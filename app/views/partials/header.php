<header class="container site-header">
    <nav>
        <ul>
            <li><strong><a href="/"> <?= e(config('SITE_NAME', 'Catering')) ?></a></strong></li>
        </ul>
        <ul>
            <li><a href="/cart.php">Cart (<?= e(cart_count()) ?>)</a></li>
            <?php if ($user = current_user()): ?>
                <?php if ((int) $user['is_admin'] === 1): ?>
                    <li><a href="/admin/index.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="/account.php">Account</a></li>
                <li>
                    <form action="/logout.php" method="post" class="inline-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="secondary">Logout</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a href="/login.php">Login</a></li>
                <li><a href="/signup.php">Sign up</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
