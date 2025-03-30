<?php

namespace App\Modules\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Shared\Response\ErrorJsonResponse;

class ValidateUserHeader
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {

    // Here we are simulating a user validation by checking for a specific header.
    if ($request->header('X-User-Id') !== 'DUMMY_USER_ID') {
      return new ErrorJsonResponse("Unauthenticated.", 401);
    }

    return $next($request);
  }
}
