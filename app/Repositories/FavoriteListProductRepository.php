<?php

namespace App\Repositories;

use App\Models\FavoriteList;
use App\Models\FavoriteListProduct;
use App\Data\FavoriteListProductData;
use App\Exceptions\FavoriteListNotFoundException;
use App\Exceptions\ProductNotFoundException;

readonly class FavoriteListProductRepository
{
    /**
     * @throws FavoriteListNotFoundException
     */
    public function addProductToList(int $userId, int $listId, string $sku): FavoriteListProductData
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            throw new FavoriteListNotFoundException((string) $listId);
        }

        $product = FavoriteListProduct::firstOrCreate([
            'favorite_list_id' => $list->id,
            'sku' => $sku,
        ]);

        return FavoriteListProductData::from($product);
    }

    /**
     * @throws FavoriteListNotFoundException
     * @throws ProductNotFoundException
     */
    public function removeProductFromList(int $userId, int $listId, string $sku): void
    {
        $list = FavoriteList::where('user_id', $userId)->find($listId);

        if (!$list) {
            throw new FavoriteListNotFoundException((string) $listId);
        }

        $product = FavoriteListProduct::where('favorite_list_id', $list->id)
            ->where('sku', $sku)
            ->first();

        if (!$product) {
            throw new ProductNotFoundException($sku);
        }

        $product->delete();
    }
}
