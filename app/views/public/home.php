<section>
    <?php if (!$menu): ?>
        <article>
            <h1>No menu is available yet</h1>
            <p>Please check back soon.</p>
        </article>
    <?php else: ?>
        <h1><?= e($menu['title']) ?></h1>
        <p class="muted">Release date: <?= e($menu['release_date']) ?> | Cutoff: <?= e($menu['cutoff_at']) ?></p>
        <?php if ($orderingClosed): ?>
            <article class="flash flash-warning">
                Ordering is closed for this menu. You can still review the menu below.
            </article>
        <?php endif; ?>
        <div class="grid-cards">
            <?php foreach ($entries as $entry): ?>
                <?php require dirname(__DIR__) . '/partials/menu-card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
