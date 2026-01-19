<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Illuminate\Support\Collection;

class FavoriteListWithProductsData extends Data
{
    public function __construct(
        public readonly FavoriteListData $list,
        #[DataCollectionOf(ProductData::class)]
        public readonly Collection $products,
    ) {}
}
