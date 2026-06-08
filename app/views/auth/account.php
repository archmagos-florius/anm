<h1>Account</h1>
<?php foreach ($errors as $error): ?><article class="flash flash-error"><?= e($error) ?></article><?php endforeach; ?>

<section class="grid">
    <article>
        <h2>Profile</h2>
        <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="profile">
            <label>Name <input name="name" value="<?= e($user['name']) ?>" required></label>
            <label>Phone <input name="phone" value="<?= e($user['phone']) ?>"></label>
            <button type="submit">Save profile</button>
        </form>
    </article>
    <article>
        <h2>Add Address</h2>
        <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="address_add">
            <label>Label <input name="label" placeholder="Home" required></label>
            <label>Address <textarea name="address" required></textarea></label>
            <button type="submit">Save address</button>
        </form>
    </article>
</section>

<h2>Saved Addresses</h2>
<?php if (!$addresses): ?>
    <p>No saved addresses yet.</p>
<?php else: ?>
    <table>
        <thead><tr><th>Label</th><th>Address</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($addresses as $address): ?>
                <tr>
                    <td>
                        <form method="post" id="address-update-<?= e($address['id']) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="address_update">
                            <input type="hidden" name="address_id" value="<?= e($address['id']) ?>">
                            <input name="label" value="<?= e($address['label']) ?>" required>
                        </form>
                    </td>
                    <td><textarea name="address" form="address-update-<?= e($address['id']) ?>" required><?= e($address['address']) ?></textarea></td>
                    <td class="actions">
                        <button type="submit" form="address-update-<?= e($address['id']) ?>">Save</button>
                        <form method="post" class="inline-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="address_delete">
                            <input type="hidden" name="address_id" value="<?= e($address['id']) ?>">
                            <button type="submit" class="secondary">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2>Order History</h2>
<?php if (!$orders): ?>
    <p>No orders yet.</p>
<?php else: ?>
    <table>
        <thead><tr><th>Order</th><th>Date</th><th>Status</th><th>Total</th></tr></thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= e($order['id']) ?></td>
                    <td><?= e($order['created_at']) ?></td>
                    <td><?= e(status_label($order['status'])) ?></td>
                    <td><?= e(money((int) $order['total_cents'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
