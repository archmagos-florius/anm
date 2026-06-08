<?php

require dirname(__DIR__) . '/app/bootstrap.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $errors = validate_required([
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
    ]);
    if ($emailError = validate_email_value(posted('email'))) {
        $errors[] = $emailError;
    }
    if (strlen(posted('password')) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if (db_fetch('SELECT id FROM users WHERE email = ?', [strtolower(posted('email'))])) {
        $errors[] = 'An account with that email already exists.';
    }

    if (!$errors) {
        $now = now_text();
        db_execute('INSERT INTO users (email, password_hash, name, phone, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, 0, ?, ?)', [
            strtolower(posted('email')),
            password_hash(posted('password'), PASSWORD_DEFAULT),
            posted('name'),
            posted('phone'),
            $now,
            $now,
        ]);
        $user = db_fetch('SELECT * FROM users WHERE email = ?', [strtolower(posted('email'))]);
        login_user($user);
        flash('success', 'Account created.');
        redirect('/account.php');
    }
}

render('auth/signup', [
    'pageTitle' => 'Sign up',
    'errors' => $errors,
]);
