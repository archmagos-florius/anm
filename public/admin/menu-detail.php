<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$id = int_param('id');
$menu = db_fetch('SELECT * FROM menus WHERE id = ?', [$id]);
if (!$menu) {
    http_response_code(404);
    exit('Menu not found.');
}

$action = queried('action');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if ($action === 'release') {
        db_execute("UPDATE menus SET status = 'released', updated_at = ? WHERE id = ?", [now_text(), $id]);
        flash('success', 'Menu released.');
        redirect('/admin/menu-detail.php?id=' . $id);
    }

    if ($action === 'make-current') {
        db()->beginTransaction();
        db_execute('UPDATE menus SET is_current = 0, updated_at = ?', [now_text()]);
        db_execute("UPDATE menus SET is_current = 1, status = 'released', updated_at = ? WHERE id = ?", [now_text(), $id]);
        db()->commit();
        flash('success', 'Menu is now current.');
        redirect('/admin/menu-detail.php?id=' . $id);
    }

    if ($action === 'add-entry') {
        $item = db_fetch('SELECT * FROM menu_items WHERE id = ?', [int_param('menu_item_id')]);
        if ($item) {
            $priceCents = parse_money_to_cents(posted('price'));
            if ($priceCents <= 0) {
                $priceCents = (int) $item['price_cents'];
            }
            db_execute('INSERT INTO menu_entries (menu_id, menu_item_id, price_cents, available, sort_order, created_at, updated_at) VALUES (?, ?, ?, 1, ?, ?, ?)', [
                $id,
                (int) $item['id'],
                $priceCents,
                (int) posted('sort_order', '0'),
                now_text(),
                now_text(),
            ]);
            flash('success', 'Item added to menu.');
        }
        redirect('/admin/menu-detail.php?id=' . $id);
    }

    if ($action === 'update-entries') {
        foreach ($_POST['entries'] ?? [] as $entryId => $entryData) {
            db_execute('UPDATE menu_entries SET price_cents = ?, available = ?, sort_order = ?, updated_at = ? WHERE id = ? AND menu_id = ?', [
                parse_money_to_cents((string) ($entryData['price'] ?? '0')),
                isset($entryData['available']) ? 1 : 0,
                (int) ($entryData['sort_order'] ?? 0),
                now_text(),
                (int) $entryId,
                $id,
            ]);
        }
        flash('success', 'Menu entries updated.');
        redirect('/admin/menu-detail.php?id=' . $id);
    }
}

$menu = db_fetch('SELECT * FROM menus WHERE id = ?', [$id]);
$entries = menu_entries_for_menu($id);
$items = db_fetch_all('SELECT * FROM menu_items WHERE active = 1 ORDER BY name');
$orders = db_fetch_all('SELECT * FROM orders WHERE menu_id = ? ORDER BY created_at DESC', [$id]);
$summary = prep_summary($id);

render('admin/menu-detail', [
    'pageTitle' => 'Menu Detail',
    'menu' => $menu,
    'entries' => $entries,
    'items' => $items,
    'orders' => $orders,
    'summary' => $summary,
]);
