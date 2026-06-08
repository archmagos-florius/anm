<?php

declare(strict_types=1);

function validate_required(array $fields): array
{
    $errors = [];
    foreach ($fields as $field => $label) {
        if (posted($field) === '') {
            $errors[] = $label . ' is required.';
        }
    }
    return $errors;
}

function validate_email_value(string $email): ?string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Please enter a valid email address.';
    }
    return null;
}
