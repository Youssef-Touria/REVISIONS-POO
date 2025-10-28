<?php

require_once 'Clothing.php';

// Initialisation du PDO pour permettre les requêtes SQL
try {
    $pdo = new PDO('mysql:host=localhost;dbname=draft_shop', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    Product::setPdo($pdo);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Classe Clothing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .product-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #666;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .info-group {
            margin: 10px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h1>🛍️ Test de la classe Clothing</h1>
    
    <?php
    // Création d'un vêtement
    $tshirt = new Clothing(
        1,                              // id
        "T-shirt Premium",              // name
        ["tshirt1.jpg", "tshirt2.jpg"], // photos
        2999,                           // price (en centimes)
        "T-shirt en coton bio de haute qualité", // description
        50,                             // quantity
        new DateTime('2024-01-15'),     // createdAt
        new DateTime('2024-10-20'),     // updatedAt
        1,                              // category (ID de la catégorie)
        "M",                            // size
        "Bleu",                         // color
        "T-shirt",                      // type
        500                             // material_fee (en centimes)
    );
    ?>
    
    <div class="product-card">
        <h2><?php echo $tshirt->getName(); ?></h2>
        
        <div class="info-group">
            <span class="label">Prix:</span>
            <span class="value"><?php echo $tshirt->getPrice() / 100; ?> €</span>
        </div>
        
        <div class="info-group">
            <span class="label">Taille:</span>
            <span class="value"><?php echo $tshirt->getSize(); ?></span>
        </div>
        
        <div class="info-group">
            <span class="label">Couleur:</span>
            <span class="value"><?php echo $tshirt->getColor(); ?></span>
        </div>
        
        <div class="info-group">
            <span class="label">Type:</span>
            <span class="value"><?php echo $tshirt->getType(); ?></span>
        </div>
        
        <div class="info-group">
            <span class="label">Frais de matériau:</span>
            <span class="value"><?php echo $tshirt->getMaterialFee() / 100; ?> €</span>
        </div>
        
        <div class="info-group">
            <span class="label">Description:</span>
            <span class="value"><?php echo $tshirt->getDescription(); ?></span>
        </div>
        
        <div class="info-group">
            <span class="label">Quantité en stock:</span>
            <span class="value"><?php echo $tshirt->getQuantity(); ?></span>
        </div>
        
        <div class="info-group">
            <span class="label">Catégorie:</span>
            <span class="value">
                <?php 
                try {
                    $category = $tshirt->getCategory();
                    echo $category ? $category->getName() : "Non définie";
                } catch (Exception $e) {
                    echo "Erreur lors de la récupération de la catégorie";
                }
                ?>
            </span>
        </div>
        
        <div class="info-group">
            <span class="label">Créé le:</span>
            <span class="value"><?php echo $tshirt->getCreatedAt()->format('d/m/Y'); ?></span>
        </div>
        
        <div class="info-group">
            <span class="label">Mis à jour le:</span>
            <span class="value"><?php echo $tshirt->getUpdatedAt()->format('d/m/Y'); ?></span>
        </div>
    </div>
    
    <?php
    // Test de modification
    echo "<h2>Test des setters</h2>";
    $tshirt->setColor("Rouge");
    $tshirt->setSize("L");
    $tshirt->setPrice(3499);
    ?>
    
    <div class="product-card">
        <h2>Après modification</h2>
        
        <div class="info-group">
            <span class="label">Nouvelle couleur:</span>
            <span class="value"><?php echo $tshirt->getColor(); ?></span>
        </div>
        
        <div class="info-group">
            <span class="label">Nouvelle taille:</span>
            <span class="value"><?php echo $tshirt->getSize(); ?></span>
        </div>
        
        <div class="info-group">
            <span class="label">Nouveau prix:</span>
            <span class="value"><?php echo $tshirt->getPrice() / 100; ?> €</span>
        </div>
    </div>
    
</body>
</html>