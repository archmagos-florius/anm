<?php

require dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$menus = db_fetch_all('SELECT * FROM menus ORDER BY release_date DESC, id DESC');

render('admin/menus', [
    'pageTitle' => 'Menus',
    'menus' => $menus,
]);
