<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public readonly string $sku,
        public readonly string $name,
        public readonly string $price,
        public readonly string $category,
        public readonly string $description,
        public readonly bool $inStock,
    ) {}
}

