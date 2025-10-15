public function getProducts(): array
{
    $stmt = $this->pdo->prepare(
        "SELECT p.* 
         FROM product p 
         INNER JOIN product_category pc ON p.id = pc.product_id 
         WHERE pc.category_id = :category_id"
    );
    
    $stmt->execute(['category_id' => $this->id]);
    
    $products = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product = new Product(
            $row['name'],
            json_decode($row['photos'], true) ?? [],
            (int)$row['price'],
            $row['description']
        );
        $product->setId((int)$row['id']);
        $products[] = $product;
    }
    
    return $products;
}