<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\FavoriteListNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Services\FavoriteListProductService;
use App\Http\Requests\AddProductToListRequest;
use Illuminate\Http\Request;

#[OA\Tag(
    name: "Favorite List Products",
    description: "Operations for managing products in favorite lists"
)]

class FavoriteListProductController extends Controller
{
    public function __construct(
        private readonly FavoriteListProductService $favoriteListProductService
    ) {}

    #[OA\Post(
        path: "/api/lists/{listId}/products",
        operationId: "addProductToList",
        description: "Add a product to a specific favorite list",
        summary: "Add product to favorite list",
        security: [["cookieAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AddProductToListRequest")
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
    /**
     * @throws FavoriteListNotFoundException
     */
    public function store(AddProductToListRequest $request, Request $httpRequest, string $listId): JsonResponse
    {
        $user = $httpRequest->user;
        $product = $this->favoriteListProductService->addProductToList($user->id, (int) $listId, $request->sku);

        return response()->json($product, 201);
    }

    /**
     * @throws FavoriteListNotFoundException
     * @throws ProductNotFoundException
     */
    #[OA\Delete(
        path: "/api/lists/{listId}/products/{sku}",
        operationId: "removeProductFromList",
        description: "Remove a product from a specific favorite list",
        summary: "Remove product from favorite list",
        security: [["cookieAuth" => []]],
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
        $this->favoriteListProductService->removeProductFromList($user->id, (int) $listId, $sku);

        return response()->json(['message' => 'Product removed from list']);
    }
}
