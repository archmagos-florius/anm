<header class="site-header-wrap">
    <nav class="container site-header">
        <ul>
            <li>
                <a class="site-brand" href="/" aria-label="<?= e(site_name()) ?> home">
                    <span class="site-brand-mark" aria-hidden="true">
                        <img src="/assets/brand/logo.svg" alt="" width="44" height="44">
                    </span>
                    <span>
                        <strong><?= e(site_name()) ?></strong>
                        <small>Peruvian homemade catering</small>
                    </span>
                </a>
            </li>
        </ul>
        <ul class="site-nav-links">
            <li><a class="cart-link" href="/cart.php">Cart <span><?= e(cart_count()) ?></span></a></li>
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
