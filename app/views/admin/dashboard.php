<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<h1>Admin Dashboard</h1>

<?php if ($menu): ?>
    <article>
        <h2>Current Menu</h2>
        <p><strong><?= e($menu['title']) ?></strong></p>
        <p>Cutoff: <?= e(format_datetime($menu['cutoff_at'])) ?> <?= cutoff_passed($menu) ? '(closed)' : '' ?></p>
        <p><a href="/admin/menu-detail.php?id=<?= e($menu['id']) ?>" role="button">Open menu dashboard</a></p>
    </article>
<?php else: ?>
    <article><p>No current menu selected.</p></article>
<?php endif; ?>

<section class="grid">
    <article>
        <h2>Prep Summary</h2>
        <?php if (!$summary): ?>
            <p>No orders yet.</p>
        <?php else: ?>
            <table><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>
                <?php foreach ($summary as $row): ?><tr><td><?= e($row['item_name']) ?></td><td><?= e($row['total_quantity']) ?></td></tr><?php endforeach; ?>
            </tbody></table>
        <?php endif; ?>
    </article>
    <article>
        <h2>Recent Orders</h2>
        <?php if (!$recentOrders): ?>
            <p>No orders yet.</p>
        <?php else: ?>
            <table><thead><tr><th>Order</th><th>Name</th><th>Status</th><th>Total</th></tr></thead><tbody>
                <?php foreach ($recentOrders as $order): ?>
                    <tr><td><a href="/admin/order-detail.php?id=<?= e($order['id']) ?>">#<?= e($order['id']) ?></a></td><td><?= e($order['customer_name']) ?></td><td><?= e(status_label($order['status'])) ?></td><td><?= e(money((int) $order['total_cents'])) ?></td></tr>
                <?php endforeach; ?>
            </tbody></table>
        <?php endif; ?>
    </article>
</section>
