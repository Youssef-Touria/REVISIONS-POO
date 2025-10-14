<?php
declare(strict_types=1);

// DB est dans job-03
require dirname(__DIR__) . '/job-03/db.php';

// Les classes aussi dans job-03
require_once dirname(__DIR__) . '/job-03/Product.php';
require_once dirname(__DIR__) . '/job-03/Category.php';

// Si tu utilises getCategory()
Product::setPdo($pdo);


// Si tu as implémenté Product::setPdo() pour getCategory(), injecte la connexion :
Product::setPdo($pdo);

// 2) Requête : produit ID=7 sous forme de tableau associatif
$sql = "SELECT id, name, price_cents, quantity, category_id, created_at, updated_at
        FROM product
        WHERE id = :id
        LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => 7]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    exit("<p>Aucun produit trouvé avec l’ID 7.</p>");
}

// (optionnel) Récupérer aussi les photos
$photos = [];
$stmtp = $pdo->prepare("SELECT url FROM product_photo WHERE product_id = :pid ORDER BY id");
$stmtp->execute([':pid' => (int)$row['id']]);
while ($r = $stmtp->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($r['url'])) $photos[] = (string)$r['url'];
}

// 3) Hydration : créer une instance Product à partir du tableau
$product = new Product(
    id:         (int)$row['id'],
    name:       (string)$row['name'],
    photos:     $photos,
    price:      (int)$row['price_cents'],     // map price_cents -> price (int)
    description:'',
    quantity:   (int)$row['quantity'],
    createdAt:  !empty($row['created_at']) ? new DateTime($row['created_at']) : null,
    updatedAt:  !empty($row['updated_at']) ? new DateTime($row['updated_at']) : null,
    categoryId: (int)$row['category_id']
);

// 4) (facultatif) Récupérer la catégorie associée via getCategory()
$category = $product->getCategory();

// 5) Affichage de contrôle
echo "<h2>Produit #7 hydraté</h2>";
echo "<pre>";
echo "ID: {$product->getId()}\n";
echo "Nom: {$product->getName()}\n";
echo "Prix: {$product->getPrice()} cts\n";
echo "Stock: {$product->getQuantity()}\n";
echo "Photos: " . implode(', ', $product->getPhotos()) . "\n";
if ($category) {
    echo "Catégorie: [#{$category->getId()}] {$category->getName()}\n";
}
echo "</pre>";

