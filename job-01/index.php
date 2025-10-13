<?php

require_once 'Product.php';

// Création d'une instance de Product
$product = new Product(
    1,
    'T-shirt en coton',
    ['photo1.jpg', 'photo2.jpg', 'photo3.jpg'],
    2500, // Prix en centimes (25,00€)
    'Un magnifique t-shirt en coton bio, confortable et durable.',
    50
);

echo "=== Test des Getters ===\n\n";

echo "<br><br>";
var_dump("ID: " . $product->getId());
echo "<br><br>";
var_dump("Nom: " . $product->getName());
echo "<br><br>";
var_dump("Photos: ", $product->getPhotos());
echo "<br><br>";
var_dump("Prix: " . $product->getPrice() . " centimes");
echo "<br><br>";
var_dump("Description: " . $product->getDescription());
echo "<br><br>";
var_dump("Quantité: " . $product->getQuantity());
echo "<br><br>";
var_dump("Créé le: " . $product->getCreatedAt()->format('Y-m-d H:i:s'));
echo "<br><br>";
var_dump("Modifié le: " . $product->getUpdatedAt()->format('Y-m-d H:i:s'));
echo "<br><br>";

echo "\n=== Test des Setters ===\n\n";

// Modification des propriétés avec les setters
$product->setName('T-shirt premium en coton bio');
$product->setPrice(3500);
$product->setQuantity(45);
$product->setUpdatedAt(new DateTime());

var_dump("Nouveau nom: " . $product->getName());
echo "<br><br>";
var_dump("Nouveau prix: " . $product->getPrice() . " centimes");
echo "<br><br>";
var_dump("Nouvelle quantité: " . $product->getQuantity());
echo "<br><br>";
var_dump("Nouvelle date de modification: " . $product->getUpdatedAt()->format('Y-m-d H:i:s'));
echo "<br><br>";

echo "\n=== Affichage complet de l'objet ===\n\n";
echo "<br><br>";

//var_dump($product);
echo $product;
