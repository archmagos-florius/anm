<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<h1>Order #<?= e($order['id']) ?></h1>
<p>Menu: <?= e($order['menu_title']) ?> | Created: <?= e($order['created_at']) ?></p>

<?php if ($order['fulfillment_type'] === 'delivery' && !empty($order['delivery_address'])): ?>
    <p><a href="<?= e(maps_directions_url($order['delivery_address'])) ?>" target="_blank" rel="noopener" role="button">Open Google Maps directions</a></p>
<?php endif; ?>

<form method="post" action="/admin/order-detail.php?id=<?= e($order['id']) ?>&action=save">
    <?= csrf_field() ?>
    <section class="grid">
        <article>
            <h2>Customer</h2>
            <label>Name <input name="customer_name" value="<?= e($order['customer_name']) ?>" required></label>
            <label>Email <input type="email" name="customer_email" value="<?= e($order['customer_email']) ?>" required></label>
            <label>Phone <input name="customer_phone" value="<?= e($order['customer_phone']) ?>" required></label>
            <label>Status
                <select name="status">
                    <?php foreach (['confirmed', 'fulfilled', 'cancelled'] as $status): ?><option value="<?= e($status) ?>" <?= selected($order['status'], $status) ?>><?= e(status_label($status)) ?></option><?php endforeach; ?>
                </select>
            </label>
        </article>
        <article>
            <h2>Fulfillment</h2>
            <label><input type="radio" name="fulfillment_type" value="pickup" <?= checked($order['fulfillment_type'] === 'pickup') ?>> Pickup</label>
            <label><input type="radio" name="fulfillment_type" value="delivery" <?= checked($order['fulfillment_type'] === 'delivery') ?>> Delivery</label>
            <label>Delivery address <textarea name="delivery_address"><?= e($order['delivery_address']) ?></textarea></label>
            <label>Notes <textarea name="customer_notes"><?= e($order['customer_notes']) ?></textarea></label>
        </article>
    </section>

    <h2>Items</h2>
    <table>
        <thead><tr><th>Item</th><th>Unit</th><th>Qty</th><th>Line total</th></tr></thead>
        <tbody>
            <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td><?= e($item['item_name_snapshot']) ?></td>
                    <td><?= e(money((int) $item['unit_price_cents'])) ?></td>
                    <td><input class="quantity-input" type="number" min="0" max="99" name="items[<?= e($item['id']) ?>]" value="<?= e($item['quantity']) ?>"></td>
                    <td><?= e(money((int) $item['line_total_cents'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <fieldset>
        <legend>Add item from this menu</legend>
        <label>Item
            <select name="add_menu_entry_id">
                <option value="0">Do not add item</option>
                <?php foreach ($menuEntries as $entry): ?><option value="<?= e($entry['id']) ?>"><?= e($entry['name']) ?> (<?= e(money((int) $entry['price_cents'])) ?>)</option><?php endforeach; ?>
            </select>
        </label>
        <label>Quantity <input class="quantity-input" type="number" min="0" max="99" name="add_quantity" value="0"></label>
    </fieldset>

    <p>Subtotal: <?= e(money((int) $order['subtotal_cents'])) ?> | Delivery: <?= e(money((int) $order['delivery_fee_cents'])) ?> | Total: <strong><?= e(money((int) $order['total_cents'])) ?></strong></p>
    <label><input type="checkbox" name="send_update_email" value="1"> Send updated order email to customer</label>
    <button type="submit">Save order</button>
</form>

<div class="actions">
    <form method="post" action="/admin/order-detail.php?id=<?= e($order['id']) ?>&action=cancel"><?= csrf_field() ?><button type="submit" class="secondary">Cancel order</button></form>
    <form method="post" action="/admin/order-detail.php?id=<?= e($order['id']) ?>&action=fulfill"><?= csrf_field() ?><button type="submit">Mark fulfilled</button></form>
</div>
