<?php
declare(strict_types=1);

class Product
{
    private int $id;
    private string $name;
    /** @var string[] */
    private array $photos;
    private int $price;          // Par ex. en centimes
    private string $description;
    private int $quantity;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private int $category_id;

    public function __construct(
        int $id,
        string $name,
        array $photos,
        int $price,
        string $description,
        int $quantity,
        DateTime $createdAt,
        DateTime $updatedAt,
        int $category_id
    ) {
        $this->id          = $id;
        $this->name        = $name;
        $this->photos      = $photos;
        $this->price       = $price;
        $this->description = $description;
        $this->quantity    = $quantity;
        $this->createdAt   = $createdAt;
        $this->updatedAt   = $updatedAt;
        $this->category_id = $category_id;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    /** @return string[] */
    public function getPhotos(): array { return $this->photos; }
    public function getPrice(): int { return $this->price; }
    public function getDescription(): string { return $this->description; }
    public function getQuantity(): int { return $this->quantity; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function getUpdatedAt(): DateTime { return $this->updatedAt; }
    public function getCategoryId(): int { return $this->category_id; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    /** @param string[] $photos */
    public function setPhotos(array $photos): void { $this->photos = $photos; }
    public function setPrice(int $price): void { $this->price = $price; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setQuantity(int $quantity): void { $this->quantity = $quantity; }
    public function setCreatedAt(DateTime $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(DateTime $updatedAt): void { $this->updatedAt = $updatedAt; }
    public function setCategoryId(int $category_id): void { $this->category_id = $category_id; }
}
