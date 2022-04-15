<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class ApiAuthMiddleware {
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next) {
    $token = substr($request->header('Authorization'), 7);

    $jwtAuth = new JwtAuth();
    $isValid =  $jwtAuth->checkToken($token);

    if (!$isValid) {      
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => 'Bad token'
      );

      return response()->json($data, 400);      
    }

    $request->token = $token;
    $request->currentUser = $jwtAuth->checkToken($token, true);

    return $next($request);
  }
}
