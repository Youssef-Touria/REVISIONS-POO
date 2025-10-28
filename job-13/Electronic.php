<?php

require_once '../job-13/AbstractProduct.php';

class Electronic extends AbstractProduct
{
    private string $brand;
    private int $warranty_fee;

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
        ?string $brand = null,
        ?int $warranty_fee = null
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
        
        $this->brand = $brand ?? '';
        $this->warranty_fee = $warranty_fee ?? 0;
    }

    // Getters
    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getWarrantyFee(): int
    {
        return $this->warranty_fee;
    }

    // Setters
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setWarrantyFee(int $warranty_fee): void
    {
        $this->warranty_fee = $warranty_fee;
    }

    // Réécriture des méthodes pour retourner des instances de Electronic
    
    public static function findOneById(int $id): ?Electronic
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT * FROM product 
            WHERE id = :id AND category_id = 2
        ");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        // Récupérer les photos depuis product_photo
        $photos = [];
        $stmtPhotos = $pdo->prepare("
            SELECT url FROM product_photo 
            WHERE product_id = :product_id 
            ORDER BY id
        ");
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
            $data['brand'] ?? '',
            (int)($data['warranty_fee'] ?? 0)
        );
    }

    public static function findAll(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM product WHERE category_id = 2");
        $electronics = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Récupérer les photos
            $photos = [];
            $stmtPhotos = $pdo->prepare("
                SELECT url FROM product_photo 
                WHERE product_id = :product_id 
                ORDER BY id
            ");
            $stmtPhotos->execute([':product_id' => (int)$data['id']]);
            
            while ($photoRow = $stmtPhotos->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($photoRow['url'])) {
                    $photos[] = $photoRow['url'];
                }
            }

            $electronics[] = new self(
                (int)$data['id'],
                $data['name'],
                $photos,
                (int)$data['price'],
                $data['description'] ?? '',
                (int)($data['quantity'] ?? 0),
                !empty($data['created_at']) ? new DateTime($data['created_at']) : new DateTime(),
                !empty($data['updated_at']) ? new DateTime($data['updated_at']) : new DateTime(),
                (int)$data['category_id'],
                $data['brand'] ?? '',
                (int)($data['warranty_fee'] ?? 0)
            );
        }

        return $electronics;
    }

    public function create(): bool
    {
        $pdo = Database::getConnection();
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO product (name, price, description, quantity, category_id, brand, warranty_fee, created_at, updated_at)
                VALUES (:name, :price, :description, :quantity, 2, :brand, :warranty_fee, NOW(), NOW())
            ");

            $result = $stmt->execute([
                'name' => $this->getName(),
                'price' => $this->getPrice(),
                'description' => $this->getDescription(),
                'quantity' => $this->getQuantity(),
                'brand' => $this->brand,
                'warranty_fee' => $this->warranty_fee
            ]);

            if (!$result) {
                return false;
            }

            $this->setId((int)$pdo->lastInsertId());
            
            // Insérer les photos dans product_photo
            if (!empty($this->getPhotos())) {
                $stmtPhoto = $pdo->prepare("
                    INSERT INTO product_photo (product_id, url) 
                    VALUES (:product_id, :url)
                ");
                
                foreach ($this->getPhotos() as $photoUrl) {
                    $stmtPhoto->execute([
                        ':product_id' => $this->getId(),
                        ':url' => $photoUrl
                    ]);
                }
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Erreur create Electronic : " . $e->getMessage());
            return false;
        }
    }

    public function update(): bool
    {
        $pdo = Database::getConnection();
        
        try {
            $stmt = $pdo->prepare("
                UPDATE product 
                SET name = :name,
                    price = :price,
                    description = :description,
                    quantity = :quantity,
                    brand = :brand,
                    warranty_fee = :warranty_fee,
                    updated_at = NOW()
                WHERE id = :id AND category_id = 2
            ");

            $result = $stmt->execute([
                'id' => $this->getId(),
                'name' => $this->getName(),
                'price' => $this->getPrice(),
                'description' => $this->getDescription(),
                'quantity' => $this->getQuantity(),
                'brand' => $this->brand,
                'warranty_fee' => $this->warranty_fee
            ]);

            if (!$result) {
                return false;
            }
            
            // Mettre à jour les photos
            $stmtDelete = $pdo->prepare("DELETE FROM product_photo WHERE product_id = :product_id");
            $stmtDelete->execute([':product_id' => $this->getId()]);
            
            if (!empty($this->getPhotos())) {
                $stmtPhoto = $pdo->prepare("
                    INSERT INTO product_photo (product_id, url) 
                    VALUES (:product_id, :url)
                ");
                
                foreach ($this->getPhotos() as $photoUrl) {
                    $stmtPhoto->execute([
                        ':product_id' => $this->getId(),
                        ':url' => $photoUrl
                    ]);
                }
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Erreur update Electronic : " . $e->getMessage());
            return false;
        }
    }
}