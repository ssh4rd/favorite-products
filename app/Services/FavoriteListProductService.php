<?php

namespace App\Services;

use App\Exceptions\FavoriteListNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Repositories\FavoriteListProductRepository;
use App\Data\FavoriteListProductData;

readonly class FavoriteListProductService
{
    public function __construct(
        private FavoriteListProductRepository $favoriteListProductRepository
    ) {}

    /**
     * @throws FavoriteListNotFoundException
     */
    public function addProductToList(int $userId, int $listId, string $sku): FavoriteListProductData
    {
        return $this->favoriteListProductRepository->addProductToList($userId, $listId, $sku);
    }

    /**
     * @throws FavoriteListNotFoundException
     * @throws ProductNotFoundException
     */
    public function removeProductFromList(int $userId, int $listId, string $sku): void
    {
        $this->favoriteListProductRepository->removeProductFromList($userId, $listId, $sku);
    }
}
