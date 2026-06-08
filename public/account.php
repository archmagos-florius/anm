<?php

require dirname(__DIR__) . '/app/bootstrap.php';

$user = require_login();
$errors = [];
$action = posted('action');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if ($action === 'profile') {
        $errors = validate_required(['name' => 'Name']);
        if (!$errors) {
            db_execute('UPDATE users SET name = ?, phone = ?, updated_at = ? WHERE id = ?', [posted('name'), posted('phone'), now_text(), (int) $user['id']]);
            flash('success', 'Profile updated.');
            redirect('/account.php');
        }
    }

    if ($action === 'address_add') {
        $errors = validate_required(['label' => 'Address label', 'address' => 'Address']);
        if (!$errors) {
            db_execute('INSERT INTO customer_addresses (user_id, label, address, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [(int) $user['id'], posted('label'), posted('address'), now_text(), now_text()]);
            flash('success', 'Address saved.');
            redirect('/account.php');
        }
    }

    if ($action === 'address_update') {
        $errors = validate_required(['label' => 'Address label', 'address' => 'Address']);
        if (!$errors) {
            db_execute('UPDATE customer_addresses SET label = ?, address = ?, updated_at = ? WHERE id = ? AND user_id = ?', [posted('label'), posted('address'), now_text(), int_param('address_id'), (int) $user['id']]);
            flash('success', 'Address updated.');
            redirect('/account.php');
        }
    }

    if ($action === 'address_delete') {
        db_execute('DELETE FROM customer_addresses WHERE id = ? AND user_id = ?', [int_param('address_id'), (int) $user['id']]);
        flash('success', 'Address deleted.');
        redirect('/account.php');
    }
}

$user = current_user();
$addresses = db_fetch_all('SELECT * FROM customer_addresses WHERE user_id = ? ORDER BY label', [(int) $user['id']]);
$orders = db_fetch_all('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 25', [(int) $user['id']]);

render('auth/account', [
    'pageTitle' => 'Account',
    'user' => $user,
    'addresses' => $addresses,
    'orders' => $orders,
    'errors' => $errors,
]);
