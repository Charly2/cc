<?php

namespace App\Http\Middleware;
use App\Repositories\EloquentUsuarioRepository;
use Closure;
use Session;

class CheckAgencia
{

    public function __construct(EloquentUsuarioRepository $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //valido que exista en la sesion el id_agencia
        if ($request->session()->has('id_usuario')) {
            return $next($request);
        }

        //extraigo los datos 
        $usertype_id = auth()->user()->usertype_id;
        $id_permiso  = auth()->user()->permiso_id;
        $id          = auth()->user()->id;
       
        $request->session()->put('id_usuario', $id);


        return $next($request);


        
    }
}
