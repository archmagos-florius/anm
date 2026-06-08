<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$items = db_fetch_all('SELECT * FROM menu_items ORDER BY active DESC, name');

render('admin/menu-items', [
    'pageTitle' => 'Menu Items',
    'items' => $items,
]);
