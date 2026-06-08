<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$menu = current_menu();
$recentOrders = db_fetch_all('SELECT * FROM orders ORDER BY created_at DESC LIMIT 10');
$summary = $menu ? prep_summary((int) $menu['id']) : [];

render('admin/dashboard', [
    'pageTitle' => 'Admin Dashboard',
    'menu' => $menu,
    'recentOrders' => $recentOrders,
    'summary' => $summary,
]);
