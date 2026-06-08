<section class="home-page">
    <?php if (!$menu): ?>
        <article class="hero empty-menu-hero">
            <p class="eyebrow"><?= e(site_name()) ?></p>
            <h1>Homemade Peruvian comfort food is coming soon.</h1>
            <p>Menus are released in small batches. Please check back soon for the next pickup and delivery window.</p>
            <a href="/cart.php" role="button" class="secondary">View cart</a>
        </article>
    <?php else: ?>
        <section class="hero menu-hero">
            <div>
                <p class="eyebrow">Fresh this menu</p>
                <h1>Homemade Peruvian comfort food, released fresh by menu.</h1>
                <p>Order rustic, home-style dishes from <?= e(site_name()) ?> while this menu is open. Choose pickup or delivery at checkout.</p>
                <div class="hero-actions">
                    <a href="#current-menu" role="button">Order from this menu</a>
                    <a href="/cart.php" role="button" class="secondary">View cart</a>
                </div>
            </div>
            <aside class="menu-meta-card" aria-label="Current menu details">
                <span class="badge">Current menu</span>
                <h2><?= e($menu['title']) ?></h2>
                <p><strong>Release date</strong><br><?= e($menu['release_date']) ?></p>
                <p><strong>Order cutoff</strong><br><?= e($menu['cutoff_at']) ?></p>
            </aside>
        </section>
        <?php if ($orderingClosed): ?>
            <article class="flash flash-warning closed-banner">
                <strong>Ordering is closed for this menu.</strong>
                <span>You can still review the dishes below and check back for the next release.</span>
            </article>
        <?php endif; ?>
        <div id="current-menu" class="section-heading">
            <p class="eyebrow">Menu</p>
            <h2><?= e($menu['title']) ?></h2>
        </div>
        <div class="grid-cards menu-grid">
            <?php foreach ($entries as $entry): ?>
                <?php require dirname(__DIR__) . '/partials/menu-card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
