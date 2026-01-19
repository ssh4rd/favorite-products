<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Product",
    properties: [
        new OA\Property(property: "sku", type: "string", example: "ABC123"),
        new OA\Property(property: "name", type: "string", example: "Sample Product"),
        new OA\Property(property: "price", type: "string", format: "decimal", example: "99.99"),
        new OA\Property(property: "category", type: "string", example: "Electronics"),
        new OA\Property(property: "description", type: "string", example: "A great product"),
        new OA\Property(property: "inStock", type: "boolean", example: true)
    ],
    type: "object"
)]
class ProductData extends Data
{
    public function __construct(
        public string $sku,
        public string $name,
        public string $price,
        public string $category,
        public string $description,
        public bool $inStock,
    ) {}
}
