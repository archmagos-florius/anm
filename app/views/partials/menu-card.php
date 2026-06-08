<article>
    <?php if (!empty($entry['image_path'])): ?>
        <img class="menu-image" src="<?= e($entry['image_path']) ?>" alt="<?= e($entry['name']) ?>">
    <?php endif; ?>
    <h2><?= e($entry['name']) ?></h2>
    <?php if (!empty($entry['description'])): ?>
        <p><?= nl2br(e($entry['description'])) ?></p>
    <?php endif; ?>
    <p><strong><?= e(money((int) $entry['price_cents'])) ?></strong></p>
    <?php $canOrder = !$orderingClosed && (int) $entry['available'] === 1 && (int) $entry['active'] === 1; ?>
    <?php if ($canOrder): ?>
        <form method="post" action="/cart.php?action=add">
            <?= csrf_field() ?>
            <input type="hidden" name="menu_entry_id" value="<?= e($entry['id']) ?>">
            <label>Quantity <input class="quantity-input" type="number" name="quantity" min="1" max="99" value="1"></label>
            <button type="submit">Add to cart</button>
        </form>
    <?php else: ?>
        <button disabled><?= $orderingClosed ? 'Ordering closed' : 'Unavailable' ?></button>
    <?php endif; ?>
</article>
