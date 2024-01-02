<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtMiddleware extends ApiBaseController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return $this->exceptionError('Token tidak valid', 401);
            } elseif ($e instanceof TokenExpiredException) {
                return $this->exceptionError('Token kadaluarsa', 403);
            } else {
                return $this->exceptionError('Token authorisasi tidak ditemukan', 403);
            }
        }

        return $next($request);
    }
}
