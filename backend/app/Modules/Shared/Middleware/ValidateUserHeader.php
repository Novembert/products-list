<?php

namespace App\Modules\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\Shared\Response\ErrorJsonResponse;
use Illuminate\Http\Response;
use App\Modules\Shared\Enums\ErrorCode;

class ValidateUserHeader
{
  public function handle(Request $request, Closure $next)
  {

    // Here we are simulating a user validation by checking for a specific header.
    if ($request->header('X-User-Id') !== 'DUMMY_USER_ID') {
      return new ErrorJsonResponse(
        statusCode: Response::HTTP_UNAUTHORIZED,
        errorCode: ErrorCode::UNAUTHENTICATED,
        message: 'Unauthenticated',
      );
    }

    return $next($request);
  }
}
