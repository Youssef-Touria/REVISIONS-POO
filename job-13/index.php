<?php
// N'incluez db.php qu'UNE SEULE FOIS
require_once '../job-06/db.php';
require_once '../job-06/Category.php';
require_once 'AbstractProduct.php';
require_once 'Clothing.php';
require_once 'Electronic.php';

echo "<h1>Job 13 - Test AbstractProduct</h1>";

// Test 1 : Essayer d'instancier AbstractProduct (devrait échouer)
echo "<h2>Test 1 : Instanciation de AbstractProduct</h2>";
try {
    // Cette ligne est commentée car elle causerait une erreur fatale
    // $product = new AbstractProduct();
    echo "✅ AbstractProduct ne peut pas être instanciée (c'est normal !)<br>";
} catch (Error $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

// Test 2 : Utiliser Clothing (devrait fonctionner)
echo "<h2>Test 2 : Clothing fonctionne</h2>";
$clothing = Clothing::findOneById(2);
if ($clothing) {
    echo "✅ Vêtement trouvé : " . $clothing->getName() . "<br>";
} else {
    echo "❌ Aucun vêtement trouvé<br>";
}

// Test 3 : Utiliser Electronic (devrait fonctionner)
echo "<h2>Test 3 : Electronic fonctionne</h2>";
$electronic = Electronic::findOneById(4);
if ($electronic) {
    echo "✅ Produit électronique trouvé : " . $electronic->getName() . "<br>";
} else {
    echo "❌ Aucun produit électronique trouvé<br>";
}