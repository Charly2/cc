@extends('layout.master')
@section('content')
    <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
        <a href="{{ route('empresas.create') }}" class="btn btn-sm btn-success btn-addon"><i class="glyphicon glyphicon-plus"></i> Registrar Empresa</a>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>RFC</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->rfc }}</td>
                            <td>{{ $empresa->nombre }}</td>
                            <td>
                                <div style="display: flex">
                                    <a href="{{ route('empresas.edit', ['id' => $empresa->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Editar</a>
                                    &nbsp;&nbsp;
                                    @include('empresas.delete')
                                    {{--<a href="{{ route('empresas.destroy', ['id' => $empresa->id]) }}" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Eliminar</a>--}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection