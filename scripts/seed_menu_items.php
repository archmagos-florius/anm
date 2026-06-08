<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    exit("Run this script from the command line.\n");
}

$items = [
    [
        'name' => 'Lomo Saltado',
        'slug' => 'lomo-saltado',
        'description' => 'Peruvian beef stir-fry with onions, tomatoes, cilantro, fries, and rice.',
        'price_cents' => 1800,
    ],
    [
        'name' => 'Aji de Gallina',
        'slug' => 'aji-de-gallina',
        'description' => 'Shredded chicken in a creamy aji amarillo, walnut, and cheese sauce over rice.',
        'price_cents' => 1600,
    ],
    [
        'name' => 'Arroz con Pollo',
        'slug' => 'arroz-con-pollo',
        'description' => 'Cilantro rice with tender chicken, peas, carrots, and salsa criolla.',
        'price_cents' => 1500,
    ],
    [
        'name' => 'Ceviche Clasico',
        'slug' => 'ceviche-clasico',
        'description' => 'Citrus-marinated fish with red onion, cilantro, sweet potato, and cancha.',
        'price_cents' => 1900,
    ],
    [
        'name' => 'Seco de Res',
        'slug' => 'seco-de-res',
        'description' => 'Slow-braised beef in cilantro and chicha-style sauce with beans and rice.',
        'price_cents' => 1700,
    ],
    [
        'name' => 'Tallarines Verdes',
        'slug' => 'tallarines-verdes',
        'description' => 'Peruvian basil-spinach noodles with queso fresco and a homestyle herb sauce.',
        'price_cents' => 1500,
    ],
    [
        'name' => 'Papa a la Huancaina',
        'slug' => 'papa-a-la-huancaina',
        'description' => 'Potatoes with creamy aji amarillo cheese sauce, egg, olive, and lettuce.',
        'price_cents' => 1000,
    ],
    [
        'name' => 'Anticuchos',
        'slug' => 'anticuchos',
        'description' => 'Aji panca-marinated grilled skewers served with potatoes and a bright herb sauce.',
        'price_cents' => 1400,
    ],
    [
        'name' => 'Rocoto Relleno',
        'slug' => 'rocoto-relleno',
        'description' => 'Stuffed rocoto pepper with seasoned beef, vegetables, cheese, and potato.',
        'price_cents' => 1600,
    ],
    [
        'name' => 'Pollo a la Brasa',
        'slug' => 'pollo-a-la-brasa',
        'description' => 'Peruvian-style roasted chicken with fries, salad, and creamy green sauce.',
        'price_cents' => 1800,
    ],
    [
        'name' => 'Tacos al Pastor',
        'slug' => 'tacos-al-pastor',
        'description' => 'Adobo-marinated pork tacos with pineapple, onion, cilantro, and salsa.',
        'price_cents' => 1300,
    ],
    [
        'name' => 'Chicken Tinga Tostadas',
        'slug' => 'chicken-tinga-tostadas',
        'description' => 'Crisp tostadas topped with smoky chipotle chicken tinga, crema, and lettuce.',
        'price_cents' => 1200,
    ],
    [
        'name' => 'Cochinita Pibil',
        'slug' => 'cochinita-pibil',
        'description' => 'Yucatan-style achiote pork with citrus, pickled red onion, and warm tortillas.',
        'price_cents' => 1600,
    ],
    [
        'name' => 'Mole Poblano Chicken',
        'slug' => 'mole-poblano-chicken',
        'description' => 'Chicken in a deep chile, spice, seed, and chocolate mole with rice.',
        'price_cents' => 1800,
    ],
    [
        'name' => 'Enchiladas Verdes',
        'slug' => 'enchiladas-verdes',
        'description' => 'Corn tortillas filled with chicken and covered in tomatillo salsa, crema, and cheese.',
        'price_cents' => 1400,
    ],
    [
        'name' => 'Chiles Rellenos',
        'slug' => 'chiles-rellenos',
        'description' => 'Roasted poblano peppers stuffed with cheese and served with tomato sauce.',
        'price_cents' => 1500,
    ],
    [
        'name' => 'Pozole Rojo',
        'slug' => 'pozole-rojo',
        'description' => 'Hominy and pork stew in red chile broth with cabbage, radish, lime, and oregano.',
        'price_cents' => 1600,
    ],
    [
        'name' => 'Tamales de Rajas',
        'slug' => 'tamales-de-rajas',
        'description' => 'Steamed corn masa tamales filled with poblano strips, cheese, and salsa verde.',
        'price_cents' => 1100,
    ],
    [
        'name' => 'Carnitas',
        'slug' => 'carnitas',
        'description' => 'Slow-cooked pork with crisp edges, served with tortillas, salsa, onion, and cilantro.',
        'price_cents' => 1700,
    ],
    [
        'name' => 'Esquites',
        'slug' => 'esquites',
        'description' => 'Mexican street corn cups with lime, chile, crema, cotija, and cilantro.',
        'price_cents' => 800,
    ],
];

$inserted = 0;
$skipped = 0;
$imageUpdates = 0;
$warnings = [];
$now = now_text();

foreach ($items as $item) {
    $imagePath = seed_asset_path($item['slug'], $warnings);
    $existing = db_fetch('SELECT id, image_path FROM menu_items WHERE name = ?', [$item['name']]);

    if ($existing) {
        $skipped++;
        if ($imagePath !== null && should_update_seed_image_path((string) ($existing['image_path'] ?? ''))) {
            db_execute('UPDATE menu_items SET image_path = ?, updated_at = ? WHERE id = ?', [
                $imagePath,
                $now,
                (int) $existing['id'],
            ]);
            $imageUpdates++;
        }
        continue;
    }

    db_execute('INSERT INTO menu_items (name, description, price_cents, image_path, active, created_at, updated_at) VALUES (?, ?, ?, ?, 1, ?, ?)', [
        $item['name'],
        $item['description'],
        $item['price_cents'],
        $imagePath,
        $now,
        $now,
    ]);
    $inserted++;
}

echo "Seeded menu items. Inserted: {$inserted}. Existing: {$skipped}. Image paths set: {$imageUpdates}.\n";
echo "Seed image attribution notes: public/assets/images/menu-items/seed/ATTRIBUTION.md\n";

foreach ($warnings as $warning) {
    fwrite(STDERR, "Warning: {$warning}\n");
}

/**
 * @param array<int, string> $warnings
 */
function seed_asset_path(string $slug, array &$warnings): ?string
{
    $relativePath = '/assets/images/menu-items/seed/' . $slug . '.jpg';
    $fullPath = dirname(__DIR__) . '/public' . $relativePath;

    if (is_file($fullPath)) {
        return $relativePath;
    }

    $warnings[] = "Missing seed image: {$relativePath}.";
    return null;
}

function should_update_seed_image_path(string $imagePath): bool
{
    return $imagePath === ''
        || str_starts_with($imagePath, '/uploads/menu-items/seed/');
}
