<?php

namespace App\Http\Middleware;

use Closure;

use Exception;
use Illuminate\Http\Request;
use App\Classes\FormatResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWT extends FormatResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json($this->toJson($this->accesoDenegado('Sesion invalida')), 401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                // return $this->toJson($this->accesoDenegado('Token expired'));
                    return response()->json($this->toJson($this->accesoDenegado('Ha expirado su sesión')), 401);
            }else {
                return response()->json($this->toJson($this->accesoDenegado('No ha iniciado sesión')), 401);
            }
        }
        return $next($request);
    }
}
