<?php $pageTitle = $pageTitle ?? site_name(); ?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - <?= e(site_name()) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body class="site-body">
<?php require __DIR__ . '/partials/header.php'; ?>
<main class="container site-main">
<?php require __DIR__ . '/partials/messages.php'; ?>
