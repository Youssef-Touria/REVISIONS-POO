<?php
declare(strict_types=1);

class Category
{
    private static ?PDO $pdo = null;
    
    private int $id;
    private string $name;
    private string $description;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    public function __construct(
        int $id = 0,
        string $name = '',
        string $description = '',
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
    }

    public static function setPdo(PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getCreatedAt(): ?DateTime { return $this->createdAt; }
    public function getUpdatedAt(): ?DateTime { return $this->updatedAt; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setCreatedAt(?DateTime $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(?DateTime $updatedAt): void { $this->updatedAt = $updatedAt; }

    public function getProducts(): array
    {
        if (self::$pdo === null) {
            throw new RuntimeException("PDO non initialisé.");
        }

        if ($this->id === 0) {
            return [];
        }

        $stmt = self::$pdo->prepare("
            SELECT * FROM product 
            WHERE category_id = :category_id
        ");
        
        $stmt->execute([':category_id' => $this->id]);
        $products = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $photos = [];
            $stmtPhotos = self::$pdo->prepare("SELECT url FROM product_photo WHERE product_id = :pid");
            $stmtPhotos->execute([':pid' => (int)$row['id']]);
            while ($p = $stmtPhotos->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($p['url'])) $photos[] = $p['url'];
            }

            $product = new Product(
                id: (int)$row['id'],
                name: (string)$row['name'],
                photos: $photos,
                price: (int)$row['price'],
                description: (string)($row['description'] ?? ''),
                quantity: (int)($row['quantity'] ?? 0),
                createdAt: !empty($row['created_at']) ? new DateTime($row['created_at']) : null,
                updatedAt: !empty($row['updated_at']) ? new DateTime($row['updated_at']) : null,
                categoryId: (int)$row['category_id']
            );
            $products[] = $product;
        }

        return $products;
    }
}