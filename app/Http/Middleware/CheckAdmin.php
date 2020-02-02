<?php

namespace App\Http\Middleware;

use Closure;


class CheckAdmin
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
        if($request->user() === null) {
            return redirect('/login');
        } elseif (strtolower($request->user()->role->name) == 'admin') {
            return $next($request);
        } else {
            return redirect()->route('home');
        }

    }
}
