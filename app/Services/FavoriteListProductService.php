<?php

namespace App\Services;

use App\Repositories\FavoriteListProductRepository;
use App\Data\FavoriteListProductData;

readonly class FavoriteListProductService
{
    public function __construct(
        private FavoriteListProductRepository $favoriteListProductRepository
    ) {}

    public function addProductToList(int $userId, int $listId, string $sku): ?FavoriteListProductData
    {
        return $this->favoriteListProductRepository->addProductToList($userId, $listId, $sku);
    }

    public function removeProductFromList(int $userId, int $listId, string $sku): bool
    {
        return $this->favoriteListProductRepository->removeProductFromList($userId, $listId, $sku);
    }
}
