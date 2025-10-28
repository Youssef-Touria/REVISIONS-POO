<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Utiliser les fichiers du job-06
require_once dirname(__DIR__) . '/job-06/db.php';
require_once dirname(__DIR__) . '/job-06/Product.php';
require_once dirname(__DIR__) . '/job-06/Category.php';

Product::setPdo($pdo);

echo "<h2>Job 07 - findOneById()</h2>";
echo "<pre>";

// Test 1 : Produit existant
echo "=== Test 1 : Recherche du produit #7 ===\n\n";

$product = new Product();
$result = $product->findOneById(7);

if ($result === false) {
    echo "❌ Produit #7 non trouvé\n\n";
} else {
    echo "✅ Produit trouvé !\n\n";
    echo "ID: " . $product->getId() . "\n";
    echo "Nom: " . $product->getName() . "\n";
    echo "Prix: " . $product->getPrice() . " cts\n";
    echo "Quantité: " . $product->getQuantity() . "\n\n";
}

// Test 2 : Produit inexistant
echo "=== Test 2 : Recherche produit inexistant (ID 9999) ===\n\n";

$product2 = new Product();
$result2 = $product2->findOneById(9999);

if ($result2 === false) {
    echo "✅ Correct : false retourné pour ID inexistant\n\n";
} else {
    echo "❌ Erreur\n\n";
}

// Test 3 : Plusieurs produits
echo "=== Test 3 : Recherche de plusieurs produits ===\n\n";

$idsToTest = [1, 2, 7, 999];

foreach ($idsToTest as $testId) {
    $testProduct = new Product();
    $found = $testProduct->findOneById($testId);
    
    if ($found !== false) {
        echo "✅ Produit #{$testId} : " . $testProduct->getName() . "\n";
    } else {
        echo "❌ Produit #{$testId} : Non trouvé\n";
    }
}



