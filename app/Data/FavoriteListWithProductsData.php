<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "FavoriteListWithProducts",
    properties: [
        new OA\Property(property: "list", ref: "#/components/schemas/FavoriteList", type: "object"),
        new OA\Property(property: "products", type: "array", items: new OA\Items(ref: "#/components/schemas/Product"))
    ],
    type: "object"
)]
class FavoriteListWithProductsData extends Data
{
    public function __construct(
        public FavoriteListData $list,
        #[DataCollectionOf(ProductData::class)]
        public Collection $products,
    ) {}
}
