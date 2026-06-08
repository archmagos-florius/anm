<?php

require dirname(__DIR__) . '/app/bootstrap.php';

$order = order_with_items(int_param('id'));
if (!$order) {
    http_response_code(404);
    exit('Order not found.');
}

$user = current_user();
$canView = ((int) ($_SESSION['last_order_id'] ?? 0) === (int) $order['id'])
    || ($user && (int) $user['is_admin'] === 1)
    || ($user && $order['user_id'] !== null && (int) $order['user_id'] === (int) $user['id']);

if (!$canView) {
    http_response_code(403);
    exit('Forbidden');
}

render('public/confirmation', [
    'pageTitle' => 'Order Confirmation',
    'order' => $order,
]);
