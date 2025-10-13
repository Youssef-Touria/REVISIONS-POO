<?php
declare(strict_types=1);

require_once __DIR__ . '/Category.php';
require_once __DIR__ . '/Product.php';

// 1) Créer une catégorie (exemple)
$category = new Category(
    id: 3,
    name: 'T-shirts',
    description: 'T-shirts en coton bio',
    createdAt: new DateTime(),
    updatedAt: new DateTime()
);

// 2) Créer un produit en passant createdAt, updatedAt et category_id
$product = new Product(
    id: 1,
    name: 'T-shirt en coton',
    photos: ['photo1.jpg', 'photo2.jpg', 'photo3.jpg'],
    price: 2500, // en centimes (25,00€)
    description: 'Un magnifique t-shirt en coton bio, confortable et durable.',
    quantity: 50,
    createdAt: new DateTime(),
    updatedAt: new DateTime(),
    category_id: $category->getId()
);

// 3) Affichages
echo "<pre>";
echo "=== Test des Getters ===\n\n";
echo "ID: " . $product->getId() . "\n";
echo "Nom: " . $product->getName() . "\n";
echo "Photos: "; print_r($product->getPhotos()); echo "\n";
echo "Prix: " . $product->getPrice() . " centimes\n";
echo "Description: " . $product->getDescription() . "\n";
echo "Quantité: " . $product->getQuantity() . "\n";
echo "Créé le: " . $product->getCreatedAt()->format('Y-m-d H:i:s') . "\n";
echo "Modifié le: " . $product->getUpdatedAt()->format('Y-m-d H:i:s') . "\n";
echo "Category ID: " . $product->getCategoryId() . "\n\n";

echo "=== Test des Setters ===\n\n";
$product->setName('T-shirt premium en coton bio');
$product->setPrice(3500);
$product->setQuantity(45);
$product->setUpdatedAt(new DateTime());

echo "Nouveau nom: " . $product->getName() . "\n";
echo "Nouveau prix: " . $product->getPrice() . " centimes\n";
echo "Nouvelle quantité: " . $product->getQuantity() . "\n";
echo "Nouvelle date de modification: " . $product->getUpdatedAt()->format('Y-m-d H:i:s') . "\n\n";

echo "=== Affichage complet de l'objet ===\n\n";
print_r($product); // évite echo $product sans __toString()
echo "</pre>";
