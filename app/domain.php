<?php

declare(strict_types=1);

function current_menu(): ?array
{
    return db_fetch("SELECT * FROM menus WHERE is_current = 1 AND status = 'released' ORDER BY id DESC LIMIT 1");
}

function menu_entries_for_menu(int $menuId, bool $activeOnly = false): array
{
    $activeSql = $activeOnly ? 'AND mi.active = 1' : '';
    return db_fetch_all(
        "SELECT me.*, mi.name, mi.description, mi.image_path, mi.active
         FROM menu_entries me
         JOIN menu_items mi ON mi.id = me.menu_item_id
         WHERE me.menu_id = ? {$activeSql}
         ORDER BY me.sort_order, mi.name",
        [$menuId]
    );
}

function cart_details(): array
{
    $menuId = cart_menu_id();
    $items = cart_items();
    if (!$menuId || empty($items)) {
        return ['menu' => null, 'rows' => [], 'subtotal_cents' => 0];
    }

    $menu = db_fetch('SELECT * FROM menus WHERE id = ?', [$menuId]);
    if (!$menu || (int) $menu['is_current'] !== 1 || $menu['status'] !== 'released') {
        clear_cart();
        return ['menu' => null, 'rows' => [], 'subtotal_cents' => 0];
    }

    $rows = [];
    $subtotal = 0;
    foreach ($items as $entryId => $quantity) {
        $entry = db_fetch(
            'SELECT me.*, mi.name, mi.description, mi.image_path, mi.active FROM menu_entries me JOIN menu_items mi ON mi.id = me.menu_item_id WHERE me.id = ? AND me.menu_id = ?',
            [(int) $entryId, $menuId]
        );
        if (!$entry) {
            continue;
        }
        $quantity = max(1, min(99, (int) $quantity));
        $lineTotal = (int) $entry['price_cents'] * $quantity;
        $subtotal += $lineTotal;
        $rows[] = [
            'entry' => $entry,
            'quantity' => $quantity,
            'line_total_cents' => $lineTotal,
        ];
    }

    if (!$rows) {
        clear_cart();
    }

    return ['menu' => $menu, 'rows' => $rows, 'subtotal_cents' => $subtotal];
}

function create_order_from_cart(array $data): int
{
    $cart = cart_details();
    if (!$cart['menu'] || !$cart['rows']) {
        throw new RuntimeException('Your cart is empty.');
    }
    if (cutoff_passed($cart['menu'])) {
        throw new RuntimeException('Ordering is closed for this menu.');
    }

    $fulfillment = $data['fulfillment_type'];
    $deliveryFee = $fulfillment === 'delivery' ? (int) $cart['menu']['delivery_fee_cents'] : 0;
    $subtotal = (int) $cart['subtotal_cents'];
    $total = $subtotal + $deliveryFee;
    $now = now_text();

    $pdo = db();
    $pdo->beginTransaction();
    try {
        db_execute(
            'INSERT INTO orders (menu_id, user_id, customer_name, customer_email, customer_phone, fulfillment_type, delivery_address, customer_notes, status, subtotal_cents, delivery_fee_cents, total_cents, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                (int) $cart['menu']['id'],
                $data['user_id'],
                $data['customer_name'],
                $data['customer_email'],
                $data['customer_phone'],
                $fulfillment,
                $fulfillment === 'delivery' ? $data['delivery_address'] : null,
                $data['customer_notes'],
                'confirmed',
                $subtotal,
                $deliveryFee,
                $total,
                $now,
                $now,
            ]
        );
        $orderId = (int) $pdo->lastInsertId();

        foreach ($cart['rows'] as $row) {
            $entry = $row['entry'];
            db_execute(
                'INSERT INTO order_items (order_id, menu_entry_id, item_name_snapshot, unit_price_cents, quantity, line_total_cents, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $orderId,
                    (int) $entry['id'],
                    $entry['name'],
                    (int) $entry['price_cents'],
                    (int) $row['quantity'],
                    (int) $row['line_total_cents'],
                    $now,
                    $now,
                ]
            );
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }

    return $orderId;
}

function order_with_items(int $orderId): ?array
{
    $order = db_fetch('SELECT o.*, m.title AS menu_title FROM orders o JOIN menus m ON m.id = o.menu_id WHERE o.id = ?', [$orderId]);
    if (!$order) {
        return null;
    }
    $order['items'] = db_fetch_all('SELECT * FROM order_items WHERE order_id = ? ORDER BY id', [$orderId]);
    return $order;
}

function recalculate_order_totals(int $orderId): void
{
    $order = db_fetch('SELECT o.*, m.delivery_fee_cents AS menu_delivery_fee_cents FROM orders o JOIN menus m ON m.id = o.menu_id WHERE o.id = ?', [$orderId]);
    if (!$order) {
        return;
    }

    $items = db_fetch_all('SELECT * FROM order_items WHERE order_id = ?', [$orderId]);
    $subtotal = 0;
    foreach ($items as $item) {
        $lineTotal = (int) $item['unit_price_cents'] * (int) $item['quantity'];
        $subtotal += $lineTotal;
        db_execute('UPDATE order_items SET line_total_cents = ?, updated_at = ? WHERE id = ?', [$lineTotal, now_text(), (int) $item['id']]);
    }
    $deliveryFee = $order['fulfillment_type'] === 'delivery' ? (int) $order['menu_delivery_fee_cents'] : 0;
    db_execute('UPDATE orders SET subtotal_cents = ?, delivery_fee_cents = ?, total_cents = ?, updated_at = ? WHERE id = ?', [
        $subtotal,
        $deliveryFee,
        $subtotal + $deliveryFee,
        now_text(),
        $orderId,
    ]);
}

function prep_summary(int $menuId): array
{
    return db_fetch_all(
        "SELECT oi.item_name_snapshot AS item_name, SUM(oi.quantity) AS total_quantity
         FROM order_items oi
         JOIN orders o ON o.id = oi.order_id
         WHERE o.menu_id = ? AND o.status != 'cancelled'
         GROUP BY oi.item_name_snapshot
         ORDER BY oi.item_name_snapshot",
        [$menuId]
    );
}
