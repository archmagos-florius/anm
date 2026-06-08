<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<div class="actions">
    <h1><?= e($menu['title']) ?></h1>
    <a href="/admin/menu-form.php?id=<?= e($menu['id']) ?>" role="button" class="secondary">Edit menu</a>
</div>
<p>Status: <?= e(status_label($menu['status'])) ?> | Current: <?= (int) $menu['is_current'] === 1 ? 'Yes' : 'No' ?> | Delivery fee: <?= e(money((int) $menu['delivery_fee_cents'])) ?></p>
<div class="actions">
    <form method="post" action="/admin/menu-detail.php?id=<?= e($menu['id']) ?>&action=release"><?= csrf_field() ?><button type="submit">Release</button></form>
    <form method="post" action="/admin/menu-detail.php?id=<?= e($menu['id']) ?>&action=make-current"><?= csrf_field() ?><button type="submit">Make current</button></form>
</div>

<section class="grid">
    <article>
        <h2>Add Item</h2>
        <form method="post" action="/admin/menu-detail.php?id=<?= e($menu['id']) ?>&action=add-entry">
            <?= csrf_field() ?>
            <label>Menu item
                <select name="menu_item_id" required>
                    <?php foreach ($items as $item): ?><option value="<?= e($item['id']) ?>"><?= e($item['name']) ?> (<?= e(money((int) $item['price_cents'])) ?>)</option><?php endforeach; ?>
                </select>
            </label>
            <label>Price <input name="price" value="0.00" required></label>
            <label>Sort order <input type="number" name="sort_order" value="0"></label>
            <button type="submit">Add to menu</button>
        </form>
    </article>
    <article>
        <h2>Prep Summary</h2>
        <?php if (!$summary): ?><p>No orders yet.</p><?php else: ?>
            <table><thead><tr><th>Item</th><th>Qty</th></tr></thead><tbody><?php foreach ($summary as $row): ?><tr><td><?= e($row['item_name']) ?></td><td><?= e($row['total_quantity']) ?></td></tr><?php endforeach; ?></tbody></table>
        <?php endif; ?>
    </article>
</section>

<h2>Menu Entries</h2>
<?php $canRemoveEntries = $menu['status'] === 'draft'; ?>
<form method="post" action="/admin/menu-detail.php?id=<?= e($menu['id']) ?>&action=update-entries">
    <?= csrf_field() ?>
    <table>
        <thead><tr><th>Item</th><th>Price</th><th>Sort</th><th>Available</th><?php if ($canRemoveEntries): ?><th>Remove</th><?php endif; ?></tr></thead>
        <tbody>
            <?php foreach ($entries as $entry): ?>
                <tr>
                    <td><?= e($entry['name']) ?></td>
                    <td><input name="entries[<?= e($entry['id']) ?>][price]" value="<?= e(cents_to_input((int) $entry['price_cents'])) ?>"></td>
                    <td><input type="number" name="entries[<?= e($entry['id']) ?>][sort_order]" value="<?= e($entry['sort_order']) ?>"></td>
                    <td><input type="checkbox" name="entries[<?= e($entry['id']) ?>][available]" value="1" <?= checked((int) $entry['available'] === 1) ?>></td>
                    <?php if ($canRemoveEntries): ?>
                        <td>
                            <button type="submit" class="secondary" form="remove-entry-<?= e($entry['id']) ?>">Remove</button>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button type="submit">Save entries</button>
</form>
<?php if ($canRemoveEntries): ?>
    <?php foreach ($entries as $entry): ?>
        <form id="remove-entry-<?= e($entry['id']) ?>" method="post" action="/admin/menu-detail.php?id=<?= e($menu['id']) ?>&action=remove-entry" class="inline-form">
            <?= csrf_field() ?>
            <input type="hidden" name="entry_id" value="<?= e($entry['id']) ?>">
        </form>
    <?php endforeach; ?>
<?php endif; ?>

<h2>Orders</h2>
<?php if (!$orders): ?><p>No orders yet.</p><?php else: ?>
    <table><thead><tr><th>Order</th><th>Name</th><th>Fulfillment</th><th>Status</th><th>Total</th></tr></thead><tbody>
        <?php foreach ($orders as $order): ?><tr><td><a href="/admin/order-detail.php?id=<?= e($order['id']) ?>">#<?= e($order['id']) ?></a></td><td><?= e($order['customer_name']) ?></td><td><?= e(status_label($order['fulfillment_type'])) ?></td><td><?= e(status_label($order['status'])) ?></td><td><?= e(money((int) $order['total_cents'])) ?></td></tr><?php endforeach; ?>
    </tbody></table>
<?php endif; ?>
