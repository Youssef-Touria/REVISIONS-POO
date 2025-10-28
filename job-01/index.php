<?php

require_once 'Product.php';

// Création d'une instance de Product
$product = new Product(
    1,
    "T-shirt Rouge",
    ["photo1.jpg", "photo2.jpg", "photo3.jpg"],
    2999, // Prix en centimes (29.99€)
    "Un magnifique t-shirt rouge en coton",
    50
);

echo "=== Affichage des propriétés avec les getters ===\n\n";

echo "ID: ";
var_dump($product->getId());

echo "\nNom: ";
var_dump($product->getName());

echo "\nPhotos: ";
var_dump($product->getPhotos());

echo "\nPrix: ";
var_dump($product->getPrice());

echo "\nDescription: ";
var_dump($product->getDescription());

echo "\nQuantité: ";
var_dump($product->getQuantity());

echo "\nDate de création: ";
var_dump($product->getCreatedAt());

echo "\nDate de mise à jour: ";
var_dump($product->getUpdatedAt());

echo "\n\n=== Modification avec les setters ===\n\n";

// Modification de quelques propriétés
$product->setName("T-shirt Bleu");
$product->setPrice(3499);
$product->setQuantity(25);
$product->setUpdatedAt(new DateTime());

echo "Nouveau nom: ";
var_dump($product->getName());

echo "\nNouveau prix: ";
var_dump($product->getPrice());

echo "\nNouvelle quantité: ";
var_dump($product->getQuantity());

echo "\nDate de mise à jour modifiée: ";
var_dump($product->getUpdatedAt());