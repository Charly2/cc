<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\Repositories\EloquentAgenciaRepository;
use App\Repositories\EloquentExpedienteRepository;
use DB;
use Illuminate\Http\Request;
use Session;
use View;

class ApiController extends Controller
{
    protected $agencia;

    protected $expediente;

    public function __construct(EloquentAgenciaRepository $agencia, EloquentExpedienteRepository $expediente){
        $this->agencia = $agencia;
        $this->expediente = $expediente;
    }

    public function service(Request $request)
    {
        //dd($request);+   where(clave,valor)   where(clave,opLogico,valor)
        $polizas = DB::connection('sqlsrv')->table('dbo.polizas')->select('codigo')->where('codigo', 'IP')->get();

        //$polizas = DB::connection('sqlsrv')->table('dbo.polizas')->select('select * from dbo.polizas where referencia like '%anticipo%'',array(1));
        return response()->json($polizas);
    }

    public function index()
    {
        // obtener el id de permisos de usuario
        $id_usuario = auth()->user()->usertype_id;
        $id_permiso = (int)auth()->user()->permiso_id;
        // en caso de permisos de administrador
        if($id_usuario == '1'){
            $agencias = Agencia::paginate(10);

        }elseif($id_usuario == '3'){
            $expedientes = $this->expediente->getExpedientesByEmpresaId(Session::get('id'))->get();
            $ids = array();
            foreach($expedientes as $expediente){
                if(!empty($expediente)){
                    $ids [] = (int)$expediente->agente_aduanal;
                }
            }
            $ids = array_unique($ids);
            $agencias = $this->agencia->agenciasByIds($ids);
        }elseif($id_usuario == '2'){
            $agencias = $this->agencia->agenciasByPermission();
        }
            //$this->agencia->all();

        return View::make('agentes.index', [
            'agencias' => $agencias,
        ]);
        //return view("agentes.index");
    }
}


