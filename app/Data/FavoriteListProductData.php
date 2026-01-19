<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Carbon\Carbon;

class FavoriteListProductData extends Data
{
    public function __construct(
        public readonly int     $id,
        public readonly int     $favoriteListId,
        public readonly string  $sku,
        public readonly ?Carbon $createdAt,
        public readonly ?Carbon $updatedAt,
    ) {}
}
