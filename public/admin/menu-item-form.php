<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$id = int_param('id');
$item = $id ? db_fetch('SELECT * FROM menu_items WHERE id = ?', [$id]) : null;
if ($id && !$item) {
    http_response_code(404);
    exit('Menu item not found.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $errors = validate_required(['name' => 'Name', 'price' => 'Price']);
    $priceCents = parse_money_to_cents(posted('price'));
    if ($priceCents <= 0) {
        $errors[] = 'Price must be greater than zero.';
    }

    $oldImagePath = $item['image_path'] ?? null;
    $imagePath = $oldImagePath;
    $uploadedPath = null;
    try {
        $uploadedPath = save_menu_item_image($_FILES['image'] ?? []);
        if ($uploadedPath !== null) {
            $imagePath = $uploadedPath;
        }
    } catch (RuntimeException $exception) {
        $errors[] = $exception->getMessage();
    }

    if (!$errors) {
        $now = now_text();
        if ($item) {
            db_execute('UPDATE menu_items SET name = ?, description = ?, price_cents = ?, image_path = ?, active = ?, updated_at = ? WHERE id = ?', [
                posted('name'),
                posted('description'),
                $priceCents,
                $imagePath,
                posted('active') === '1' ? 1 : 0,
                $now,
                $id,
            ]);
            if ($uploadedPath !== null && $uploadedPath !== $oldImagePath) {
                delete_menu_item_upload($oldImagePath);
            }
            flash('success', 'Menu item updated.');
        } else {
            db_execute('INSERT INTO menu_items (name, description, price_cents, image_path, active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)', [
                posted('name'),
                posted('description'),
                $priceCents,
                $imagePath,
                posted('active') === '1' ? 1 : 0,
                $now,
                $now,
            ]);
            flash('success', 'Menu item created.');
        }
        redirect('/admin/menu-items.php');
    }
}

render('admin/menu-item-form', [
    'pageTitle' => $item ? 'Edit Menu Item' : 'New Menu Item',
    'item' => $item,
    'errors' => $errors,
]);
