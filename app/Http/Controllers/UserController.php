<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentEmpresaRepository;

use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $empresa;

    /**
     * UserController constructor.
     * @param $empresa
     */
    public function __construct(EloquentEmpresaRepository $empresa)
    {
        $this->empresa = $empresa;
    }


    public function empresas()
    {
        $empresas =   $this->empresa->empresasByPermission();
        return view('usuarios.empresas', ['empresas' => $empresas]);
    }
}
