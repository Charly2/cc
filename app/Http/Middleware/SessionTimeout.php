<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Session;

class SessionTimeout
{
    protected $session;
    protected $timeout = 3000;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }
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
        if (!$this->session->has('lastActivityTime')) {
            $this->session->put('lastActivityTime', time());
        } elseif (time() - $this->session->get('lastActivityTime') > $this->timeout) {
            $this->session->forget('lastActivityTime');
            Auth::logout();
            Session::flush();

            return redirect('/auth/login')->with([
                'alerta' => 'Su sessión finalizo por inactividad',
            ]);
        }
        $this->session->put('lastActivityTime', time());

        return $next($request);
    }
}
