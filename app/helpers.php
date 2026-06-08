<?php

declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    return $GLOBALS['app_config'][$key] ?? $default;
}

function site_name(): string
{
    $name = (string) config('SITE_NAME', 'Akisitonoma');
    return $name === '' || str_starts_with($name, 'TODO_') ? 'Akisitonoma' : $name;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function posted(string $key, string $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

function queried(string $key, string $default = ''): string
{
    return trim((string) ($_GET[$key] ?? $default));
}

function int_param(string $key): int
{
    return max(0, (int) ($_GET[$key] ?? $_POST[$key] ?? 0));
}

function now_text(): string
{
    return date('Y-m-d H:i:s');
}

function parse_app_datetime(string $value): ?DateTimeImmutable
{
    $value = trim($value);
    if ($value === '') {
        return null;
    }

    try {
        return new DateTimeImmutable($value);
    } catch (Throwable) {
        return null;
    }
}

function format_date(string $value): string
{
    $date = parse_app_datetime($value);
    return $date ? $date->format('F j, Y') : $value;
}

function format_datetime(string $value): string
{
    $date = parse_app_datetime($value);
    return $date ? $date->format('F j, Y \a\t g:i A') : $value;
}

function money(int $cents): string
{
    return '$' . number_format($cents / 100, 2);
}

function parse_money_to_cents(string $value): int
{
    $clean = preg_replace('/[^0-9.]/', '', $value) ?: '0';
    return (int) round(((float) $clean) * 100);
}

function cents_to_input(int $cents): string
{
    return number_format($cents / 100, 2, '.', '');
}

function app_url(string $path): string
{
    return rtrim((string) config('BASE_URL', ''), '/') . '/' . ltrim($path, '/');
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require dirname(__DIR__) . '/app/views/layout-top.php';
    require dirname(__DIR__) . '/app/views/' . $view . '.php';
    require dirname(__DIR__) . '/app/views/layout-bottom.php';
}

function selected(string $actual, string $expected): string
{
    return $actual === $expected ? 'selected' : '';
}

function checked(bool $value): string
{
    return $value ? 'checked' : '';
}

function cart_items(): array
{
    return $_SESSION['cart']['items'] ?? [];
}

function cart_menu_id(): ?int
{
    return isset($_SESSION['cart']['menu_id']) ? (int) $_SESSION['cart']['menu_id'] : null;
}

function cart_count(): int
{
    return array_sum(array_map('intval', cart_items()));
}

function clear_cart(): void
{
    unset($_SESSION['cart']);
}

function set_cart_item(int $menuId, int $entryId, int $quantity): void
{
    if (cart_menu_id() !== $menuId) {
        $_SESSION['cart'] = ['menu_id' => $menuId, 'items' => []];
    }

    if ($quantity <= 0) {
        unset($_SESSION['cart']['items'][$entryId]);
    } else {
        $_SESSION['cart']['items'][$entryId] = min($quantity, 99);
    }

    if (empty($_SESSION['cart']['items'])) {
        clear_cart();
    }
}

function cutoff_passed(array $menu): bool
{
    if (empty($menu['cutoff_at'])) {
        return false;
    }
    return strtotime((string) $menu['cutoff_at']) <= time();
}

function status_label(string $status): string
{
    return ucwords(str_replace('_', ' ', $status));
}
