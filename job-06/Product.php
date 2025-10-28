<?php
declare(strict_types=1);

require_once __DIR__ . '/Category.php';

class Product
{
    private int $id;
    private string $name;
    private array $photos;
    private int $price;
    private string $description;
    private int $quantity;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private int $categoryId;

    private static ?PDO $pdo = null;
    private ?Category $category = null;

    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?array $photos = null,
        ?int $price = null,
        ?string $description = null,
        ?int $quantity = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        ?int $categoryId = null
    ) {
        $this->id = $id ?? 0;
        $this->name = $name ?? '';
        $this->photos = $photos ?? [];
        $this->price = $price ?? 0;
        $this->description = $description ?? '';
        $this->quantity = $quantity ?? 0;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
        $this->categoryId = $categoryId ?? 0;
    }

    // ========== GETTERS ==========
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPhotos(): array { return $this->photos; }
    public function getPrice(): int { return $this->price; }
    public function getDescription(): string { return $this->description; }
    public function getQuantity(): int { return $this->quantity; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function getUpdatedAt(): DateTime { return $this->updatedAt; }
    public function getCategoryId(): int { return $this->categoryId; }

    // ========== SETTERS ==========
    public function setId(int $v): void { $this->id = $v; }
    public function setName(string $v): void { $this->name = $v; }
    public function setPhotos(array $v): void { $this->photos = $v; }
    public function setPrice(int $v): void { $this->price = $v; }
    public function setDescription(string $v): void { $this->description = $v; }
    public function setQuantity(int $v): void { $this->quantity = $v; }
    public function setCreatedAt(DateTime $v): void { $this->createdAt = $v; }
    public function setUpdatedAt(DateTime $v): void { $this->updatedAt = $v; }
    public function setCategoryId(int $v): void { $this->categoryId = $v; }

    // ========== DB (static) ==========
    public static function setPdo(PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    public function getCategory(): ?Category
    {
        if ($this->category !== null) {
            return $this->category;
        }
        if ($this->categoryId <= 0) {
            return null;
        }
        if (!self::$pdo) {
            throw new RuntimeException("PDO non défini. Appelle d'abord Product::setPdo(\$pdo).");
        }

        $sql = "SELECT id, name, description, created_at, updated_at
                FROM category
                WHERE id = :id
                LIMIT 1";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([':id' => $this->categoryId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $this->category = new Category(
            (int)$row['id'],
            (string)$row['name'],
            (string)($row['description'] ?? ''),
            !empty($row['created_at']) ? new DateTime($row['created_at']) : new DateTime(),
            !empty($row['updated_at']) ? new DateTime($row['updated_at']) : new DateTime()
        );

        return $this->category;
    }

    /**
     * Trouve un produit par son ID (méthode statique)
     * @param int $id L'ID du produit à rechercher
     * @return Product|null L'instance du produit ou null si non trouvé
     */
    public static function findOneById(int $id): ?Product
    {
        if (self::$pdo === null) {
            throw new RuntimeException("PDO non initialisé. Appelez Product::setPdo() d'abord.");
        }

        $stmt = self::$pdo->prepare("
            SELECT id, name, price, description, quantity, created_at, updated_at, category_id
            FROM product
            WHERE id = :id
            LIMIT 1
        ");
        
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // Récupérer les photos
        $photos = [];
        $stmtPhotos = self::$pdo->prepare("
            SELECT url FROM product_photo 
            WHERE product_id = :product_id 
            ORDER BY id
        ");
        $stmtPhotos->execute([':product_id' => $id]);
        
        while ($photoRow = $stmtPhotos->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($photoRow['url'])) {
                $photos[] = (string)$photoRow['url'];
            }
        }

        return new Product(
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
    }

    /**
     * Récupère tous les produits (méthode statique)
     * @return Product[] Tableau d'instances Product
     */
    public static function findAll(): array
    {
        if (self::$pdo === null) {
            throw new RuntimeException("PDO non initialisé. Appelez Product::setPdo() d'abord.");
        }

        $stmt = self::$pdo->query("
            SELECT id, name, price, description, quantity, created_at, updated_at, category_id
            FROM product
            ORDER BY id
        ");
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = [];

        foreach ($rows as $row) {
            $photos = [];
            $stmtPhotos = self::$pdo->prepare("
                SELECT url FROM product_photo 
                WHERE product_id = :product_id 
                ORDER BY id
            ");
            $stmtPhotos->execute([':product_id' => (int)$row['id']]);
            
            while ($photoRow = $stmtPhotos->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($photoRow['url'])) {
                    $photos[] = (string)$photoRow['url'];
                }
            }

            $products[] = new Product(
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
        }

        return $products;
    }

    /**
     * Insère le produit courant dans la base de données
     * @return Product|false L'instance avec l'ID généré ou false en cas d'échec
     */
    public function create()
    {
        if (self::$pdo === null) {
            throw new RuntimeException("PDO non initialisé.");
        }
        
        try {
            $stmt = self::$pdo->prepare("
                INSERT INTO product (name, price, description, quantity, category_id, created_at, updated_at)
                VALUES (:name, :price, :description, :quantity, :category_id, NOW(), NOW())
            ");
            
            $result = $stmt->execute([
                ':name' => $this->name,
                ':price' => $this->price,
                ':description' => $this->description,
                ':quantity' => $this->quantity,
                ':category_id' => $this->categoryId
            ]);
            
            if (!$result) {
                return false;
            }
            
            $this->id = (int)self::$pdo->lastInsertId();
            
            if (!empty($this->photos)) {
                $stmtPhoto = self::$pdo->prepare("
                    INSERT INTO product_photo (product_id, url) 
                    VALUES (:product_id, :url)
                ");
                
                foreach ($this->photos as $photoUrl) {
                    $stmtPhoto->execute([
                        ':product_id' => $this->id,
                        ':url' => $photoUrl
                    ]);
                }
            }
            
            $this->createdAt = new DateTime();
            $this->updatedAt = new DateTime();
            
            return $this;
            
        } catch (PDOException $e) {
            error_log("Erreur create() : " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Met à jour le produit courant dans la base de données
     * @return bool True si la mise à jour réussit, false sinon
     */
    public function update(): bool
    {
        if (self::$pdo === null) {
            throw new RuntimeException("PDO non initialisé.");
        }
        
        if ($this->id <= 0) {
            throw new RuntimeException("Impossible de mettre à jour un produit sans ID.");
        }
        
        try {
            $stmt = self::$pdo->prepare("
                UPDATE product 
                SET name = :name,
                    price = :price,
                    description = :description,
                    quantity = :quantity,
                    category_id = :category_id,
                    updated_at = NOW()
                WHERE id = :id
            ");
            
            $result = $stmt->execute([
                ':id' => $this->id,
                ':name' => $this->name,
                ':price' => $this->price,
                ':description' => $this->description,
                ':quantity' => $this->quantity,
                ':category_id' => $this->categoryId
            ]);
            
            if (!$result) {
                return false;
            }
            
            $stmtDelete = self::$pdo->prepare("
                DELETE FROM product_photo WHERE product_id = :product_id
            ");
            $stmtDelete->execute([':product_id' => $this->id]);
            
            if (!empty($this->photos)) {
                $stmtPhoto = self::$pdo->prepare("
                    INSERT INTO product_photo (product_id, url) 
                    VALUES (:product_id, :url)
                ");
                
                foreach ($this->photos as $photoUrl) {
                    $stmtPhoto->execute([
                        ':product_id' => $this->id,
                        ':url' => $photoUrl
                    ]);
                }
            }
            
            $this->updatedAt = new DateTime();
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Erreur update() : " . $e->getMessage());
            return false;
        }
    }
}