<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Maintenant on utilise les fichiers LOCAUX (dans job-06)
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/Category.php';

Product::setPdo($pdo);
Category::setPdo($pdo);

echo "<h2>Job 06 - getProducts()</h2>";
echo "<pre>";

// Récupérer la catégorie #1
$stmt = $pdo->prepare("SELECT * FROM category WHERE id = 1");
$stmt->execute();
$catRow = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$catRow) {
    die("Catégorie #1 introuvable");
}

$category = new Category(
    id: (int)$catRow['id'],
    name: (string)$catRow['name'],
    description: (string)$catRow['description'],
    createdAt: !empty($catRow['created_at']) ? new DateTime($catRow['created_at']) : null,
    updatedAt: !empty($catRow['updated_at']) ? new DateTime($catRow['updated_at']) : null
);

echo "Catégorie: " . $category->getName() . "\n\n";

$products = $category->getProducts();

echo "Nombre de produits: " . count($products) . "\n\n";

foreach ($products as $product) {
    echo "- " . $product->getName() . " (" . $product->getPrice() . " cts)\n";
}

echo "</pre>";
