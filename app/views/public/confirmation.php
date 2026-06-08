<section class="page-hero confirmation-hero">
    <p class="eyebrow">Order confirmed</p>
    <h1>Thank you for ordering from <?= e(site_name()) ?>.</h1>
    <p>Your order number is <strong>#<?= e($order['id']) ?></strong>. We will prepare it for your selected pickup or delivery option.</p>
</section>

<?php require dirname(__DIR__) . '/partials/order-summary.php'; ?>

<p><a href="/" role="button">Back to menu</a></p>
