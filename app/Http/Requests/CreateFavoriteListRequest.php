<?php

namespace App\Http\Requests;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateFavoriteListRequest",
    required: ["name"],
    properties: [
        new OA\Property(
            property: "name",
            type: "string",
            maxLength: 255,
            minLength: 1,
            example: "My Favorites"
        )
    ],
    type: "object"
)]
class CreateFavoriteListRequest extends Data
{
    public function __construct(
        public string $name,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
