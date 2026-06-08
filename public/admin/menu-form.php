<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$id = int_param('id');
$menu = $id ? db_fetch('SELECT * FROM menus WHERE id = ?', [$id]) : null;
if ($id && !$menu) {
    http_response_code(404);
    exit('Menu not found.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $errors = validate_required([
        'title' => 'Title',
        'release_date' => 'Release date',
        'cutoff_at' => 'Cutoff time',
        'delivery_fee' => 'Delivery fee',
    ]);
    $deliveryFee = parse_money_to_cents(posted('delivery_fee'));
    $status = posted('status', 'draft');
    if (!in_array($status, ['draft', 'released', 'closed'], true)) {
        $errors[] = 'Invalid menu status.';
    }

    if (!$errors) {
        $now = now_text();
        if ($menu) {
            db_execute('UPDATE menus SET title = ?, release_date = ?, cutoff_at = ?, status = ?, delivery_fee_cents = ?, updated_at = ? WHERE id = ?', [
                posted('title'),
                posted('release_date'),
                str_replace('T', ' ', posted('cutoff_at')),
                $status,
                $deliveryFee,
                $now,
                $id,
            ]);
            flash('success', 'Menu updated.');
            redirect('/admin/menu-detail.php?id=' . $id);
        }

        db_execute('INSERT INTO menus (title, release_date, cutoff_at, status, is_current, delivery_fee_cents, created_at, updated_at) VALUES (?, ?, ?, ?, 0, ?, ?, ?)', [
            posted('title'),
            posted('release_date'),
            str_replace('T', ' ', posted('cutoff_at')),
            $status,
            $deliveryFee,
            $now,
            $now,
        ]);
        $newId = (int) db()->lastInsertId();
        flash('success', 'Menu created. Add items next.');
        redirect('/admin/menu-detail.php?id=' . $newId);
    }
}

render('admin/menu-form', [
    'pageTitle' => $menu ? 'Edit Menu' : 'New Menu',
    'menu' => $menu,
    'errors' => $errors,
]);
