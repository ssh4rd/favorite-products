<?php

namespace App\Repositories;

use App\Models\FavoriteList;
use App\Data\FavoriteListData;
use App\Data\FavoriteListWithProductsData;
use App\Services\ProductService;
use App\Exceptions\FavoriteListNotFoundException;
use Illuminate\Support\Collection;

readonly class FavoriteListRepository
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * @return Collection<int, FavoriteListData>
     */
    public function getAllForUser(int $userId): Collection
    {
        $lists = FavoriteList::where('user_id', $userId)->get();

        return $lists->map(fn($list) => FavoriteListData::from($list));
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    public function findForUser(int $userId, int $listId): FavoriteListData
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            throw new FavoriteListNotFoundException((string) $listId);
        }

        return FavoriteListData::from($list);
    }

    public function createForUser(int $userId, string $name): FavoriteListData
    {
        $list = FavoriteList::create([
            'user_id' => $userId,
            'name' => $name,
        ]);

        return FavoriteListData::from($list);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    public function updateForUser(int $userId, int $listId, string $name): FavoriteListData
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            throw new FavoriteListNotFoundException((string) $listId);
        }

        $list->update(['name' => $name]);

        return FavoriteListData::from($list);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    public function deleteForUser(int $userId, int $listId): void
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            throw new FavoriteListNotFoundException((string) $listId);
        }

        $list->delete();
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    public function getWithProductsForUser(int $userId, int $listId): FavoriteListWithProductsData
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            throw new FavoriteListNotFoundException((string) $listId);
        }

        $products = $list->products->map(function ($listProduct) {
            return $this->productService->getProductBySku($listProduct->sku);
        });

        return new FavoriteListWithProductsData(
            list: FavoriteListData::from($list),
            products: $products
        );
    }
}
