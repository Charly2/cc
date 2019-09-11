@extends('layout.master')
@section('content') 
<div>
    <div class="panel panel-default">
     <div class="panel-heading">Pedimentos no asignados<a class="btn btn-default btn-xs pull-right" href="{{ url('/expedientes/'.$id_expediente) }}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
      <div class="panel-body">

        <div class="col-md-12">
            <div class="page-header">
                <div class="clearfix"></div>
            </div>

            <div class="table-responsive" style="width: 100%">
                <table id="myTable" class="table table-striped table-bordered table-hover dataTable no-footer" cellspacing="0">
                    <thead>
                    <tr>
                        <td>Pedimento</td>
                        <td>Aduana despacho</td>
                        <td>Nombre Archivo M</td>

                        <td>Imp / Exp Nombre</td>
                        <td>Tipo operacion</td>
                        <td>Acción</td>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($pedimentos as $pedimento)
                        <tr>
                            <td>{{$pedimento->pedimento}}</td>
                            <td>{{$pedimento->getAduana()->nombre}}</td>
                            <td>{{$pedimento->archivoM}}</td>
                            <td>{{ $pedimento->impExpNombre }}</td>
                            <td>{{ ($pedimento->tipoOperacion==1) ? 'Importacion' : 'Exportacion' }}</td>
                            <td>
                                <a class="btn btn-default btn-xs pull-left" href="{{url('/asigna_pedimentos',['id_pedimento'=>$pedimento->id,'id_expediente' => $id_expediente])}}" role="button" aria-label="Left Align" title="Asignar Pedimento" ><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>



      </div>
    </div>  
</div>
@endsection

