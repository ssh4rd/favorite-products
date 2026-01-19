<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Carbon\Carbon;

class FavoriteListData extends Data
{
    public function __construct(
        public readonly int     $id,
        public readonly int     $userId,
        public readonly string  $name,
        public readonly ?Carbon $createdAt,
        public readonly ?Carbon $updatedAt,
    ) {}
}
