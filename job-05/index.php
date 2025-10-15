<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/job-03/Product.php';
require_once dirname(__DIR__) . '/job-03/Category.php';
require_once dirname(__DIR__) . '/job-03/db.php';

// Injection de PDO dans la classe Product
Product::setPdo($pdo);

// Récupération du produit
$sql = "SELECT * FROM product WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => 7]);
$res = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$res) {
    exit("Aucun produit trouvé avec l'ID 7");
}

// Récupération des photos
$sql = "SELECT * FROM product_photo WHERE product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':product_id' => 7]);
$res_photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$photos = [];
foreach ($res_photos as $photo) {
    if (!empty($photo["name"])) {
        $photos[] = $photo["name"];
    }
}

// Création de l'instance Product
$product = new Product(
    (int)$res['id'],
    (string)$res['name'],
    $photos,
    (int)$res['price_cents'],
    (string)$res['description'],
    (int)$res['quantity'],
    !empty($res['createdAt']) ? DateTime::createFromFormat("Y-m-d H:i:s", $res['createdAt']) : null,
    !empty($res['updatedAt']) ? DateTime::createFromFormat("Y-m-d H:i:s", $res['updatedAt']) : null,
    (int)$res['category_id']
);

var_dump($product);

// Récupération de la catégorie via getCategory()
$category = $product->getCategory();
var_dump($category);
?>