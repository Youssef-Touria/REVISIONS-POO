<?php

declare(strict_types=1);

class Product
{
    private int $id;
    private string $name;
    private int $price;
    private string $description;
    private int $quantity;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private int $category_id;
    

     public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?int $price = null,
        ?string $description = null,
        ?int $quantity = null,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        ?int $category_id = null
       
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->category_id = $category_id;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): int
    {
        return $this->price;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function setName(string $v): void
    {
        $this->name = $v;
    }
    public function setPrice(int $v): void
    {
        $this->price = $v;
    }
    public function setDescription(string $v): void
    {
        $this->description = $v;
    }
    public function setQuantity(int $v): void
    {
        $this->quantity = $v;
    }
    public function setCreatedAt(DateTime $v): void
    {
        $this->createdAt = $v;
    }
    public function setUpdatedAt(DateTime $v): void
    {
        $this->updatedAt = $v;
    }
    public function setCategoryId(int $v): void
    {
        $this->category_id = $v;
    }
}
