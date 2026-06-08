<?php

require dirname(__DIR__) . '/app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
}

logout_user();
flash('success', 'Logged out.');
redirect('/');
