<?php

require dirname(__DIR__) . '/app/bootstrap.php';

$menu = current_menu();
$entries = $menu ? menu_entries_for_menu((int) $menu['id']) : [];
$orderingClosed = $menu ? cutoff_passed($menu) : false;

render('public/home', [
    'pageTitle' => 'Menu',
    'menu' => $menu,
    'entries' => $entries,
    'orderingClosed' => $orderingClosed,
]);
