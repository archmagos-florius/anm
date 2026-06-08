<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<div class="actions"><h1>Menu Items</h1><a href="/admin/menu-item-form.php" role="button">New item</a></div>
<?php if (!$items): ?>
    <p>No menu items yet.</p>
<?php else: ?>
    <table>
        <thead><tr><th>Name</th><th>Price</th><th>Active</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr><td><?= e($item['name']) ?></td><td><?= e(money((int) $item['price_cents'])) ?></td><td><?= (int) $item['active'] === 1 ? 'Yes' : 'No' ?></td><td><a href="/admin/menu-item-form.php?id=<?= e($item['id']) ?>">Edit</a></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
