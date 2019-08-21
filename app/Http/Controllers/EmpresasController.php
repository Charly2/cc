<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Repositories\EloquentEmpresaRepository;
use Illuminate\Routing\Controller as BaseController;
use Redirect;
use Session;
use View;

class EmpresasController extends BaseController
{
    /**
     * @var IEmpresaRepository
     */
    protected $empresa;

    /**
     * EmpresasController constructor.
     *
     * @param IEmpresaRepository $empresa
     */
    public function __construct(EloquentEmpresaRepository $empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $empresas = $this->empresa->all();

        return View::make('empresas.index', [
            'empresas' => $empresas,
        ]);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return View::make('empresas.create');
    }

    /**
     * @param StoreEmpresaRequest $request
     *
     * @return mixed
     */
    public function store(StoreEmpresaRequest $request)
    {
        $empresa = $this->empresa->create($request->all());

        if($empresa){
            return redirect('/empresas');
        } else {
            return view('empresas.create');
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $empresa = $this->empresa->findOrFail($id);

        return View::make('empresas.edit', ['empresa' => $empresa]);
    }

    /**
     * @param UpdateEmpresaRequest $request
     * @param $id
     *
     * @return mixed
     */
    public function update(UpdateEmpresaRequest $request, $id)
    {
        $empresa = $this->empresa->update($request->all(), $id);
        if ($empresa){
            return redirect('/empresas');
        } else {
            return view('empresas.edit', ['empresa' => $empresa]);
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $this->empresa->destroy($id);
        return redirect('/empresas');
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function registrarEmpresa($id)
    {
        $empresa = $this->empresa->findOrFail($id);

        Session::put('id', $empresa->id);
        Session::put('rfc', $empresa->rfc);
        Session::put('empresa', $empresa->nombre);

        return redirect('home');
    }
}
