<?php

declare(strict_types=1);

function maps_directions_url(string $address): string
{
    return 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($address);
}

function google_maps_api_key(): string
{
    $key = (string) config('GOOGLE_MAPS_API_KEY', '');
    return str_starts_with($key, 'TODO_') ? '' : $key;
}
