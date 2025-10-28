<?php
declare(strict_types=1);

// Utiliser les fichiers du job-03 (ou job-04)
require_once dirname(__DIR__) . '/job-03/db.php';
require_once dirname(__DIR__) . '/job-03/Product.php';
require_once dirname(__DIR__) . '/job-03/Category.php';

// Injecter PDO pour permettre à getCategory() de fonctionner
Product::setPdo($pdo);

echo "<h2>Job 05 - Récupérer la catégorie d'un produit</h2>";
echo "<pre>";

// ========================================
// 1. Récupérer le produit #7
// ========================================
echo "=== Étape 1 : Récupération du produit #7 ===\n\n";

$sql = "SELECT id, name, price, quantity, category_id, created_at, updated_at, description
        FROM product
        WHERE id = :id
        LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => 7]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    exit("❌ Aucun produit trouvé avec l'ID 7.\n");
}

// Récupérer les photos
$photos = [];
$stmtp = $pdo->prepare("SELECT url FROM product_photo WHERE product_id = :pid ORDER BY id");
$stmtp->execute([':pid' => (int)$row['id']]);
while ($r = $stmtp->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($r['url'])) {
        $photos[] = (string)$r['url'];
    }
}

// Hydrater le produit
$product = new Product(
    id:         (int)$row['id'],
    name:       (string)$row['name'],
    photos:     $photos,
    price:      (int)$row['price'],
    description: (string)($row['description'] ?? ''),
    quantity:   (int)$row['quantity'],
    createdAt:  !empty($row['created_at']) ? new DateTime($row['created_at']) : null,
    updatedAt:  !empty($row['updated_at']) ? new DateTime($row['updated_at']) : null,
    categoryId: (int)$row['category_id']
);

echo "✅ Produit récupéré :\n";
echo "   ID: " . $product->getId() . "\n";
echo "   Nom: " . $product->getName() . "\n";
echo "   Category ID: " . $product->getCategoryId() . "\n\n";

// ========================================
// 2. Récupérer la catégorie avec getCategory()
// ========================================
echo "=== Étape 2 : Appel de getCategory() ===\n\n";

$category = $product->getCategory();

if ($category === null) {
    echo "❌ Aucune catégorie trouvée pour ce produit.\n";
} else {
    echo "✅ Catégorie récupérée avec succès !\n\n";
    
    // ========================================
    // 3. Afficher les informations complètes
    // ========================================
    echo "=== Informations complètes de la catégorie ===\n\n";
    echo "ID: " . $category->getId() . "\n";
    echo "Nom: " . $category->getName() . "\n";
    echo "Description: " . $category->getDescription() . "\n";
    
    if ($category->getCreatedAt()) {
        echo "Créé le: " . $category->getCreatedAt()->format('d/m/Y H:i:s') . "\n";
    }
    if ($category->getUpdatedAt()) {
        echo "Modifié le: " . $category->getUpdatedAt()->format('d/m/Y H:i:s') . "\n";
    }
    
    echo "\n";
    
    // ========================================
    // 4. Résumé complet Produit + Catégorie
    // ========================================
    echo "=== Résumé : Produit #7 et sa catégorie ===\n\n";
    echo "Produit: " . $product->getName() . "\n";
    echo "Prix: " . $product->getPrice() . " cts (" . ($product->getPrice() / 100) . " €)\n";
    echo "Stock: " . $product->getQuantity() . " unités\n";
    echo "\n";
    echo "Catégorie: " . $category->getName() . "\n";
    echo "Description de la catégorie: " . $category->getDescription() . "\n";
}

echo "\n====================================================\n";
echo "🎉 Job 05 complété !\n";
echo "Vous avez récupéré l'entièreté des informations\n";
echo "de la catégorie associée au produit #7 !\n";
echo "====================================================\n";

echo "</pre>";