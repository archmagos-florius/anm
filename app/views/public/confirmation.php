<h1>Order Confirmed</h1>
<p>Thank you. Your order number is <strong>#<?= e($order['id']) ?></strong>.</p>

<?php require dirname(__DIR__) . '/partials/order-summary.php'; ?>

<p><a href="/" role="button">Back to menu</a></p>
