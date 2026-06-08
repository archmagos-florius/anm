<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<div class="actions"><h1>Menus</h1><a href="/admin/menu-form.php" role="button">New menu</a></div>
<?php if (!$menus): ?>
    <p>No menus yet.</p>
<?php else: ?>
    <table>
        <thead><tr><th>Title</th><th>Release</th><th>Cutoff</th><th>Status</th><th>Current</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
                <tr><td><?= e($menu['title']) ?></td><td><?= e(format_date($menu['release_date'])) ?></td><td><?= e(format_datetime($menu['cutoff_at'])) ?></td><td><?= e(status_label($menu['status'])) ?></td><td><?= (int) $menu['is_current'] === 1 ? 'Yes' : 'No' ?></td><td><a href="/admin/menu-detail.php?id=<?= e($menu['id']) ?>">Open</a></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
