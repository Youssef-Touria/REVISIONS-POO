<?php

namespace App;

use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;

class Clothing extends AbstractProduct implements StockableInterface
{
    private string $size;
    private string $color;
    private string $type;

    public function __construct(
        string $name,
        float $price,
        string $size,
        string $color = '',
        string $type = '',
        int $quantity = 0
    ) {
        parent::__construct($name, $price, $quantity);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
    }

    // Implémentation de StockableInterface
    public function addStocks(int $stock): self
    {
        if ($stock < 0) {
            throw new \InvalidArgumentException("Le stock à ajouter doit être positif");
        }
        $this->quantity += $stock;
        return $this;
    }

    public function removeStocks(int $stock): self
    {
        if ($stock < 0) {
            throw new \InvalidArgumentException("Le stock à retirer doit être positif");
        }
        if ($stock > $this->quantity) {
            throw new \RuntimeException("Stock insuffisant");
        }
        $this->quantity -= $stock;
        return $this;
    }

    // Getters
    public function getSize(): string
    {
        return $this->size;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getType(): string
    {
        return $this->type;
    }

    // Setters
    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}