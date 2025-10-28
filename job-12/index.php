<?php
require_once '../job-06/db.php';
require_once '../job-06/Category.php';
require_once '../job-06/Product.php';
require_once '../job-11/Clothing.php';
require_once '../job-11/Electronic.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job 12 - Tests</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
        }
        
        h1 {
            text-align: center;
            color: #667eea;
            margin-bottom: 40px;
            font-size: 2.5em;
        }
        
        .test-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 5px solid #667eea;
        }
        
        .test-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
            border-left: 4px solid #ffc107;
        }
        
        .product-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .product-name {
            font-weight: bold;
            color: #667eea;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        
        .product-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        .detail-item {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.9em;
        }
        
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            margin-right: 5px;
        }
        
        .badge-clothing {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .badge-electronic {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .count {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Job 12 - Test des méthodes réécrites</h1>

        <!-- TEST 1 -->
        <div class="test-section">
            <h2>👕 Test 1 : Clothing::findOneById()</h2>
            <?php
            $clothing = Clothing::findOneById(1);
            if ($clothing) {
                echo '<div class="product-card">';
                echo '<div class="product-name">' . htmlspecialchars($clothing->getName()) . '</div>';
                echo '<div class="product-details">';
                echo '<div class="detail-item"><span class="detail-label">Taille:</span> ' . htmlspecialchars($clothing->getSize()) . '</div>';
                echo '<div class="detail-item"><span class="detail-label">Couleur:</span> ' . htmlspecialchars($clothing->getColor()) . '</div>';
                echo '<div class="detail-item"><span class="detail-label">Type:</span> ' . htmlspecialchars($clothing->getType()) . '</div>';
                echo '<div class="detail-item"><span class="detail-label">Prix:</span> ' . ($clothing->getPrice() / 100) . ' €</div>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="warning">❌ Aucun vêtement trouvé avec l\'ID 1</div>';
            }
            ?>
        </div>

        <!-- TEST 2 -->
        <div class="test-section">
            <h2>👕 Test 2 : Clothing::findAll()</h2>
            <?php
            $clothings = Clothing::findAll();
            echo '<div class="count">Nombre de vêtements : ' . count($clothings) . '</div>';
            
            foreach ($clothings as $item) {
                echo '<div class="product-card">';
                echo '<span class="badge badge-clothing">Vêtement</span>';
                echo '<div class="product-name">' . htmlspecialchars($item->getName()) . '</div>';
                echo '<div class="product-details">';
                echo '<div class="detail-item"><span class="detail-label">Taille:</span> ' . htmlspecialchars($item->getSize()) . '</div>';
                echo '<div class="detail-item"><span class="detail-label">Couleur:</span> ' . htmlspecialchars($item->getColor()) . '</div>';
                echo '<div class="detail-item"><span class="detail-label">Type:</span> ' . htmlspecialchars($item->getType()) . '</div>';
                echo '<div class="detail-item"><span class="detail-label">Prix:</span> ' . ($item->getPrice() / 100) . ' €</div>';
                echo '<div class="detail-item"><span class="detail-label">Frais mat.:</span> ' . ($item->getMaterialFee() / 100) . ' €</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- TEST 3 -->
        <div class="test-section">
            <h2>👕 Test 3 : Clothing::create()</h2>
            <?php
            $newClothing = new Clothing(
                0, 'Pull Hiver', ['pull.jpg'], 4999,
                'Pull chaud pour hiver', 30, new DateTime(), new DateTime(),
                1, 'L', 'Gris', 'Pull', 800
            );
            
            $result = $newClothing->create();
            if ($result) {
                echo '<div class="success">✅ Vêtement créé avec succès !<br>';
                echo '<strong>ID du nouveau vêtement :</strong> ' . $newClothing->getId() . '</div>';
            } else {
                echo '<div class="warning">❌ Échec de la création</div>';
            }
            ?>
        </div>

        <!-- TEST 4 -->
        <div class="test-section">
            <h2>👕 Test 4 : Clothing::update()</h2>
            <?php
            if ($clothing) {
                $clothing->setColor('Noir');
                $clothing->setPrice(3499);
                $result = $clothing->update();
                
                if ($result) {
                    echo '<div class="success">✅ Vêtement mis à jour avec succès !<br>';
                    echo '<strong>Nouvelle couleur :</strong> ' . htmlspecialchars($clothing->getColor()) . '<br>';
                    echo '<strong>Nouveau prix :</strong> ' . ($clothing->getPrice() / 100) . ' €</div>';
                } else {
                    echo '<div class="warning">❌ Échec de la mise à jour</div>';
                }
            } else {
                echo '<div class="info">ℹ️ Pas de produit à mettre à jour</div>';
            }
            ?>
        </div>

        <!-- TEST 5 -->
        <div class="test-section">
            <h2>⚡ Test 5 : Electronic::findOneById()</h2>
            <?php
            $electronic = Electronic::findOneById(1);
            if ($electronic) {
                echo '<div class="product-card">';
                echo '<div class="product-name">' . htmlspecialchars($electronic->getName()) . '</div>';
                echo '<div class="product-details">';
                echo '<div class="detail-item"><span class="detail-label">Marque:</span> ' . htmlspecialchars($electronic->getBrand()) . '</div>';
                echo '<div class="detail-item"><span class="detail-label">Prix:</span> ' . ($electronic->getPrice() / 100) . ' €</div>';
                echo '<div class="detail-item"><span class="detail-label">Garantie:</span> ' . ($electronic->getWarrantyFee() / 100) . ' €</div>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="warning">❌ Aucun produit électronique trouvé avec l\'ID 1</div>';
            }
            ?>
        </div>

        <!-- TEST 6 -->
        <div class="test-section">
            <h2>⚡ Test 6 : Electronic::findAll()</h2>
            <?php
            $electronics = Electronic::findAll();
            echo '<div class="count">Nombre de produits électroniques : ' . count($electronics) . '</div>';
            
            foreach ($electronics as $item) {
                echo '<div class="product-card">';
                echo '<span class="badge badge-electronic">Électronique</span>';
                echo '<div class="product-name">' . htmlspecialchars($item->getName()) . '</div>';
                echo '<div class="product-details">';
                echo '<div class="detail-item"><span class="detail-label">Marque:</span> ' . htmlspecialchars($item->getBrand()) . '</div>';
                echo '<div class="detail-item"><span class="detail-label">Prix:</span> ' . ($item->getPrice() / 100) . ' €</div>';
                echo '<div class="detail-item"><span class="detail-label">Garantie:</span> ' . ($item->getWarrantyFee() / 100) . ' €</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- TEST 7 -->
        <div class="test-section">
            <h2>⚡ Test 7 : Electronic::create()</h2>
            <?php
            $newElectronic = new Electronic(
                0, 'Tablette Pro', ['tablette.jpg'], 59999,
                'Tablette performante', 15, new DateTime(), new DateTime(),
                2, 'Samsung', 2000
            );
            
            $result = $newElectronic->create();
            if ($result) {
                echo '<div class="success">✅ Produit électronique créé avec succès !<br>';
                echo '<strong>ID du nouveau produit :</strong> ' . $newElectronic->getId() . '</div>';
            } else {
                echo '<div class="warning">❌ Échec de la création</div>';
            }
            ?>
        </div>

        <!-- TEST 8 -->
        <div class="test-section">
            <h2>⚡ Test 8 : Electronic::update()</h2>
            <?php
            if ($electronic) {
                $electronic->setBrand('Apple');
                $electronic->setPrice(79999);
                $result = $electronic->update();
                
                if ($result) {
                    echo '<div class="success">✅ Produit électronique mis à jour avec succès !<br>';
                    echo '<strong>Nouvelle marque :</strong> ' . htmlspecialchars($electronic->getBrand()) . '<br>';
                    echo '<strong>Nouveau prix :</strong> ' . ($electronic->getPrice() / 100) . ' €</div>';
                } else {
                    echo '<div class="warning">❌ Échec de la mise à jour</div>';
                }
            } else {
                echo '<div class="info">ℹ️ Pas de produit à mettre à jour</div>';
            }
            ?>
        </div>

    </div>
</body>
</html>