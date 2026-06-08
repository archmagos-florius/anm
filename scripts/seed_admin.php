<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    exit("Run this script from the command line.\n");
}

[$script, $email, $name, $password] = array_pad($argv, 4, null);

if (!$email || !$name || !$password) {
    exit("Usage: php scripts/seed_admin.php admin@example.com \"Admin Name\" \"password\"\n");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email.\n");
}

$existing = db_fetch('SELECT id FROM users WHERE email = ?', [$email]);
$now = now_text();

if ($existing) {
    db_execute('UPDATE users SET password_hash = ?, name = ?, is_admin = 1, updated_at = ? WHERE email = ?', [
        password_hash($password, PASSWORD_DEFAULT),
        $name,
        $now,
        $email,
    ]);
    echo "Updated existing admin: {$email}\n";
    exit;
}

db_execute('INSERT INTO users (email, password_hash, name, phone, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, 1, ?, ?)', [
    $email,
    password_hash($password, PASSWORD_DEFAULT),
    $name,
    '',
    $now,
    $now,
]);

echo "Created admin: {$email}\n";
