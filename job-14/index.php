<?php
require_once __DIR__ . '/../job-06/db.php';
require_once __DIR__ . '/StockableInterface.php';
require_once __DIR__ . '/Clothing.php';
require_once __DIR__ . '/Electronic.php';

echo "<h1>Job 14 - Test StockableInterface</h1>";
echo "<hr>";

// Test 1 : Récupérer un vêtement
echo "<h2>Test 1 : Gestion stock Clothing</h2>";
$clothing = Clothing::findOneById(2);

if ($clothing) {
    echo "Produit : " . $clothing->getName() . "<br>";
    echo "Stock initial : " . $clothing->getQuantity() . "<br><br>";
    
    // Ajouter du stock
    echo "<strong>Ajout de 20 unités</strong><br>";
    $clothing->addStocks(20);
    echo "Nouveau stock : " . $clothing->getQuantity() . "<br><br>";
    
    // Retirer du stock
    echo "<strong>Retrait de 5 unités</strong><br>";
    $clothing->removeStocks(5);
    echo "Nouveau stock : " . $clothing->getQuantity() . "<br><br>";
    
    // Test du chaînage de méthodes
    echo "<strong>Chaînage : +10 puis -3</strong><br>";
    $clothing->addStocks(10)->removeStocks(3);
    echo "Stock final : " . $clothing->getQuantity() . "<br>";
} else {
    echo "❌ Aucun vêtement trouvé<br>";
}

echo "<hr>";

// Test 2 : Récupérer un produit électronique
echo "<h2>Test 2 : Gestion stock Electronic</h2>";
$electronic = Electronic::findOneById(4);

if ($electronic) {
    echo "Produit : " . $electronic->getName() . "<br>";
    echo "Stock initial : " . $electronic->getQuantity() . "<br><br>";
    
    // Ajouter du stock
    echo "<strong>Ajout de 15 unités</strong><br>";
    $electronic->addStocks(15);
    echo "Nouveau stock : " . $electronic->getQuantity() . "<br><br>";
    
    // Retirer du stock
    echo "<strong>Retrait de 8 unités</strong><br>";
    $electronic->removeStocks(8);
    echo "Nouveau stock : " . $electronic->getQuantity() . "<br>";
} else {
    echo "❌ Aucun produit électronique trouvé<br>";
}

echo "<hr>";

// Test 3 : Test des erreurs
echo "<h2>Test 3 : Gestion des erreurs</h2>";

try {
    echo "<strong>Tentative de retrait de stock négatif</strong><br>";
    $clothing->removeStocks(-5);
    echo "❌ ERREUR : Aucune exception levée !<br>";
} catch (InvalidArgumentException $e) {
    echo "✅ Exception correctement levée : " . $e->getMessage() . "<br>";
}

echo "<br>";

try {
    echo "<strong>Tentative de retrait de plus de stock que disponible</strong><br>";
    $newClothing = new Clothing(1, 'Test', [], 1000, 'Test', 5, new DateTime(), new DateTime(), 1, 'M', 'Bleu', 'T-shirt', 100);
    $newClothing->removeStocks(10); // Il n'a que 5 en stock
    echo "❌ ERREUR : Aucune exception levée !<br>";
} catch (RuntimeException $e) {
    echo "✅ Exception correctement levée : " . $e->getMessage() . "<br>";
}

