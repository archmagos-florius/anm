<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$id = int_param('id');
$order = order_with_items($id);
if (!$order) {
    http_response_code(404);
    exit('Order not found.');
}

$emailHtml = order_email_html($order, $order['items']);

if (queried('raw') === '1') {
    header('Content-Type: text/html; charset=UTF-8');
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order #<?= e($order['id']) ?> Email Preview</title>
</head>
<body>
<?= $emailHtml ?>
</body>
</html>
    <?php
    exit;
}

render('admin/email-preview', [
    'pageTitle' => 'Order #' . $id . ' Email Preview',
    'order' => $order,
]);
