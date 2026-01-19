<?php

namespace App\Services;

use App\Exceptions\FavoriteListNotFoundException;
use App\Repositories\FavoriteListRepository;
use App\Data\FavoriteListData;
use App\Data\FavoriteListWithProductsData;
use Illuminate\Support\Collection;

readonly class FavoriteListService
{
    public function __construct(
        private FavoriteListRepository $favoriteListRepository
    ) {}

    /**
     * @return Collection<int, FavoriteListData>
     */
    public function getAllForUser(int $userId): Collection
    {
        return $this->favoriteListRepository->getAllForUser($userId);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    public function getByIdForUser(int $userId, int $listId): FavoriteListData
    {
        return $this->favoriteListRepository->findForUser($userId, $listId);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    public function getWithProductsForUser(int $userId, int $listId): FavoriteListWithProductsData
    {
        return $this->favoriteListRepository->getWithProductsForUser($userId, $listId);
    }

    public function createForUser(int $userId, string $name): FavoriteListData
    {
        return $this->favoriteListRepository->createForUser($userId, $name);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    public function updateForUser(int $userId, int $listId, string $name): FavoriteListData
    {
        return $this->favoriteListRepository->updateForUser($userId, $listId, $name);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    public function deleteForUser(int $userId, int $listId): void
    {
        $this->favoriteListRepository->deleteForUser($userId, $listId);
    }
}
