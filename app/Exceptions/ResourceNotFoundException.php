<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Response(
    response: 404,
    description: "Resource not found",
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: "message", type: "string", example: "Resource not found"),
            new OA\Property(property: "error", type: "string", example: "NOT_FOUND")
        ]
    )
)]
class ResourceNotFoundException extends Exception
{
    public function __construct(string $resource = "Resource", string $identifier = "")
    {
        $message = $resource . " not found";
        if ($identifier) {
            $message .= ": " . $identifier;
        }

        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error' => 'NOT_FOUND'
        ], 404);
    }
}
