<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once dirname(__DIR__) . '/job-06/db.php';
require_once dirname(__DIR__) . '/job-06/Product.php';
require_once dirname(__DIR__) . '/job-06/Category.php';

Product::setPdo($pdo);

echo "<h2>Job 10 - update()</h2>";
echo "<pre>";

try {
    // ========================================
    // Test 1 : Créer un produit d'abord
    // ========================================
    echo "=== Étape 1 : Création d'un produit test ===\n\n";
    
    $product = new Product(
        id: 0,
        name: 'T-shirt Original',
        photos: ['photo1.jpg'],
        price: 1000,
        description: 'Description originale',
        quantity: 10,
        categoryId: 1
    );
    
    $product->create();
    
    echo "Produit créé :\n";
    echo "ID: " . $product->getId() . "\n";
    echo "Nom: " . $product->getName() . "\n";
    echo "Prix: " . $product->getPrice() . " cts\n";
    echo "Quantité: " . $product->getQuantity() . "\n\n";
    
    // ========================================
    // Test 2 : Modifier le produit
    // ========================================
    echo "=== Étape 2 : Modification du produit ===\n\n";
    
    $product->setName('T-shirt Modifié 232');
    $product->setQuantity(24);
    $product->setPrice(1500);
    $product->setPhotos(['nouvelle_photo1.jpg', 'nouvelle_photo2.jpg']);
    
    echo "Avant update() :\n";
    echo "Nom modifié en mémoire: " . $product->getName() . "\n";
    echo "Quantité modifiée en mémoire: " . $product->getQuantity() . "\n\n";
    
    $result = $product->update();
    
    if ($result) {
        echo "✅ Mise à jour réussie !\n\n";
    } else {
        echo "❌ Échec de la mise à jour\n\n";
    }
    
    // ========================================
    // Test 3 : Vérifier en base de données
    // ========================================
    echo "=== Étape 3 : Vérification en BDD ===\n\n";
    
    $productFromDb = new Product();
    $productFromDb->findOneById($product->getId());
    
    echo "Données depuis la BDD :\n";
    echo "ID: " . $productFromDb->getId() . "\n";
    echo "Nom: " . $productFromDb->getName() . "\n";
    echo "Prix: " . $productFromDb->getPrice() . " cts\n";
    echo "Quantité: " . $productFromDb->getQuantity() . "\n";
    echo "Photos: " . implode(', ', $productFromDb->getPhotos()) . "\n";
    
    if ($productFromDb->getUpdatedAt()) {
        echo "Modifié le: " . $productFromDb->getUpdatedAt()->format('Y-m-d H:i:s') . "\n";
    }
    
    echo "\n";
    
    // ========================================
    // Test 4 : Vérifier que les modifications sont persistées
    // ========================================
    echo "=== Étape 4 : Comparaison ===\n\n";
    
    if ($productFromDb->getName() === 'T-shirt Modifié 232' && 
        $productFromDb->getQuantity() === 24) {
        echo "✅ Les modifications sont bien enregistrées en BDD !\n";
    } else {
        echo "❌ Les modifications ne sont pas enregistrées\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

