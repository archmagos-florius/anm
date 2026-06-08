<?php

declare(strict_types=1);

function save_menu_item_image(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed.');
    }
    if ((int) $file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Image must be 2MB or smaller.');
    }

    $info = getimagesize((string) $file['tmp_name']);
    if ($info === false) {
        throw new RuntimeException('Uploaded file is not a valid image.');
    }

    $mime = $info['mime'] ?? '';
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($extensions[$mime])) {
        throw new RuntimeException('Only JPG, PNG, and WebP images are allowed.');
    }

    [$width, $height] = $info;
    $targetWidth = min(900, (int) $width);
    $targetHeight = (int) round(((int) $height) * ($targetWidth / (int) $width));

    $source = match ($mime) {
        'image/jpeg' => imagecreatefromjpeg((string) $file['tmp_name']),
        'image/png' => imagecreatefrompng((string) $file['tmp_name']),
        'image/webp' => imagecreatefromwebp((string) $file['tmp_name']),
        default => false,
    };
    if (!$source) {
        throw new RuntimeException('Could not process uploaded image.');
    }

    $target = imagecreatetruecolor($targetWidth, $targetHeight);
    imagealphablending($target, false);
    imagesavealpha($target, true);
    imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, (int) $width, (int) $height);

    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
    $relativePath = '/uploads/menu-items/' . $filename;
    $fullPath = dirname(__DIR__) . '/public' . $relativePath;

    match ($mime) {
        'image/jpeg' => imagejpeg($target, $fullPath, 85),
        'image/png' => imagepng($target, $fullPath, 7),
        'image/webp' => imagewebp($target, $fullPath, 85),
    };

    imagedestroy($source);
    imagedestroy($target);

    return $relativePath;
}

function menu_item_image_source_label(?string $relativePath): string
{
    if (!$relativePath) {
        return 'No image';
    }

    if (str_starts_with($relativePath, '/assets/images/menu-items/seed/')) {
        return 'Seed asset';
    }

    if (is_uploaded_menu_item_image($relativePath)) {
        return 'Uploaded image';
    }

    return 'Custom path';
}

function is_uploaded_menu_item_image(?string $relativePath): bool
{
    if (!$relativePath || !str_starts_with($relativePath, '/uploads/menu-items/')) {
        return false;
    }

    $suffix = substr($relativePath, strlen('/uploads/menu-items/'));
    return $suffix !== '' && !str_contains($suffix, '/');
}

function delete_menu_item_upload(?string $relativePath): void
{
    if (!is_uploaded_menu_item_image($relativePath)) {
        return;
    }

    $fullPath = dirname(__DIR__) . '/public' . $relativePath;
    if (is_file($fullPath)) {
        unlink($fullPath);
    }
}
