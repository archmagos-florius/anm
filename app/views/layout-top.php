<?php $pageTitle = $pageTitle ?? config('SITE_NAME', 'Catering'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - <?= e(config('SITE_NAME', 'Catering')) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main class="container">
<?php require __DIR__ . '/partials/messages.php'; ?>
