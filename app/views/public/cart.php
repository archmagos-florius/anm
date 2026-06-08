<h1>Your Cart</h1>

<?php if (!$cart['rows']): ?>
    <p>Your cart is empty.</p>
    <p><a href="/" role="button">View menu</a></p>
<?php else: ?>
    <?php require dirname(__DIR__) . '/partials/cart-table.php'; ?>
    <p><a href="/checkout.php" role="button">Continue to checkout</a></p>
<?php endif; ?>
