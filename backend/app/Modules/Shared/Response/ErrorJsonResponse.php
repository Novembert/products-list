<?php

namespace App\Modules\Shared\Response;

use App\Modules\Shared\Enums\ErrorCode;
use Illuminate\Http\JsonResponse;

class ErrorJsonResponse extends JsonResponse
{
    public function __construct(
        string $message = 'An error occurred',
        int $statusCode = 500,
        array $headers = [],
        int $options = 0,
        protected ErrorCode $errorCode = ErrorCode::UNKNOWN_ERROR
    ) {
        parent::__construct([
            'error' => [
                'message' => $message,
                'code' => $errorCode->getCode()
            ],
        ], $statusCode, $headers, $options);
    }
}
