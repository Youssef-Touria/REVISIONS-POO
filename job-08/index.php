<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/job-06/db.php';
require_once dirname(__DIR__) . '/job-06/Product.php';
require_once dirname(__DIR__) . '/job-06/Category.php';

Product::setPdo($pdo);
Category::setPdo($pdo);

echo "<h2>Job 08 - findAll()</h2>";
echo "<pre>";

// ========================================
// Récupérer tous les produits
// ========================================
echo "=== Récupération de tous les produits ===\n\n";

$product = new Product();
$allProducts = $product->findAll();

echo "Nombre total de produits : " . count($allProducts) . "\n\n";

if (empty($allProducts)) {
    echo "⚠️  Aucun produit trouvé dans la base de données.\n";
} else {
    echo "✅ Produits récupérés avec succès !\n\n";
    
    // ========================================
    // Afficher tous les produits
    // ========================================
    echo "=== Liste complète des produits ===\n\n";
    
    foreach ($allProducts as $index => $prod) {
        echo "--- Produit #" . ($index + 1) . " ---\n";
        echo "ID: " . $prod->getId() . "\n";
        echo "Nom: " . $prod->getName() . "\n";
        echo "Prix: " . $prod->getPrice() . " cts (" . ($prod->getPrice() / 100) . " €)\n";
        echo "Stock: " . $prod->getQuantity() . " unités\n";
        echo "Description: " . substr($prod->getDescription(), 0, 50) . "...\n";
        echo "Category ID: " . $prod->getCategoryId() . "\n";
        
        $photosCount = count($prod->getPhotos());
        echo "Photos: " . $photosCount . " photo(s)\n";
        
        echo "\n";
    }
    
    // ========================================
    // Statistiques
    // ========================================
    echo "=== Statistiques ===\n\n";
    
    $totalStock = 0;
    $totalValue = 0;
    
    foreach ($allProducts as $prod) {
        $totalStock += $prod->getQuantity();
        $totalValue += ($prod->getPrice() * $prod->getQuantity());
    }
    
    echo "Stock total : " . $totalStock . " unités\n";
    echo "Valeur totale : " . $totalValue . " cts (" . ($totalValue / 100) . " €)\n";
    echo "Prix moyen : " . ($totalValue / count($allProducts)) . " cts\n\n";
    
    // ========================================
    // Produits par catégorie
    // ========================================
    echo "=== Produits par catégorie ===\n\n";
    
    $byCategory = [];
    foreach ($allProducts as $prod) {
        $catId = $prod->getCategoryId();
        if (!isset($byCategory[$catId])) {
            $byCategory[$catId] = [];
        }
        $byCategory[$catId][] = $prod;
    }
    
    foreach ($byCategory as $catId => $products) {
        echo "Catégorie #{$catId} : " . count($products) . " produit(s)\n";
        foreach ($products as $p) {
            echo "  - " . $p->getName() . "\n";
        }
        echo "\n";
    }
}

