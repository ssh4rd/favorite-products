<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\FavoriteList;
use App\Services\ProductService;
use Illuminate\Http\Request;

#[OA\Info(
    version: "1.0.0",
    description: "API for managing favorite product lists",
    title: "Favorite Products API"
)]

#[OA\Server(
    url: "http://localhost:8000",
    description: "Local development server"
)]

#[OA\Schema(
    schema: "FavoriteList",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "user_id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "My Favorites"),
        new OA\Property(property: "is_default", type: "boolean", example: false),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "FavoriteListProduct",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "favorite_list_id", type: "integer", example: 1),
        new OA\Property(property: "sku", type: "string", example: "ABC123"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ],
    type: "object"
)]

#[OA\Schema(
    schema: "Product",
    properties: [
        new OA\Property(property: "sku", type: "string", example: "ABC123"),
        new OA\Property(property: "name", type: "string", example: "Sample Product"),
        new OA\Property(property: "price", type: "number", format: "float", example: 99.99),
        new OA\Property(property: "description", type: "string", example: "A great product")
    ],
    type: "object"
)]

#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "apiKey",
    description: "Enter token in format (Bearer <token>)",
    name: "Authorization",
    in: "header"
)]

class FavoriteListController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    #[OA\Get(
        path: "/api/lists",
        operationId: "getFavoriteLists",
        description: "Retrieve all favorite lists for the authenticated user",
        summary: "Get user's favorite lists",
        security: [["sanctum" => []]],
        tags: ["Favorite Lists"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of favorite lists",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/FavoriteList")
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $user = $request->user;
        $lists = FavoriteList::where('user_id', $user->id)->get();

        return response()->json($lists);
    }

    #[OA\Post(
        path: "/api/lists",
        operationId: "createFavoriteList",
        description: "Create a new favorite list for the authenticated user",
        summary: "Create a new favorite list",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "My Favorites")
                ]
            )
        ),
        tags: ["Favorite Lists"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Favorite list created",
                content: new OA\JsonContent(ref: "#/components/schemas/FavoriteList")
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user;
        $list = FavoriteList::create([
            'user_id' => $user->id,
            'name' => $request->name,
        ]);

        return response()->json($list, 201);
    }

    #[OA\Get(
        path: "/api/lists/{id}",
        operationId: "getFavoriteList",
        description: "Retrieve a specific favorite list with its products",
        summary: "Get favorite list with products",
        security: [["sanctum" => []]],
        tags: ["Favorite Lists"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Favorite list ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Favorite list with products",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "list", ref: "#/components/schemas/FavoriteList"),
                        new OA\Property(property: "products", type: "array", items: new OA\Items(ref: "#/components/schemas/Product"))
                    ]
                )
            ),
            new OA\Response(response: 404, description: "List not found"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function show(Request $request, string $id)
    {
        $user = $request->user;
        $list = FavoriteList::where('user_id', $user->id)->findOrFail($id);

        $products = $list->products->map(function ($listProduct) {
            return $this->productService->getProductBySku($listProduct->sku);
        });

        return response()->json([
            'list' => $list,
            'products' => $products,
        ]);
    }

    #[OA\Put(
        path: "/api/lists/{id}",
        operationId: "updateFavoriteList",
        description: "Update the name of a specific favorite list",
        summary: "Update favorite list",
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Updated Favorites")
                ]
            )
        ),
        tags: ["Favorite Lists"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Favorite list ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Favorite list updated",
                content: new OA\JsonContent(ref: "#/components/schemas/FavoriteList")
            ),
            new OA\Response(response: 404, description: "List not found"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user;
        $list = FavoriteList::where('user_id', $user->id)->findOrFail($id);

        $list->update(['name' => $request->name]);

        return response()->json($list);
    }

    #[OA\Delete(
        path: "/api/lists/{id}",
        operationId: "deleteFavoriteList",
        description: "Soft delete a specific favorite list",
        summary: "Delete favorite list",
        security: [["sanctum" => []]],
        tags: ["Favorite Lists"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Favorite list ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Favorite list deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "List deleted")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "List not found"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = $request->user;
        $list = FavoriteList::where('user_id', $user->id)->findOrFail($id);

        $list->delete(); // Soft delete

        return response()->json(['message' => 'List deleted']);
    }
}
