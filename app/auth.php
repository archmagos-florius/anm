<?php

declare(strict_types=1);

function current_user(): ?array
{
    static $user = null;

    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    if ($user !== null && (int) $user['id'] === (int) $_SESSION['user_id']) {
        return $user;
    }

    $user = db_fetch('SELECT * FROM users WHERE id = ?', [(int) $_SESSION['user_id']]);
    if (!$user) {
        unset($_SESSION['user_id']);
        return null;
    }

    return $user;
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
}

function logout_user(): void
{
    unset($_SESSION['user_id']);
    session_regenerate_id(true);
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        flash('warning', 'Please log in first.');
        redirect('/login.php');
    }
    return $user;
}

function require_admin(): array
{
    $user = require_login();
    if ((int) $user['is_admin'] !== 1) {
        http_response_code(403);
        exit('Forbidden');
    }
    return $user;
}
