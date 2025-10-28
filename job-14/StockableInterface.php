<?php

/**
 * Interface pour gérer les stocks de produits
 */
interface StockableInterface
{
    /**
     * Ajoute du stock au produit
     * @param int $stock Quantité à ajouter
     * @return self L'instance courante pour le chaînage
     */
    public function addStocks(int $stock): self;

    /**
     * Retire du stock au produit
     * @param int $stock Quantité à retirer
     * @return self L'instance courante pour le chaînage
     */
    public function removeStocks(int $stock): self;
}