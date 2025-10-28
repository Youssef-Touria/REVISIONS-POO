<?php
declare(strict_types=1);

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h1>DIAGNOSTIC COMPLET</h1>";
echo "<pre>";

// ===== TEST 1 : Connexion à la base de données =====
echo "===== TEST 1 : Connexion PDO =====\n";
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=draft-shop;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    echo "✅ Connexion réussie à draft-shop\n\n";
} catch (PDOException $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit;
}

// ===== TEST 2 : Structure des tables =====
echo "===== TEST 2 : Structure de la table 'category' =====\n";
try {
    $stmt = $pdo->query("DESCRIBE category");
    $columns = $stmt->fetchAll();
    foreach ($columns as $col) {
        echo "- {$col['Field']} ({$col['Type']})\n";
    }
    echo "\n";
} catch (PDOException $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n\n";
}

echo "===== Structure de la table 'product' =====\n";
try {
    $stmt = $pdo->query("DESCRIBE product");
    $columns = $stmt->fetchAll();
    foreach ($columns as $col) {
        echo "- {$col['Field']} ({$col['Type']})\n";
    }
    echo "\n";
} catch (PDOException $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n\n";
}

// ===== TEST 3 : Vérifier si la table product_photo existe =====
echo "===== TEST 3 : Table 'product_photo' =====\n";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'product_photo'");
    if ($stmt->rowCount() > 0) {
        echo "✅ La table product_photo existe\n";
        $stmt = $pdo->query("DESCRIBE product_photo");
        $columns = $stmt->fetchAll();
        foreach ($columns as $col) {
            echo "- {$col['Field']} ({$col['Type']})\n";
        }
    } else {
        echo "⚠️  La table product_photo n'existe pas\n";
    }
    echo "\n";
} catch (PDOException $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n\n";
}

// ===== TEST 4 : Contenu des tables =====
echo "===== TEST 4 : Contenu de 'category' =====\n";
try {
    $stmt = $pdo->query("SELECT * FROM category LIMIT 5");
    $categories = $stmt->fetchAll();
    if (empty($categories)) {
        echo "⚠️  Aucune catégorie trouvée\n";
    } else {
        echo "✅ " . count($categories) . " catégories trouvées:\n";
        foreach ($categories as $cat) {
            print_r($cat);
        }
    }
    echo "\n";
} catch (PDOException $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n\n";
}

echo "===== Contenu de 'product' =====\n";
try {
    $stmt = $pdo->query("SELECT * FROM product LIMIT 5");
    $products = $stmt->fetchAll();
    if (empty($products)) {
        echo "⚠️  Aucun produit trouvé\n";
    } else {
        echo "✅ " . count($products) . " produits trouvés:\n";
        foreach ($products as $prod) {
            print_r($prod);
        }
    }
    echo "\n";
} catch (PDOException $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n\n";
}

// ===== TEST 5 : Vérifier si le produit #7 existe =====
echo "===== TEST 5 : Recherche du produit #7 =====\n";
try {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = 7");
    $stmt->execute();
    $product7 = $stmt->fetch();
    
    if ($product7) {
        echo "✅ Produit #7 trouvé:\n";
        print_r($product7);
    } else {
        echo "⚠️  Aucun produit avec l'ID 7\n";
    }
    echo "\n";
} catch (PDOException $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n\n";
}

// ===== TEST 6 : Test des classes =====
echo "===== TEST 6 : Chargement des classes =====\n";
if (file_exists(__DIR__ . '/Product.php')) {
    echo "✅ Product.php existe\n";
    require_once __DIR__ . '/Product.php';
} else {
    echo "❌ Product.php introuvable\n";
}

if (file_exists(__DIR__ . '/Category.php')) {
    echo "✅ Category.php existe\n";
    require_once __DIR__ . '/Category.php';
} else {
    echo "❌ Category.php introuvable\n";
}

echo "\n";

// ===== TEST 7 : Test simple d'instanciation =====
echo "===== TEST 7 : Test d'instanciation simple =====\n";
try {
    $testProduct = new Product(
        id: 999,
        name: "Produit Test",
        photos: ["test.jpg"],
        price: 1000,
        description: "Test",
        quantity: 5,
        categoryId: 1
    );
    echo "✅ Product instancié avec succès\n";
    echo "Nom: " . $testProduct->getName() . "\n";
    echo "Prix: " . $testProduct->getPrice() . "\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
}

echo "</pre>";