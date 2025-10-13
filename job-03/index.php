<?php

declare(strict_types=1);
require __DIR__ . '/db.php';

$sql = "SELECT p.id, p.name, p.price_cents, p.quantity, c.name AS category, ph.url
        FROM product p
        JOIN category c ON c.id = p.category_id
        LEFT JOIN product_photo ph ON ph.product_id = p.id
        ORDER BY p.id, ph.id";
$rows = $pdo->query($sql)->fetchAll();

$by = [];
foreach ($rows as $r) {
    $id = (int)$r['id'];
    if (!isset($by[$id])) {
        $by[$id] = [
            'name' => $r['name'],
            'price_cents' => (int)$r['price_cents'],
            'quantity' => (int)$r['quantity'],
            'category' => $r['category'],
            'photos' => [],
        ];
    }
    if (!empty($r['url'])) $by[$id]['photos'][] = $r['url'];
}

echo "<pre>";
foreach ($by as $id => $p) {
    echo "Produit #$id — {$p['name']} ({$p['category']})\n";
    echo "Prix: {$p['price_cents']} cts | Stock: {$p['quantity']}\n";
    echo "Photos: " . implode(', ', $p['photos']) . "\n\n";
}
echo "</pre>";

