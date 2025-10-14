<?php
declare(strict_types=1);

// Inclusion des fichiers nécessaires depuis job-03
require __DIR__ . '/../job-03/db.php';
require __DIR__ . '/../job-03/Product.php';

echo "<h1>Job 04 - Récupération et hydratation du produit ID 7</h1>";

// Requête pour récupérer le produit avec l'id 7
$sql = "SELECT p.*, c.name AS category_name
        FROM product p
        JOIN category c ON c.id = p.category_id
        WHERE p.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => 7]);

// Récupération sous forme de tableau associatif
$productData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($productData) {
    echo "<h2>✓ Produit trouvé dans la base de données</h2>";
    echo "<h3>Données brutes (tableau associatif) :</h3>";
    echo "<pre>";
    print_r($productData);
    echo "</pre>";
    
    // Récupération des photos du produit
    $sqlPhotos = "SELECT url FROM product_photo WHERE product_id = :id ORDER BY id";
    $stmtPhotos = $pdo->prepare($sqlPhotos);
    $stmtPhotos->execute(['id' => 7]);
    $photos = $stmtPhotos->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<hr>";
    echo "<h2>✓ Hydratation de l'instance Product</h2>";
    
    // Création d'une nouvelle instance de Product
    $product = new Product();
    
    // Hydratation de l'instance avec les données de la base de données
    $product->setId($productData['id']);
    $product->setName($productData['name']);
    $product->setPhotos($photos);
    $product->setPrice($productData['price_cents']);
    $product->setDescription($productData['description'] ?? '');
    $product->setQuantity($productData['quantity']);
    $product->setCreatedAt(new DateTime($productData['created_at']));
    $product->setUpdatedAt(new DateTime($productData['updated_at']));
    $product->setCategory_id($productData['category_id']);
    
    // Affichage des informations du produit hydraté
    echo "<h3>Instance Product hydratée :</h3>";
    echo "<pre>";
    echo "ID:           " . $product->getId() . "\n";
    echo "Nom:          " . $product->getName() . "\n";
    echo "Prix:         " . $product->getPrice() . " centimes\n";
    echo "Description:  " . $product->getDescription() . "\n";
    echo "Quantité:     " . $product->getQuantity() . "\n";
    echo "Catégorie ID: " . $product->getCategory_id() . "\n";
    echo "Catégorie:    " . $productData['category_name'] . "\n";
    echo "Photos:       " . implode(', ', $product->getPhotos()) . "\n";
    echo "Créé le:      " . $product->getCreatedAt()->format('Y-m-d H:i:s') . "\n";
    echo "Mis à jour:   " . $product->getUpdatedAt()->format('Y-m-d H:i:s') . "\n";
    echo "</pre>";
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724;'>";
    echo "<strong>🎉 Félicitations !</strong><br>";
    echo "Vous venez de créer votre première instance de classe avec des données de base de données !";
    echo "</div>";
    
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
    echo "<strong>⚠ Erreur :</strong> Aucun produit trouvé avec l'ID 7.";
    echo "</div>";
    
    // Afficher les produits disponibles
    $sqlAll = "SELECT id, name FROM product ORDER BY id";
    $allProducts = $pdo->query($sqlAll)->fetchAll();
    echo "<h3>Produits disponibles :</h3>";
    echo "<ul>";
    foreach ($allProducts as $p) {
        echo "<li>ID {$p['id']}: {$p['name']}</li>";
    }
    echo "</ul>";
}
?>