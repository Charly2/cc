<?php

namespace App\Http\Middleware;

use App\Repositories\EloquentExpedienteRepository;
use Closure;
use Illuminate\Http\Response;
use Session;

class ExpedienteApproved
{
    /**
     * @var IExpedienteRepository
     */
    protected $expedienteRepo;

    /**
     * ExpedienteApproved constructor.
     *
     * @param IExpedienteRepository $expedienteRepository
     */
    public function __construct(EloquentExpedienteRepository $expedienteRepository)
    {
        $this->expedienteRepo = $expedienteRepository;
    }

    /**
     * @param $request
     * @param Closure $next
     *
     * @return Response
     */
    public function handle($request, Closure $next) : Response
    {
        $empresaId = Session::get('id');

        $expedienteId = explode('/',$request->path())[1];


        $expediente = $this->expedienteRepo->getExpedienteById($expedienteId);





        if($expediente->empresa_id != $empresaId){
            abort(403,'No tiene permisos para esta accion');
        }

        return $next($request);
    }
}
