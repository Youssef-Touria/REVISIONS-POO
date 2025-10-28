<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once dirname(__DIR__) . '/job-06/db.php';
require_once dirname(__DIR__) . '/job-06/Product.php';
require_once dirname(__DIR__) . '/job-06/Category.php';

Product::setPdo($pdo);

echo "<h2>Job 09 - create()</h2>";
echo "<pre>";

echo "=== Test 1 : Création d'un nouveau produit ===\n\n";

try {
    // Créer une nouvelle instance
    $newProduct = new Product(
        id: 0,
        name: 'Nouveau T-shirt Test',
        photos: ['test1.jpg', 'test2.jpg'],
        price: 3999,
        description: 'Un super t-shirt de test',
        quantity: 100,
        categoryId: 1
    );
    
    echo "Instance créée\n";
    echo "Avant create() - ID: " . $newProduct->getId() . "\n\n";
    
    // Appeler create()
    $result = $newProduct->create();
    
    echo "Après appel de create()\n";
    
    if ($result === false) {
        echo "❌ Échec de la création\n";
    } else {
        echo "✅ Produit créé avec succès !\n\n";
        echo "ID généré: " . $newProduct->getId() . "\n";
        echo "Nom: " . $newProduct->getName() . "\n";
        echo "Prix: " . $newProduct->getPrice() . " cts\n";
        echo "Quantité: " . $newProduct->getQuantity() . "\n";
        echo "Category ID: " . $newProduct->getCategoryId() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}

echo "\n</pre>";