<?php

require_once __DIR__ . '/../job-13/AbstractProduct.php';
require_once __DIR__ . '/StockableInterface.php';

class Clothing extends AbstractProduct implements StockableInterface
{
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?array $photos = null,
        ?int $price = null,
        ?string $description = null,
        ?int $quantity = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        ?int $category = null,
        ?string $size = null,
        ?string $color = null,
        ?string $type = null,
        ?int $material_fee = null
    ) {
        parent::__construct(
            $id,
            $name,
            $photos,
            $price,
            $description,
            $quantity,
            $createdAt,
            $updatedAt,
            $category
        );
        
        $this->size = $size ?? '';
        $this->color = $color ?? '';
        $this->type = $type ?? '';
        $this->material_fee = $material_fee ?? 0;
    }

    // Getters
    public function getSize(): string { return $this->size; }
    public function getColor(): string { return $this->color; }
    public function getType(): string { return $this->type; }
    public function getMaterialFee(): int { return $this->material_fee; }

    // Setters
    public function setSize(string $size): void { $this->size = $size; }
    public function setColor(string $color): void { $this->color = $color; }
    public function setType(string $type): void { $this->type = $type; }
    public function setMaterialFee(int $material_fee): void { $this->material_fee = $material_fee; }

    // ========== Implémentation de StockableInterface ==========
    
    /**
     * Ajoute du stock au vêtement
     */
    public function addStocks(int $stock): self
    {
        if ($stock < 0) {
            throw new InvalidArgumentException("Le stock à ajouter doit être positif");
        }
        
        $newQuantity = $this->getQuantity() + $stock;
        $this->setQuantity($newQuantity);
        
        return $this;
    }

    /**
     * Retire du stock au vêtement
     */
    public function removeStocks(int $stock): self
    {
        if ($stock < 0) {
            throw new InvalidArgumentException("Le stock à retirer doit être positif");
        }
        
        $newQuantity = $this->getQuantity() - $stock;
        
        if ($newQuantity < 0) {
            throw new RuntimeException("Stock insuffisant. Stock actuel : " . $this->getQuantity());
        }
        
        $this->setQuantity($newQuantity);
        
        return $this;
    }

    // ========== Méthodes abstraites implémentées ==========
    
    public static function findOneById(int $id): ?Clothing
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id AND category_id = 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $photos = [];
        $stmtPhotos = $pdo->prepare("SELECT url FROM product_photo WHERE product_id = :product_id ORDER BY id");
        $stmtPhotos->execute([':product_id' => $id]);
        
        while ($photoRow = $stmtPhotos->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($photoRow['url'])) {
                $photos[] = $photoRow['url'];
            }
        }

        return new self(
            (int)$data['id'],
            $data['name'],
            $photos,
            (int)$data['price'],
            $data['description'] ?? '',
            (int)($data['quantity'] ?? 0),
            !empty($data['created_at']) ? new DateTime($data['created_at']) : new DateTime(),
            !empty($data['updated_at']) ? new DateTime($data['updated_at']) : new DateTime(),
            (int)$data['category_id'],
            $data['size'] ?? '',
            $data['color'] ?? '',
            $data['type'] ?? '',
            (int)($data['material_fee'] ?? 0)
        );
    }

    public static function findAll(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM product WHERE category_id = 1");
        $clothings = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $photos = [];
            $stmtPhotos = $pdo->prepare("SELECT url FROM product_photo WHERE product_id = :product_id ORDER BY id");
            $stmtPhotos->execute([':product_id' => (int)$data['id']]);
            
            while ($photoRow = $stmtPhotos->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($photoRow['url'])) {
                    $photos[] = $photoRow['url'];
                }
            }

            $clothings[] = new self(
                (int)$data['id'],
                $data['name'],
                $photos,
                (int)$data['price'],
                $data['description'] ?? '',
                (int)($data['quantity'] ?? 0),
                !empty($data['created_at']) ? new DateTime($data['created_at']) : new DateTime(),
                !empty($data['updated_at']) ? new DateTime($data['updated_at']) : new DateTime(),
                (int)$data['category_id'],
                $data['size'] ?? '',
                $data['color'] ?? '',
                $data['type'] ?? '',
                (int)($data['material_fee'] ?? 0)
            );
        }

        return $clothings;
    }

    public function create(): bool
    {
        $pdo = Database::getConnection();
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO product (name, price, description, quantity, category_id, size, color, type, material_fee, created_at, updated_at)
                VALUES (:name, :price, :description, :quantity, 1, :size, :color, :type, :material_fee, NOW(), NOW())
            ");

            $result = $stmt->execute([
                'name' => $this->getName(),
                'price' => $this->getPrice(),
                'description' => $this->getDescription(),
                'quantity' => $this->getQuantity(),
                'size' => $this->size,
                'color' => $this->color,
                'type' => $this->type,
                'material_fee' => $this->material_fee
            ]);

            if (!$result) {
                return false;
            }

            $this->setId((int)$pdo->lastInsertId());
            
            if (!empty($this->getPhotos())) {
                $stmtPhoto = $pdo->prepare("INSERT INTO product_photo (product_id, url) VALUES (:product_id, :url)");
                
                foreach ($this->getPhotos() as $photoUrl) {
                    $stmtPhoto->execute([
                        ':product_id' => $this->getId(),
                        ':url' => $photoUrl
                    ]);
                }
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Erreur create Clothing : " . $e->getMessage());
            return false;
        }
    }

    public function update(): bool
    {
        $pdo = Database::getConnection();
        
        try {
            $stmt = $pdo->prepare("
                UPDATE product 
                SET name = :name, price = :price, description = :description, quantity = :quantity,
                    size = :size, color = :color, type = :type, material_fee = :material_fee, updated_at = NOW()
                WHERE id = :id AND category_id = 1
            ");

            $result = $stmt->execute([
                'id' => $this->getId(),
                'name' => $this->getName(),
                'price' => $this->getPrice(),
                'description' => $this->getDescription(),
                'quantity' => $this->getQuantity(),
                'size' => $this->size,
                'color' => $this->color,
                'type' => $this->type,
                'material_fee' => $this->material_fee
            ]);

            if (!$result) {
                return false;
            }
            
            $stmtDelete = $pdo->prepare("DELETE FROM product_photo WHERE product_id = :product_id");
            $stmtDelete->execute([':product_id' => $this->getId()]);
            
            if (!empty($this->getPhotos())) {
                $stmtPhoto = $pdo->prepare("INSERT INTO product_photo (product_id, url) VALUES (:product_id, :url)");
                
                foreach ($this->getPhotos() as $photoUrl) {
                    $stmtPhoto->execute([
                        ':product_id' => $this->getId(),
                        ':url' => $photoUrl
                    ]);
                }
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Erreur update Clothing : " . $e->getMessage());
            return false;
        }
    }
}