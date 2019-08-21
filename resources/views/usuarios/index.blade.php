@extends('layout.master')
@section('content')
        <div class="col-md-12">
            <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-success btn-addon"><i class="glyphicon glyphicon-plus"></i> Crear usuario</a>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre usuario</th>
                            <th>Email</th>
                            <th>Tipo usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->username }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ ($usuario->usertype) }}</td>
                                <td>
                                    @if(Session::get('id_usuario')==($usuario->id))
                                    <a href="{{ route('usuarios.edit', ['id' => $usuario->id]) }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-pencil"></span> Editar</a>
                                    @endif

                                    @if(Session::get('id_usuario')!=($usuario->id)) 
                                    @include('usuarios.delete')
                                    @endif
                                    <!--
                                    <a href="{{ route('usuario.asignar', ['id' => $usuario->id]) }}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-option-vertical"></span> Empresas</a>
                                    -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection