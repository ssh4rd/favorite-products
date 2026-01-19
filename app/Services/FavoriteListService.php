<?php

namespace App\Services;

use App\Repositories\FavoriteListRepository;
use App\Data\FavoriteListData;
use App\Data\FavoriteListWithProductsData;
use Illuminate\Support\Collection;

readonly class FavoriteListService
{
    public function __construct(
        private FavoriteListRepository $favoriteListRepository
    ) {}

    public function getAllForUser(int $userId): Collection
    {
        return $this->favoriteListRepository->getAllForUser($userId);
    }

    public function getByIdForUser(int $userId, int $listId): ?FavoriteListData
    {
        return $this->favoriteListRepository->findForUser($userId, $listId);
    }

    public function getWithProductsForUser(int $userId, int $listId): ?FavoriteListWithProductsData
    {
        return $this->favoriteListRepository->getWithProductsForUser($userId, $listId);
    }

    public function createForUser(int $userId, string $name): FavoriteListData
    {
        return $this->favoriteListRepository->createForUser($userId, $name);
    }

    public function updateForUser(int $userId, int $listId, string $name): ?FavoriteListData
    {
        return $this->favoriteListRepository->updateForUser($userId, $listId, $name);
    }

    public function deleteForUser(int $userId, int $listId): bool
    {
        return $this->favoriteListRepository->deleteForUser($userId, $listId);
    }
}
