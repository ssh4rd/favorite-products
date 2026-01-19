<?php

namespace App\Http\Requests;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AddProductToListRequest",
    required: ["sku"],
    properties: [
        new OA\Property(
            property: "sku",
            type: "string",
            minLength: 1,
            example: "ABC123"
        )
    ],
    type: "object"
)]
class AddProductToListRequest extends Data
{
    public function __construct(
        public string $sku,
    ) {}

    public static function rules(): array
    {
        return [
            'sku' => 'required|string',
        ];
    }
}
