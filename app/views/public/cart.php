<section class="page-hero compact-hero">
    <p class="eyebrow">Your order</p>
    <h1>Your Cart</h1>
    <p>Review your dishes before heading to checkout.</p>
</section>

<?php if (!$cart['rows']): ?>
    <article class="empty-state">
        <h2>Your cart is empty.</h2>
        <p>Choose from the current <?= e(site_name()) ?> menu when ordering is open.</p>
        <p><a href="/" role="button">View menu</a></p>
    </article>
<?php else: ?>
    <section class="cart-layout">
        <div>
            <?php require dirname(__DIR__) . '/partials/cart-table.php'; ?>
        </div>
        <aside class="total-card">
            <p class="eyebrow">Subtotal</p>
            <strong><?= e(money((int) $cart['subtotal_cents'])) ?></strong>
            <p>No tax is added in this MVP. Delivery fee is applied at checkout when delivery is selected.</p>
            <a href="/checkout.php" role="button">Continue to checkout</a>
        </aside>
    </section>
<?php endif; ?>
