<?php
declare(strict_types=1);

require_once __DIR__ . '/../job-06/Category.php'; 

abstract class AbstractProduct
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

    protected static function getPdo(): ?PDO
    {
        return self::$pdo;
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
            throw new RuntimeException("PDO non défini. Appelle d'abord AbstractProduct::setPdo(\$pdo).");
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

    // ========== MÉTHODES ABSTRAITES ==========
    // Ces méthodes DOIVENT être implémentées dans les classes enfants

    /**
     * Trouve un produit par son ID
     * Chaque classe enfant doit implémenter cette méthode
     */
    abstract public static function findOneById(int $id): ?self;

    /**
     * Récupère tous les produits du type
     * Chaque classe enfant doit implémenter cette méthode
     */
    abstract public static function findAll(): array;

    /**
     * Crée le produit dans la base de données
     * Chaque classe enfant doit implémenter cette méthode
     */
    abstract public function create(): bool;

    /**
     * Met à jour le produit dans la base de données
     * Chaque classe enfant doit implémenter cette méthode
     */
    abstract public function update(): bool;
}