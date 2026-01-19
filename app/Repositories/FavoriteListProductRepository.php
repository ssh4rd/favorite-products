<?php

namespace App\Repositories;

use App\Models\FavoriteList;
use App\Models\FavoriteListProduct;
use App\Data\FavoriteListProductData;

readonly class FavoriteListProductRepository
{
    public function addProductToList(int $userId, int $listId, string $sku): ?FavoriteListProductData
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            return null;
        }

        $product = FavoriteListProduct::firstOrCreate([
            'favorite_list_id' => $list->id,
            'sku' => $sku,
        ]);

        return FavoriteListProductData::from($product);
    }

    public function removeProductFromList(int $userId, int $listId, string $sku): bool
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            return false;
        }

        $product = FavoriteListProduct::where('favorite_list_id', $list->id)
            ->where('sku', $sku)
            ->first();

        if (!$product) {
            return false;
        }

        $product->delete();

        return true;
    }
}
