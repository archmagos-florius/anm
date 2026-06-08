<?php

require dirname(__DIR__) . '/app/bootstrap.php';

$cart = cart_details();
if (!$cart['menu'] || !$cart['rows']) {
    flash('warning', 'Your cart is empty.');
    redirect('/cart.php');
}
if (cutoff_passed($cart['menu'])) {
    flash('warning', 'Ordering is closed for this menu.');
    redirect('/cart.php');
}

$user = current_user();
$addresses = $user ? db_fetch_all('SELECT * FROM customer_addresses WHERE user_id = ? ORDER BY label', [(int) $user['id']]) : [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $fulfillment = posted('fulfillment_type', 'pickup');
    $fields = [
        'customer_name' => 'Name',
        'customer_email' => 'Email',
        'customer_phone' => 'Phone',
    ];
    if ($fulfillment === 'delivery') {
        $fields['delivery_address'] = 'Delivery address';
    }
    $errors = validate_required($fields);
    if ($emailError = validate_email_value(posted('customer_email'))) {
        $errors[] = $emailError;
    }
    if (!in_array($fulfillment, ['pickup', 'delivery'], true)) {
        $errors[] = 'Choose pickup or delivery.';
    }

    if (!$errors) {
        try {
            $orderId = create_order_from_cart([
                'user_id' => $user ? (int) $user['id'] : null,
                'customer_name' => posted('customer_name'),
                'customer_email' => strtolower(posted('customer_email')),
                'customer_phone' => posted('customer_phone'),
                'fulfillment_type' => $fulfillment,
                'delivery_address' => posted('delivery_address'),
                'customer_notes' => posted('customer_notes'),
            ]);

            if ($user && $fulfillment === 'delivery' && posted('save_address') === '1' && posted('delivery_address') !== '') {
                db_execute('INSERT INTO customer_addresses (user_id, label, address, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
                    (int) $user['id'],
                    posted('address_label', 'Saved Address') ?: 'Saved Address',
                    posted('delivery_address'),
                    now_text(),
                    now_text(),
                ]);
            }

            $order = db_fetch('SELECT * FROM orders WHERE id = ?', [$orderId]);
            if ($order) {
                send_order_confirmation($order);
                send_admin_new_order($order);
            }
            $_SESSION['last_order_id'] = $orderId;
            clear_cart();
            redirect('/confirmation.php?id=' . $orderId);
        } catch (RuntimeException $exception) {
            $errors[] = $exception->getMessage();
        }
    }
}

render('public/checkout', [
    'pageTitle' => 'Checkout',
    'cart' => $cart,
    'user' => $user,
    'addresses' => $addresses,
    'errors' => $errors,
]);
