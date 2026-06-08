<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<div class="actions">
    <h1>Order #<?= e($order['id']) ?> Email Preview</h1>
    <a href="/admin/order-detail.php?id=<?= e($order['id']) ?>" role="button" class="secondary">Back to order</a>
</div>

<p>This preview uses the same generated HTML as the customer order email. It does not send an email.</p>

<iframe
    title="Order #<?= e($order['id']) ?> customer email preview"
    src="/admin/email-preview.php?id=<?= e($order['id']) ?>&raw=1"
    width="100%"
    height="700"
></iframe>
