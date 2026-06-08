<?php $canOrder = !$orderingClosed && (int) $entry['available'] === 1 && (int) $entry['active'] === 1; ?>
<article class="menu-card <?= $canOrder ? '' : 'menu-card-disabled' ?>">
    <div class="menu-media">
        <?php if (!empty($entry['image_path'])): ?>
            <img class="menu-image" src="<?= e($entry['image_path']) ?>" alt="<?= e($entry['name']) ?>">
        <?php else: ?>
            <div class="menu-placeholder" aria-hidden="true">
                <span><?= e(site_name()) ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="menu-card-body">
        <div class="menu-card-heading">
            <h2><?= e($entry['name']) ?></h2>
            <p class="menu-price"><?= e(money((int) $entry['price_cents'])) ?></p>
        </div>
        <?php if (!empty($entry['description'])): ?>
            <p class="menu-description"><?= nl2br(e($entry['description'])) ?></p>
        <?php endif; ?>
    </div>
    <?php if ($canOrder): ?>
        <form class="menu-card-form" method="post" action="/cart.php?action=add">
            <?= csrf_field() ?>
            <input type="hidden" name="menu_entry_id" value="<?= e($entry['id']) ?>">
            <label>Quantity <input class="quantity-input" type="number" name="quantity" min="1" max="99" value="1"></label>
            <button type="submit">Add to cart</button>
        </form>
    <?php else: ?>
        <div class="menu-unavailable">
            <button disabled><?= $orderingClosed ? 'Ordering closed' : 'Unavailable' ?></button>
        </div>
    <?php endif; ?>
</article>
