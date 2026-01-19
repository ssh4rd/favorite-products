<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\FavoriteListNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Services\FavoriteListService;
use App\Http\Requests\CreateFavoriteListRequest;
use App\Http\Requests\UpdateFavoriteListRequest;
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

class FavoriteListController extends Controller
{
    public function __construct(
        private readonly FavoriteListService $favoriteListService
    ) {}

    #[OA\Get(
        path: "/api/lists",
        operationId: "getFavoriteLists",
        description: "Retrieve all favorite lists for the authenticated user",
        summary: "Get user's favorite lists",
        tags: ["Favorite Lists"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of favorite lists",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/FavoriteList")
                )
            )
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $user = $request->user;
        $lists = $this->favoriteListService->getAllForUser($user->id);

        return response()->json($lists);
    }

    #[OA\Post(
        path: "/api/lists",
        operationId: "createFavoriteList",
        description: "Create a new favorite list for the authenticated user",
        summary: "Create a new favorite list",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/CreateFavoriteListRequest")
        ),
        tags: ["Favorite Lists"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Favorite list created",
                content: new OA\JsonContent(ref: "#/components/schemas/FavoriteList")
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(CreateFavoriteListRequest $request, Request $httpRequest): JsonResponse
    {
        $user = $httpRequest->user;
        $list = $this->favoriteListService->createForUser($user->id, $request->name);

        return response()->json($list, 201);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    #[OA\Get(
        path: "/api/lists/{id}",
        operationId: "getFavoriteList",
        description: "Retrieve a specific favorite list with its products",
        summary: "Get favorite list with products",
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
                content: new OA\JsonContent(ref: "#/components/schemas/FavoriteListWithProducts")
            ),
            new OA\Response(response: 404, description: "List not found")
        ]
    )]
    public function show(Request $request, string $id)
    {
        $user = $request->user;
        $data = $this->favoriteListService->getWithProductsForUser($user->id, (int) $id);

        return response()->json($data);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    #[OA\Put(
        path: "/api/lists/{id}",
        operationId: "updateFavoriteList",
        description: "Update the name of a specific favorite list",
        summary: "Update favorite list",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateFavoriteListRequest")
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
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(UpdateFavoriteListRequest $request, Request $httpRequest, string $id)
    {
        $user = $httpRequest->user;
        $list = $this->favoriteListService->updateForUser($user->id, (int) $id, $request->name);

        return response()->json($list);
    }

    /**
     * @throws FavoriteListNotFoundException
     */
    #[OA\Delete(
        path: "/api/lists/{id}",
        operationId: "deleteFavoriteList",
        description: "Soft delete a specific favorite list",
        summary: "Delete favorite list",
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
            new OA\Response(response: 404, description: "List not found")
        ]
    )]
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = $request->user;
        $this->favoriteListService->deleteForUser($user->id, (int) $id);

        return response()->json(['message' => 'List deleted']);
    }
}
