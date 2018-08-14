<?php

namespace App\Http\Middleware;

use Closure;

use App;

class CorsMiddleware {
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next) {
    header('Access-Control-Allow-Origin: ' . env('URL_ORIGIN'));
    header('Access-Control-Allow-Headers: Content-Type');
    
    return $next($request);
  }
}
