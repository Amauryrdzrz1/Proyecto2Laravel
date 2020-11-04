<?php

namespace App\Http\Middleware;

use Closure;

class InicioSesion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->email === 'amaury@gmail.com') {
            $request->role = 'admin:admin';
        } else {
            $request->role = 'user:info';
        }
        //admin contrasenia : 1234
        //correo amaury@gmail.com
        
        return $next($request);
    }

}
