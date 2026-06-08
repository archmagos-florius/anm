<?php

require dirname(__DIR__) . '/app/bootstrap.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(posted('email'));
    $user = db_fetch('SELECT * FROM users WHERE email = ?', [$email]);
    if (!$user || !password_verify(posted('password'), (string) $user['password_hash'])) {
        $errors[] = 'Invalid email or password.';
    } else {
        login_user($user);
        flash('success', 'Logged in.');
        redirect((int) $user['is_admin'] === 1 ? '/admin/index.php' : '/account.php');
    }
}

render('auth/login', [
    'pageTitle' => 'Login',
    'errors' => $errors,
]);
