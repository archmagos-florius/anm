<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<h1><?= $menu ? 'Edit Menu' : 'New Menu' ?></h1>
<?php foreach ($errors as $error): ?><article class="flash flash-error"><?= e($error) ?></article><?php endforeach; ?>
<form method="post">
    <?= csrf_field() ?>
    <label>Title <input name="title" value="<?= e(posted('title', $menu['title'] ?? '')) ?>" required></label>
    <label>Release date <input type="date" name="release_date" value="<?= e(posted('release_date', $menu['release_date'] ?? date('Y-m-d'))) ?>" required></label>
    <label>Cutoff time <input type="datetime-local" name="cutoff_at" value="<?= e(posted('cutoff_at', isset($menu) ? str_replace(' ', 'T', substr($menu['cutoff_at'], 0, 16)) : date('Y-m-d\TH:i'))) ?>" required></label>
    <label>Delivery fee <input name="delivery_fee" value="<?= e(posted('delivery_fee', isset($menu) ? cents_to_input((int) $menu['delivery_fee_cents']) : '0.00')) ?>" required></label>
    <label>Status
        <select name="status">
            <?php foreach (['draft', 'released', 'closed'] as $status): ?>
                <option value="<?= e($status) ?>" <?= selected(posted('status', $menu['status'] ?? 'draft'), $status) ?>><?= e(status_label($status)) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Save menu</button>
</form>
