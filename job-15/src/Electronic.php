<?php

namespace App;

use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;

class Electronic extends AbstractProduct implements StockableInterface
{
    private string $brand;
    private int $warrantyFee;

    public function __construct(
        string $name,
        float $price,
        string $brand,
        int $warrantyFee = 0,
        int $quantity = 0
    ) {
        parent::__construct($name, $price, $quantity);
        $this->brand = $brand;
        $this->warrantyFee = $warrantyFee;
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
    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getWarrantyFee(): int
    {
        return $this->warrantyFee;
    }

    // Setters
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setWarrantyFee(int $warrantyFee): void
    {
        $this->warrantyFee = $warrantyFee;
    }
}