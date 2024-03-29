<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class RfcEmpresa
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has('rfc')) {
            return redirect('/');
        }

        return $next($request);
    }
}
