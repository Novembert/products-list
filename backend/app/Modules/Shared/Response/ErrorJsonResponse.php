<?php

namespace App\Modules\Shared\Response;

use Illuminate\Http\JsonResponse;

class ErrorJsonResponse extends JsonResponse
{
    public function __construct(
        string $message = 'An error occurred',
        int $statusCode = 500,
        array $headers = [],
        int $options = 0
    ) {
        parent::__construct([
            'error' => [
                'message' => $message,
            ],
        ], $statusCode, $headers, $options);
    }
}
