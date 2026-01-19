<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Carbon\Carbon;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "FavoriteList",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "userId", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "My Favorites"),
        new OA\Property(property: "createdAt", type: "string", format: "date-time"),
        new OA\Property(property: "updatedAt", type: "string", format: "date-time")
    ],
    type: "object"
)]
class FavoriteListData extends Data
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $name,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {}
}
