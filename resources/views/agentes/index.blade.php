@extends('layout.master')
@section('content')
    <div class="col-md-12">
        <a href="{{url('/agenteaduanal/create')}}" class="btn btn-sm btn-success btn-addon"><i class="glyphicon glyphicon-plus"></i> Registrar Agencia Aduanal</a>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RFC</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($agencias as $agencia)
                    <tr>
                        <td>{{ $agencia->id }}</td>
                        <td>{{ $agencia->nombre }}</td>
                        <td>{{ $agencia->rfc }}</td>
                        <td>

                            <a href="{{url('/agenteaduanal/edit/'.$agencia->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Editar</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($agencias,'render'))
        {!! $agencias->render() !!}
    @endif
@endsection

