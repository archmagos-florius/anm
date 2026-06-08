<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("Run this script from the command line.\n");
}

if ($argc !== 2) {
    exit("Usage: php scripts/build_home_hero_image.php /path/to/source-image.png\n");
}

if (!function_exists('imagewebp')) {
    exit("PHP GD with WebP support is required.\n");
}

$sourcePath = expand_home_path($argv[1]);
if (!is_file($sourcePath) || !is_readable($sourcePath)) {
    exit("Source image does not exist or is not readable: {$sourcePath}\n");
}

$info = getimagesize($sourcePath);
if ($info === false) {
    exit("Source file is not a valid image: {$sourcePath}\n");
}

[$sourceWidth, $sourceHeight] = $info;
$mime = $info['mime'] ?? '';

$source = match ($mime) {
    'image/jpeg' => imagecreatefromjpeg($sourcePath),
    'image/png' => imagecreatefrompng($sourcePath),
    'image/webp' => imagecreatefromwebp($sourcePath),
    default => false,
};

if (!$source) {
    exit("Only JPG, PNG, and WebP source images are supported.\n");
}

$outputDir = dirname(__DIR__) . '/public/assets/images';
if (!is_dir($outputDir) && !mkdir($outputDir, 0775, true)) {
    exit("Could not create output directory: {$outputDir}\n");
}

$outputs = [
    'home-hero-bg.webp' => 1600,
    'home-hero-bg-small.webp' => 900,
];

$crop = centered_crop_box((int) $sourceWidth, (int) $sourceHeight, 16 / 9);

echo "Source: {$sourcePath}\n";
echo "Source dimensions: {$sourceWidth}x{$sourceHeight}\n";

foreach ($outputs as $filename => $targetWidth) {
    $actualWidth = min($targetWidth, (int) $crop['width']);
    $actualHeight = (int) round($actualWidth * 9 / 16);
    $target = imagecreatetruecolor($actualWidth, $actualHeight);

    imagecopyresampled(
        $target,
        $source,
        0,
        0,
        (int) $crop['x'],
        (int) $crop['y'],
        $actualWidth,
        $actualHeight,
        (int) $crop['width'],
        (int) $crop['height']
    );

    for ($i = 0; $i < 2; $i++) {
        imagefilter($target, IMG_FILTER_GAUSSIAN_BLUR);
    }

    $outputPath = $outputDir . '/' . $filename;
    if (!imagewebp($target, $outputPath, 82)) {
        exit("Could not write image: {$outputPath}\n");
    }

    $fileSize = filesize($outputPath);
    $fileSizeLabel = $fileSize === false ? 'unknown size' : format_bytes($fileSize);
    echo "Wrote public/assets/images/{$filename}: {$actualWidth}x{$actualHeight}, {$fileSizeLabel}\n";
}

/**
 * @return array{x: int, y: int, width: int, height: int}
 */
function centered_crop_box(int $width, int $height, float $targetRatio): array
{
    $sourceRatio = $width / $height;

    if ($sourceRatio > $targetRatio) {
        $cropHeight = $height;
        $cropWidth = (int) round($height * $targetRatio);
        $cropX = (int) floor(($width - $cropWidth) / 2);
        $cropY = 0;
    } else {
        $cropWidth = $width;
        $cropHeight = (int) round($width / $targetRatio);
        $cropX = 0;
        $cropY = (int) floor(($height - $cropHeight) / 2);
    }

    return [
        'x' => $cropX,
        'y' => $cropY,
        'width' => $cropWidth,
        'height' => $cropHeight,
    ];
}

function expand_home_path(string $path): string
{
    if ($path === '~') {
        return getenv('HOME') ?: $path;
    }

    if (str_starts_with($path, '~/')) {
        $home = getenv('HOME');
        if ($home !== false && $home !== '') {
            return $home . substr($path, 1);
        }
    }

    return $path;
}

function format_bytes(int $bytes): string
{
    if ($bytes >= 1024 * 1024) {
        return round($bytes / 1024 / 1024, 2) . 'MB';
    }

    if ($bytes >= 1024) {
        return round($bytes / 1024, 1) . 'KB';
    }

    return $bytes . 'B';
}
