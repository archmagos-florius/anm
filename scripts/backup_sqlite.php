<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$source = (string) config('DATABASE_PATH');
if (!is_file($source)) {
    exit("Database file does not exist: {$source}\n");
}

$backupDir = dirname(__DIR__) . '/storage/backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0775, true);
}

$target = $backupDir . '/database-' . date('Ymd-His') . '.sqlite';
if (!copy($source, $target)) {
    exit("Could not create backup.\n");
}

echo "Backup created: {$target}\n";
