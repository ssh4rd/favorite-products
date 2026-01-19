<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\FavoriteList;
use App\Models\FavoriteListProduct;
use Illuminate\Http\Request;

#[OA\Tag(
    name: "Favorite List Products",
    description: "Operations for managing products in favorite lists"
)]

class FavoriteListProductController extends Controller
{
    #[OA\Post(
        path: "/api/lists/{listId}/products",
        operationId: "addProductToList",
        description: "Add a product to a specific favorite list",
        summary: "Add product to favorite list",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["sku"],
                properties: [
                    new OA\Property(property: "sku", type: "string", example: "ABC123")
                ]
            )
        ),
        tags: ["Favorite List Products"],
        parameters: [
            new OA\Parameter(
                name: "listId",
                description: "Favorite list ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Product added to list",
                content: new OA\JsonContent(ref: "#/components/schemas/FavoriteListProduct")
            ),
            new OA\Response(response: 404, description: "List not found"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(Request $request, string $listId): JsonResponse
    {
        $request->validate([
            'sku' => 'required|string',
        ]);

        $user = $request->user;
        $list = FavoriteList::where('user_id', $user->id)->findOrFail($listId);

        $product = FavoriteListProduct::firstOrCreate([
            'favorite_list_id' => $list->id,
            'sku' => $request->sku,
        ]);

        return response()->json($product, 201);
    }

    #[OA\Delete(
        path: "/api/lists/{listId}/products/{sku}",
        operationId: "removeProductFromList",
        description: "Remove a product from a specific favorite list",
        summary: "Remove product from favorite list",
        security: [["sanctum" => []]],
        tags: ["Favorite List Products"],
        parameters: [
            new OA\Parameter(
                name: "listId",
                description: "Favorite list ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "sku",
                description: "Product SKU",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Product removed from list",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Product removed from list")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "List or product not found"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function destroy(Request $request, string $listId, string $sku): JsonResponse
    {
        $user = $request->user;
        $list = FavoriteList::where('user_id', $user->id)->findOrFail($listId);

        $product = FavoriteListProduct::where('favorite_list_id', $list->id)
            ->where('sku', $sku)
            ->firstOrFail();

        $product->delete();

        return response()->json(['message' => 'Product removed from list']);
    }
}
