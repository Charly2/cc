@extends('layout.master')

@section('content')
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">Lista general de Pedimentos<a class="btn btn-default btn-xs pull-right" href="{{ url('/expediente/listado') }}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
        <div class="panel-body">
            @if(Session::has('message'))
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button> <strong>{{ Session::get('message') }}</strong>
                </div>
            @endif
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <a role="button" class="btn btn-primary pull-left"  href="{{route('programacion_pedimento.index')}}" ><span class="glyphicon glyphicon-plus"></span> Carga Automática</a>
                    <a role="button" class="btn btn-primary pull-right"  href="{{url('cargar_pedimento',['id_empresa'=> Session::get('id')])}}" ><span class="glyphicon glyphicon-plus"></span> Cargar Pedimento</a>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive" style="width: 100%">
                        <table id="myTable" class="table table-striped table-bordered table-hover dataTable no-footer" cellspacing="0">
                            <thead>
                                <tr>
                                    <td>N° Pedimento</td>
                                    <td>Aduana Despacho</td>
                                    <td>Nombre Imp/Exp</td>
                                    <td width="25">Expediente</td>
                                    <td>Operación</td>
                                </tr>
                            </thead>
                            <tbody>

                                @if(count($pedimentos)> 0)

                                    @foreach ($pedimentos as $pedimento )

                                        <tr>
                                            <td>{{$pedimento->pedimento}}</td>
                                            <td>{{$pedimento->aduanaDespacho}}</td>
                                            <td>{{$pedimento->impExpNombre}}</td>
                                            <td>{{$pedimento->expediente_id?$pedimento->expediente_id:"S/A"}}</td>
                                            <td>{{$pedimento->tipoOperacion = 1 ? 'Importacion' : 'Exportación'}}</td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

$(document).ready(function(){
    $('#myTable').DataTable( {
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        lengthMenu: [
            [10, 25, 50, -1 ],
            [ '10 ', '25 ', '50 ', 'Ver Todo' ]
        ],
        buttons: ['pageLength']
    });
});

@endpush

@endsection