<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$id = int_param('id');
$order = order_with_items($id);
if (!$order) {
    http_response_code(404);
    exit('Order not found.');
}

$action = queried('action');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if ($action === 'cancel') {
        db_execute("UPDATE orders SET status = 'cancelled', updated_at = ? WHERE id = ?", [now_text(), $id]);
        flash('success', 'Order cancelled.');
        redirect('/admin/order-detail.php?id=' . $id);
    }

    if ($action === 'fulfill') {
        db_execute("UPDATE orders SET status = 'fulfilled', updated_at = ? WHERE id = ?", [now_text(), $id]);
        flash('success', 'Order marked fulfilled.');
        redirect('/admin/order-detail.php?id=' . $id);
    }

    if ($action === 'save') {
        $fulfillment = posted('fulfillment_type', 'pickup');
        if (!in_array($fulfillment, ['pickup', 'delivery'], true)) {
            $fulfillment = 'pickup';
        }
        $status = posted('status', 'confirmed');
        if (!in_array($status, ['confirmed', 'fulfilled', 'cancelled'], true)) {
            $status = 'confirmed';
        }

        db_execute('UPDATE orders SET customer_name = ?, customer_email = ?, customer_phone = ?, fulfillment_type = ?, delivery_address = ?, customer_notes = ?, status = ?, updated_at = ? WHERE id = ?', [
            posted('customer_name'),
            strtolower(posted('customer_email')),
            posted('customer_phone'),
            $fulfillment,
            $fulfillment === 'delivery' ? posted('delivery_address') : null,
            posted('customer_notes'),
            $status,
            now_text(),
            $id,
        ]);

        foreach ($_POST['items'] ?? [] as $itemId => $quantity) {
            $quantity = (int) $quantity;
            if ($quantity <= 0) {
                db_execute('DELETE FROM order_items WHERE id = ? AND order_id = ?', [(int) $itemId, $id]);
            } else {
                db_execute('UPDATE order_items SET quantity = ?, updated_at = ? WHERE id = ? AND order_id = ?', [$quantity, now_text(), (int) $itemId, $id]);
            }
        }

        $addEntryId = (int) posted('add_menu_entry_id');
        $addQuantity = max(0, (int) posted('add_quantity', '0'));
        if ($addEntryId > 0 && $addQuantity > 0) {
            $entry = db_fetch('SELECT me.*, mi.name FROM menu_entries me JOIN menu_items mi ON mi.id = me.menu_item_id WHERE me.id = ? AND me.menu_id = ?', [$addEntryId, (int) $order['menu_id']]);
            if ($entry) {
                db_execute('INSERT INTO order_items (order_id, menu_entry_id, item_name_snapshot, unit_price_cents, quantity, line_total_cents, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
                    $id,
                    $addEntryId,
                    $entry['name'],
                    (int) $entry['price_cents'],
                    $addQuantity,
                    (int) $entry['price_cents'] * $addQuantity,
                    now_text(),
                    now_text(),
                ]);
            }
        }

        recalculate_order_totals($id);
        $updated = db_fetch('SELECT * FROM orders WHERE id = ?', [$id]);
        if ($updated && posted('send_update_email') === '1') {
            send_order_confirmation($updated);
        }
        flash('success', 'Order saved.');
        redirect('/admin/order-detail.php?id=' . $id);
    }
}

$order = order_with_items($id);
$menuEntries = menu_entries_for_menu((int) $order['menu_id']);

render('admin/order-detail', [
    'pageTitle' => 'Order #' . $id,
    'order' => $order,
    'menuEntries' => $menuEntries,
]);
