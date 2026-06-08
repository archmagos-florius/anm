<form method="post" action="/cart.php?action=update">
    <?= csrf_field() ?>
    <table>
        <thead>
            <tr><th>Item</th><th>Price</th><th>Quantity</th><th>Line total</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($cart['rows'] as $row): ?>
                <tr>
                    <td><?= e($row['entry']['name']) ?></td>
                    <td><?= e(money((int) $row['entry']['price_cents'])) ?></td>
                    <td><input class="quantity-input" type="number" name="quantities[<?= e($row['entry']['id']) ?>]" min="0" max="99" value="<?= e($row['quantity']) ?>"></td>
                    <td><?= e(money((int) $row['line_total_cents'])) ?></td>
                    <td>
                        <button form="remove-<?= e($row['entry']['id']) ?>" class="secondary">Remove</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><th colspan="3">Subtotal</th><th><?= e(money((int) $cart['subtotal_cents'])) ?></th><th></th></tr>
        </tfoot>
    </table>
    <button type="submit">Update cart</button>
</form>
<?php foreach ($cart['rows'] as $row): ?>
    <form id="remove-<?= e($row['entry']['id']) ?>" method="post" action="/cart.php?action=remove">
        <?= csrf_field() ?>
        <input type="hidden" name="menu_entry_id" value="<?= e($row['entry']['id']) ?>">
    </form>
<?php endforeach; ?>
