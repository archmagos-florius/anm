<?php

declare(strict_types=1);

$root = dirname(__DIR__);

$configFile = __DIR__ . '/config.php';
if (!is_file($configFile)) {
    $configFile = __DIR__ . '/config.example.php';
}

$GLOBALS['app_config'] = require $configFile;

$timezone = $GLOBALS['app_config']['APP_TIMEZONE'] ?? 'UTC';
if ($timezone === 'TODO_TIMEZONE') {
    $timezone = 'UTC';
}
date_default_timezone_set($timezone);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('catering_mvp_session');
    session_start();
}

foreach ([$root . '/storage', $root . '/storage/backups', $root . '/public/uploads/menu-items'] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

$autoload = $root . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/domain.php';
require_once __DIR__ . '/maps.php';
require_once __DIR__ . '/mail.php';
require_once __DIR__ . '/images.php';
