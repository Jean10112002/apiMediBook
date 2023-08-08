<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class onlyPaciente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = Auth::guard('sanctum')->user();
        if ($usuario->rol_id!=3) {
            return response()->json(["error" => "no autorizado"], 403);
        }
        return $next($request);
    }
}
