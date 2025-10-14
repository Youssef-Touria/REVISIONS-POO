<?php
declare(strict_types=1);

// ---- Includes & DB ----
require __DIR__ . '/db.php';            // doit créer $pdo (PDO connecté)
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/Category.php';

// Injecte la connexion PDO une seule fois pour Product::getCategory()
Product::setPdo($pdo);

// -----------------------------------------------------------------------------
// 1) LISTE DES PRODUITS (avec catégorie + photos agrégées)
// -----------------------------------------------------------------------------
$sql = "
  SELECT p.id, p.name, p.price_cents, p.quantity,
         c.name AS category, ph.url
  FROM product p
  JOIN category c ON c.id = p.category_id
  LEFT JOIN product_photo ph ON ph.product_id = p.id
  ORDER BY p.id, ph.id
";
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Regrouper par produit
$by = [];
foreach ($rows as $r) {
    $id = (int)$r['id'];
    if (!isset($by[$id])) {
        $by[$id] = [
            'name'        => (string)$r['name'],
            'price_cents' => (int)$r['price_cents'],
            'quantity'    => (int)$r['quantity'],
            'category'    => (string)$r['category'],
            'photos'      => [],
        ];
    }
    if (!empty($r['url'])) {
        $by[$id]['photos'][] = (string)$r['url'];
    }
}

// Affichage liste
echo "<h2>Produits disponibles</h2>";
echo "<pre>";
foreach ($by as $id => $p) {
    echo "Produit #$id — {$p['name']} ({$p['category']})\n";
    echo "Prix: {$p['price_cents']} cts | Stock: {$p['quantity']}\n";
    echo "Photos: " . implode(', ', $p['photos']) . "\n\n";
}
echo "</pre>"; // <-- bien le point-virgule ici

// -----------------------------------------------------------------------------
// 2) PRODUIT #7 — hydrater Product + récupérer sa Category via getCategory()
// -----------------------------------------------------------------------------
echo "<hr><h3>Produit #7 + sa catégorie</h3>";

// a) Récupérer la ligne du produit #7
$sql7 = "
  SELECT id, name, price_cents, quantity, category_id, created_at, updated_at
  FROM product
  WHERE id = :id
  LIMIT 1
";
$stmt = $pdo->prepare($sql7);
$stmt->execute([':id' => 7]);
$row7 = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row7) {
    echo "<p>Aucun produit trouvé avec l’ID 7.</p>";
    exit;
}

// b) Récupérer ses photos
$photos7 = [];
$stmtp = $pdo->prepare("SELECT url FROM product_photo WHERE product_id = :pid ORDER BY id");
$stmtp->execute([':pid' => (int)$row7['id']]);
while ($r = $stmtp->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($r['url'])) $photos7[] = (string)$r['url'];
}

// c) Hydrater l'objet Product
$product7 = new Product(
    id:         (int)$row7['id'],
    name:       (string)$row7['name'],
    photos:     $photos7,
    price:      (int)$row7['price_cents'], // ta classe a 'price' (int)
    description:'',
    quantity:   (int)$row7['quantity'],
    createdAt:  !empty($row7['created_at']) ? new DateTime($row7['created_at']) : null,
    updatedAt:  !empty($row7['updated_at']) ? new DateTime($row7['updated_at']) : null,
    categoryId: (int)$row7['category_id']
);

// d) Catégorie via getCategory() (ne prend aucun paramètre)
$cat7 = $product7->getCategory();

// e) Affichage détaillé
echo "<pre>";
echo "Produit #{$product7->getId()} — {$product7->getName()}\n";
echo "Prix: {$product7->getPrice()} cts | Stock: {$product7->getQuantity()}\n";
echo "Photos: " . implode(', ', $product7->getPhotos()) . "\n";

if ($cat7) {
    echo "Catégorie: [#{$cat7->getId()}] {$cat7->getName()}\n";
    $desc = trim($cat7->getDescription());
    if ($desc !== '') echo "Description: {$desc}\n";
} else {
    echo "Catégorie: non trouvée\n";
}
echo "</pre>";
