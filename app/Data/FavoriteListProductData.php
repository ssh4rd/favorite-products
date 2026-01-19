<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Carbon\Carbon;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "FavoriteListProduct",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "favoriteListId", type: "integer", example: 1),
        new OA\Property(property: "sku", type: "string", example: "ABC123"),
        new OA\Property(property: "createdAt", type: "string", format: "date-time"),
        new OA\Property(property: "updatedAt", type: "string", format: "date-time")
    ],
    type: "object"
)]
class FavoriteListProductData extends Data
{
    public function __construct(
        public int $id,
        public int $favoriteListId,
        public string $sku,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {}
}
