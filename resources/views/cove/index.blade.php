@extends('layout.master')

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Lista general de COVES<a class="btn btn-default btn-xs pull-right" href="{{ url('/expediente/listado') }}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
            <div class="panel-body">
          
                <div class="row">
                          
                            <div class="col-md-8"></div>
                            <div class="col-md-4">

                              <a role="button" class="btn btn-primary pull-left"  href="{{url('cargar_cove',['id_empresa'=> Session::get('id')])}}" ><span class="glyphicon glyphicon-plus"></span> Carga Automática</a>

                              <a role="button" class="btn btn-primary pull-right"  href="{{url('cargar_cove',['id_empresa'=> Session::get('id')])}}" ><span class="glyphicon glyphicon-plus"></span> Cargar COVE</a>
                            </div>
                        <div class="col-md-12">
                        <div class="table-responsive"> 
                        <table id="myTable" class="table table-striped table-bordered table-hover dataTable no-footer" cellspacing="0">
                            <thead>
                                <tr>
                                    <td>N° Cove</td>
                                    <td>Fecha</td>
                                    <td>Valor Total en dolares</td>
                                    <td width="25">Expediente</td>
                                    <td>Proveedor</td>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach ($coves as $row)
                                  <tr>
                                    <td>{{$row->usr_num_cove}}</td>
                                    <td>{{ $row->getFechaExp() }}</td>
                                    <td>${{$row->getTotalMercancias()}}</td>
                                    <td>{{ isset($row->id_expediente) ? $row->id_expediente : 'N/A' }}</td>
                                    <td>{{$row->getEmisor()}}</td>
                                  </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
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
              ]
              ,buttons: ['pageLength']
          } );



});

 @endpush

   

@endsection