@extends('layout.master')
@section('content')
    <div class="col-md-12">
       @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button> <strong>{{ Session::get('message') }}</strong>
        </div>
        @endif
        <a href="{{ route('expedientes.create') }}" class="btn btn-sm btn-success btn-addon"><i class="glyphicon glyphicon-plus"></i> Crear expediente</a>
        <div class="table-responsive">
            <table class="table table-striped txt-medium" id="tableExpediente">
                <thead>
                <tr>
                    <th>N. Expediente</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                    <th>Estatus</th>
                </tr>
                </thead>
                <tbody>
                @foreach($expedientes as $expediente)
                    <tr>
                        <td>N.E.: {{ $expediente->expediente }}</td>
                        <td>{{ $expediente->nombre }}</td>
                        <td>{{ $expediente->descripcion }}</td>
                        <td>{{ date('d-m-Y', strtotime($expediente->created_at)) }}</td>
                        <td>
                            <a class="btn btn-default btn-sm" href="{{ route('expedientes.edit', $expediente->id) }}" role="button" data-toggle="tooltip" data-placement="bottom" title="Editar Expediente"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a class="btn btn-default btn-sm" href="{{ route('expedientes.show', $expediente->id) }}" role="button" data-toggle="tooltip" data-placement="bottom" title="Ver Expediente"><span class="glyphicon glyphicon-folder-open"></span></a>
                                @if ($expediente->status =='Abierto')

<!--
                                <a role="button" class="btn btn-default btn-sm" data-modal="true" data-href="{{ url('/delete_expediente',['action' => 'view','id'=>$expediente->id]) }}"  data-toggle="tooltip" data-placement="bottom" title="Borrar Expediente"><span class="glyphicon glyphicon-trash"></span></a>
-->
                                @endif

                        </td>

                        <td>
                            @if ($expediente->status =='Abierto')
                            <span class="btn btn-success" >
                            {{ $expediente->status }} <span class="icon-lock-open"></span>
                            </span>
                            @elseif ($expediente->status =='Cerrado')
                            <span class="btn btn-danger" >
                            {{ $expediente->status }} <span class="icon-lock"></span>
                            </span> 
                            @elseif ($expediente->status =='Proceso')
                            <span class="btn btn-info" >
                            {{ $expediente->status }} <span class="icon-lock"></span>
                            </span>                             
                            @endif                           
                         </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
    $(document).ready(function(){
        $('#tableExpediente').DataTable( {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            lengthMenu: [
                [10, 25, 50, -1],
                ['10', '25', '50', 'Ver Todo']
            ],
            buttons: ['pageLength']
        });
    });
@endpush
@endsection