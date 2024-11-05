<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidarPoseePermiso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permisos): Response
    {
        if ( !auth()->user()->poseePermisos($permisos) && !auth()->user()->esAdministrador() )
        {
          return response()->json([ 'message' => 'No posee los permisos necesarios' ], 401);
        }

        return $next($request);
    }
}
