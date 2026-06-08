<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<div class="actions"><h1>Menu Items</h1><a href="/admin/menu-item-form.php" role="button">New item</a></div>
<?php if (!$items): ?>
    <p>No menu items yet.</p>
<?php else: ?>
    <table>
        <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Source</th><th>Active</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php if (!empty($item['image_path'])): ?>
                            <img class="admin-menu-thumb" src="<?= e($item['image_path']) ?>" alt="<?= e($item['name']) ?>">
                        <?php else: ?>
                            <span class="muted">No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= e($item['name']) ?></td>
                    <td><?= e(money((int) $item['price_cents'])) ?></td>
                    <td><?= e(menu_item_image_source_label($item['image_path'] ?? null)) ?></td>
                    <td><?= (int) $item['active'] === 1 ? 'Yes' : 'No' ?></td>
                    <td><a href="/admin/menu-item-form.php?id=<?= e($item['id']) ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
