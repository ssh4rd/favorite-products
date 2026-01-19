<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, $id)
 * @method static firstOrCreate(array $array)
 */
class FavoriteListProduct extends Model
{
    protected $fillable = ['favorite_list_id', 'sku'];

    public function favoriteList(): BelongsTo
    {
        return $this->belongsTo(FavoriteList::class);
    }
}
