<?php

namespace App\Repositories;

use App\Models\FavoriteList;
use App\Data\FavoriteListData;
use App\Data\FavoriteListWithProductsData;
use App\Services\ProductService;
use Illuminate\Support\Collection;

readonly class FavoriteListRepository
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function getAllForUser(int $userId): Collection
    {
        $lists = FavoriteList::where('user_id', $userId)->get();

        return $lists->map(fn($list) => FavoriteListData::from($list));
    }

    public function findForUser(int $userId, int $listId): ?FavoriteListData
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        return $list ? FavoriteListData::from($list) : null;
    }

    public function createForUser(int $userId, string $name): FavoriteListData
    {
        $list = FavoriteList::create([
            'user_id' => $userId,
            'name' => $name,
        ]);

        return FavoriteListData::from($list);
    }

    public function updateForUser(int $userId, int $listId, string $name): ?FavoriteListData
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            return null;
        }

        $list->update(['name' => $name]);

        return FavoriteListData::from($list);
    }

    public function deleteForUser(int $userId, int $listId): bool
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            return false;
        }

        $list->delete();

        return true;
    }

    public function getWithProductsForUser(int $userId, int $listId): ?FavoriteListWithProductsData
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            return null;
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
