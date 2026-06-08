<?php require dirname(__DIR__) . '/partials/admin-nav.php'; ?>
<h1><?= $item ? 'Edit Menu Item' : 'New Menu Item' ?></h1>
<?php foreach ($errors as $error): ?><article class="flash flash-error"><?= e($error) ?></article><?php endforeach; ?>
<form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <label>Name <input name="name" value="<?= e(posted('name', $item['name'] ?? '')) ?>" required></label>
    <label>Description <textarea name="description"><?= e(posted('description', $item['description'] ?? '')) ?></textarea></label>
    <label>Price <input name="price" value="<?= e(posted('price', isset($item) ? cents_to_input((int) $item['price_cents']) : '')) ?>" required></label>
    <label>Image <input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
    <?php if (!empty($item['image_path'])): ?><img class="menu-image" src="<?= e($item['image_path']) ?>" alt="<?= e($item['name']) ?>"><?php endif; ?>
    <label><input type="checkbox" name="active" value="1" <?= checked((int) ($item['active'] ?? 1) === 1) ?>> Active</label>
    <button type="submit">Save</button>
</form>
