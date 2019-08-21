@extends('layout.master')
 
@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Factura de Servicios<a class="btn btn-default btn-xs pull-right" href="{{ url('/expedientes/'.$expediente_id) }}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
            <div class="panel-body">
          
                <div class="row">
                          <div class="col-md-12">
                        <div>
                            
                        
                        <a style="margin-left: 10px;" role="button" class="btn btn-primary pull-right"  href="{{url('/subir_facturas',['expediente_id'=>$expediente_id ,'tipo'=>'cta_gastos'])}}" ><span class="glyphicon glyphicon-plus"></span> Cuenta de Gastos</a>                            

                        <a style="margin-left: 5px;" role="button" class="btn btn-primary pull-right"  href="{{url('/subir_facturas',['expediente_id'=>$expediente_id,'tipo'=>'comprobantes'])}}" ><span class="glyphicon glyphicon-plus"></span> Registrar Factura</a>
                        </div>
                        <h4>Facturas</h4>
                    <div class="table-responsive"> 
                        <table id="myTable" class="table table-striped table-bordered table-hover dataTable no-footer" cellspacing="0">
                            <thead>
                                <tr>
                                    <td width="15%">UUID</td>
                                    <td>RFC Emisor</td>
                                    <td width="15%">Emisor</td>

                                    <td width="15%">Fecha</td>
                                    
                                    <td width="10%">Metodo de pago</td>
                                    <td width="10%">Monto</td>
                                    <td width="10%">Status</td>
                                    <td>Acción</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facturas as $factura)
                                    @php
                                        $factura_json=json_decode($factura->json_cfdi,true);

                                    @endphp
                                    <tr>
                                        <td>{{ $factura_json['tfd']['UUID'] }}</td>
                                        <td>{{ $factura_json['Emisor']['rfc'] }}</td>
                                        <td>{{ $factura_json['Emisor']['nombre'] }}</td>
                                        <td>{{ $factura_json['cfdiComprobante']['fecha'] }}</td>    
                                        
                                        <td>{{ $factura_json['cfdiComprobante']['metodoDePago'] }}</td>
                                        <td style="text-align: right;">${{ number_format($factura_json['cfdiComprobante']['total'],2) }}</td>
                                        <td > {{ isset($factura->status_factura) ? $factura->status_factura : 'Sin pagar' }}
                                        </td>
                                        
                                        <td>

                                        <a href="{{ url($factura->xml_file) }}" role="button" aria-label="Left Align" title="Descargar XML" target="_blank">
                                            <img class="imagen" width="35" heigth="30" src="/img/save_xml.png" >
                                        </a>  

                                        <a href="{{ url('/vista_factura',['id' => $factura->id,'expediente_id'=> $expediente_id]) }}" role="button" aria-label="Left Align" title="Ver Factura" >
                                            <img class="imagen" width="35" heigth="30" src="/img/infopago.png" >
                                        </a>  


                              

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