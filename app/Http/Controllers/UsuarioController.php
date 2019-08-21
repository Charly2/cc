<?php

namespace App\Http\Controllers;

use App\CatUsertype;
use App\Http\Requests\AsignarEmpresaRequest;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Repositories\EloquentAgenciaRepository;
use App\Repositories\EloquentEmpresaRepository;
use App\Repositories\EloquentUsuarioRepository;
use Auth;
use DB;
use Illuminate\Routing\Controller as BaseController;
use Redirect;
use Validator;
use View;

class UsuarioController extends BaseController
{
    protected $usuario;
    protected $empresa;
    protected $agencia;

    public function __construct(EloquentUsuarioRepository $usuario, EloquentEmpresaRepository $empresa, EloquentAgenciaRepository $agencia)
    {
        $this->usuario = $usuario;
        $this->empresa = $empresa;
        $this->agencia = $agencia;
    }

    public function index()
    {
        $usuarios = $this->usuario->usuariosByPermission();
        return view('usuarios.index', ['usuarios' => $usuarios]);
    }

    public function create()
    {
        $usertype = CatUsertype::usertypePermission();
        $empresas = $this->empresa->empresasByPermission();
        $agentes  = $this->agencia->agenciasByPermission();

        return view('usuarios.create',[
            'usertype' => $usertype,
            'empresas' => $empresas,
            'agentes' => $agentes
        ]);
    }

    public function store(StoreUsuarioRequest $request)
    {
        $request->request->add(['permiso_id' => '0']);
        //en caso que los permisos sean de Agente
        if($request->usertype_id=='2' || $request->usertype_id=='3'){
            if ($request->usertype_id=='2') {
                //agrego un item al request
                $request->request->add(['permiso_id' => $request->agente]);
                $validator = Validator::make($request->all(),
                ['agente' => 'required|exists:catalogoaduanas,id'],
                ['exists' => 'Seleccione un Agente']);
            } elseif($request->usertype_id=='3') {
                $request->request->add(['permiso_id' => $request->empresa]);
                $validator = Validator::make($request->all(),
                ['empresa' => 'required|exists:catalogoaduanas,id'],
                ['exists' => 'Seleccione una Empresa']);
            }

            //En caso que falle nos manda al formulario con los mensajes de error
            if ($validator->fails()) {
                return back()
                ->withErrors($validator)
                ->withInput();
            }
        }
        $this->usuario->create($request->all());
        return redirect()->route('usuarios.index');
    }

    public function edit($id)
    {
        $usuario = $this->usuario->find($id);
        $usertype = CatUsertype::all();
        $empresas = $this->empresa->empresasByPermission();
        return view('usuarios.edit', [
            'usuario'  => $usuario,
            'usertype' => $usertype,
            'empresas' => $empresas
        ]);
    }

    public function update(UpdateUsuarioRequest $request, $id)
    {
        $this->usuario->update($request->all(), $id);

        return redirect()->route('usuarios.index');
    }

    public function destroy($id)
    {
        $this->usuario->delete($id);
        return redirect()->route('usuarios.index');
    }

    public function empresas()
    {
        $empresas =   $this->empresa->empresasByPermission();
        return View::make('usuarios.empresas', ['empresas' => $empresas]);
    }

    public function asignar($id)
    {
        $empresas = $this->empresa->all();
        $usuario = $this->usuario->findOrFail($id);

        return View::make('usuario.asignar', [
            'empresas' => $empresas,
            'usuario' => $usuario,
        ]);
    }
    public function asignarEmpresa(AsignarEmpresaRequest $request, $id)
    {
        $this->usuario->asignarEmpresa($id, $request->input('empresa'));

        return redirect()->back();
    }
    public function desasignar($idUsuario, $idEmpresa)
    {
        $this->usuario->desasignarEmpresa($idUsuario, $idEmpresa);

        return redirect()->back();
    }
    public function login()
    {
        return View::make('login.index');
    }
}
