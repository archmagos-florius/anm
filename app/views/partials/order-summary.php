<?php
$summaryOrder = $order ?? null;
$summaryRows = $summaryOrder ? array_map(fn($item) => ['name' => $item['item_name_snapshot'], 'quantity' => $item['quantity'], 'unit' => $item['unit_price_cents'], 'line' => $item['line_total_cents']], $summaryOrder['items']) : array_map(fn($row) => ['name' => $row['entry']['name'], 'quantity' => $row['quantity'], 'unit' => $row['entry']['price_cents'], 'line' => $row['line_total_cents']], $cart['rows']);
$subtotal = $summaryOrder ? (int) $summaryOrder['subtotal_cents'] : (int) $cart['subtotal_cents'];
$deliveryFee = $summaryOrder ? (int) $summaryOrder['delivery_fee_cents'] : (int) $cart['menu']['delivery_fee_cents'];
$total = $summaryOrder ? (int) $summaryOrder['total_cents'] : $subtotal;
?>
<article class="order-summary-card">
    <h2>Order Summary</h2>
    <?php if ($summaryOrder): ?>
        <div class="summary-meta">
            <p><strong>Status</strong><br><?= e(status_label($summaryOrder['status'])) ?></p>
            <p><strong>Fulfillment</strong><br><?= e(status_label($summaryOrder['fulfillment_type'])) ?></p>
            <p><strong>Placed</strong><br><?= e(format_datetime($summaryOrder['created_at'])) ?></p>
        </div>
        <?php if ($summaryOrder['fulfillment_type'] === 'delivery'): ?>
            <p><strong>Delivery address:</strong><br><?= nl2br(e($summaryOrder['delivery_address'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($summaryOrder['customer_notes'])): ?>
            <p><strong>Notes:</strong><br><?= nl2br(e($summaryOrder['customer_notes'])) ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <table class="responsive-table summary-table">
        <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
        <tbody>
            <?php foreach ($summaryRows as $row): ?>
                <tr>
                    <td data-label="Item"><strong><?= e($row['name']) ?></strong></td>
                    <td data-label="Qty"><?= e($row['quantity']) ?></td>
                    <td data-label="Price"><?= e(money((int) $row['unit'])) ?></td>
                    <td data-label="Total"><strong><?= e(money((int) $row['line'])) ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><th colspan="3">Subtotal</th><th><?= e(money($subtotal)) ?></th></tr>
            <?php if ($summaryOrder && (int) $summaryOrder['delivery_fee_cents'] > 0): ?>
                <tr><th colspan="3">Delivery fee</th><th><?= e(money($deliveryFee)) ?></th></tr>
            <?php endif; ?>
            <tr><th colspan="3">Total</th><th><?= e(money($summaryOrder ? $total : $subtotal)) ?></th></tr>
        </tfoot>
    </table>
</article>
