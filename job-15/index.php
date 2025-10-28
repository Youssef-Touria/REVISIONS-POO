<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Clothing;
use App\Electronic;

// Test Clothing
echo "=== Test Clothing ===\n";
$tshirt = new Clothing("T-Shirt Premium", 29.99, "M", "Bleu", "Casual");
$tshirt->addStocks(100);
echo "Stock T-Shirt: " . $tshirt->getQuantity() . "\n";

$tshirt->removeStocks(15);
echo "Stock après vente: " . $tshirt->getQuantity() . "\n";
echo "Prix: " . $tshirt->getPrice() . "€\n";
echo "Taille: " . $tshirt->getSize() . "\n\n";

// Test Electronic
echo "=== Test Electronic ===\n";
$laptop = new Electronic("Laptop Pro", 1299.99, "Dell", 199);
$laptop->addStocks(50);
echo "Stock Laptop: " . $laptop->getQuantity() . "\n";

$laptop->removeStocks(5);
echo "Stock après vente: " . $laptop->getQuantity() . "\n";
echo "Prix: " . $laptop->getPrice() . "€\n";
echo "Marque: " . $laptop->getBrand() . "\n";
echo "Garantie: " . $laptop->getWarrantyFee() . "€\n\n";

// Test du chaînage
echo "=== Test Chaînage ===\n";
$phone = new Electronic("Smartphone", 699.99, "Samsung");
$phone->addStocks(100)
      ->removeStocks(10)
      ->addStocks(50);
echo "Stock Smartphone: " . $phone->getQuantity() . "\n";