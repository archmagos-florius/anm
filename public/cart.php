<?php

require dirname(__DIR__) . '/app/bootstrap.php';

$action = queried('action');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if ($action === 'add') {
        $entryId = int_param('menu_entry_id');
        $quantity = max(1, (int) posted('quantity', '1'));
        $entry = db_fetch(
            "SELECT me.*, mi.active FROM menu_entries me JOIN menu_items mi ON mi.id = me.menu_item_id JOIN menus m ON m.id = me.menu_id WHERE me.id = ? AND m.is_current = 1 AND m.status = 'released'",
            [$entryId]
        );
        $menu = $entry ? db_fetch('SELECT * FROM menus WHERE id = ?', [(int) $entry['menu_id']]) : null;

        if (!$entry || !$menu || (int) $entry['available'] !== 1 || (int) $entry['active'] !== 1 || cutoff_passed($menu)) {
            flash('error', 'That item is not available for ordering.');
            redirect('/');
        }

        $currentQuantity = (int) (cart_items()[$entryId] ?? 0);
        set_cart_item((int) $entry['menu_id'], $entryId, $currentQuantity + $quantity);
        flash('success', 'Added item to cart.');
        redirect('/cart.php');
    }

    if ($action === 'update') {
        foreach ($_POST['quantities'] ?? [] as $entryId => $quantity) {
            if (cart_menu_id()) {
                set_cart_item((int) cart_menu_id(), (int) $entryId, (int) $quantity);
            }
        }
        flash('success', 'Cart updated.');
        redirect('/cart.php');
    }

    if ($action === 'remove') {
        if (cart_menu_id()) {
            set_cart_item((int) cart_menu_id(), int_param('menu_entry_id'), 0);
        }
        flash('success', 'Item removed.');
        redirect('/cart.php');
    }
}

$cart = cart_details();

render('public/cart', [
    'pageTitle' => 'Cart',
    'cart' => $cart,
]);
