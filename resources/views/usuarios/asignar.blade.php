@extends('layout.master')
@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ route('usuario.asignarEmpresa',['id' => $usuario->id]) }}">
        {!! csrf_field() !!}
        <div class="panel panel-default">
            <div class="panel-heading">
                Asignacion de empresas
            </div>
            <div class="panel-body">
                <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Empresa</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="empresa">
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @foreach($errors->all() as $error)
                    <h5><span class="text text-danger">{{ $error }}</span></h5>
                @endforeach
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-success btn-addon"><i class="glyphicon glyphicon-ok"></i>Asignar</button>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Empresa</th>
                    <th>RFC</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuario->empresas as $empresa)
                    <tr>
                        <td>{{ $empresa->id }}</td>
                        <td>{{ $empresa->nombre }}</td>
                        <td>{{ $empresa->rfc }}</td>
                        <td>
                            <a href="{{ route('usuario.desasignar', ['id' => $usuario->id,'empresa' => $empresa->id ]) }}" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Remover</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection