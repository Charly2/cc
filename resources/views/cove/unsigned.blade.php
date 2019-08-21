@extends('layout.master')
@section('content') 
<div>
	<div class="panel panel-default">
     <div class="panel-heading">Coves no asignados<a class="btn btn-default btn-xs pull-right" href="{{ url('/expedientes/'.$id_expediente) }}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
	  <div class="panel-body">

        <div class="col-md-12">

            <div class="table-responsive">
                <table id="table-pedimentos" class="table table-striped ">
                    <thead>
                    <tr>
                        <td>N° Cove</td>
                        <td>Tipo operación</td>
                        <td>Fecha Expedición</td>
                        <td>Emisor</td>
                        <td>Valor Total</td>
                        <td>Acción</td>
                    </tr>
                    </thead>
                    <tbody id="pedimentos-loader">
               			@foreach ($coves as $row)
                        @php
                            $collection = collect(json_decode($row->json_cove,true));
                            $collection = new App\Collector\Collector($collection);
                            $mercancias = $collection->comprobantes['mercancias'];
                            $total= $collection->comprobantes->sum('valorTotal');
                            $coves_json=json_decode($row->json_cove,true);
                              foreach ($mercancias as $mercancia){
                                    if (is_array($mercancia)){
                                        $total += $mercancia['valorTotal'];
                                }
                              }
                        @endphp
                        <tr>
							<td>{{$row->usr_num_cove}}</td>
							<td>{{$coves_json['comprobantes']["tipoOperacion"]}}</td>
							<td>{{$coves_json['comprobantes']["fechaExpedicion"]}}</td>
							<td>{{$coves_json['comprobantes']["emisor"]["nombre"]}}</td>
							<td>${{number_format(isset($total) ? $total : '0' ,2)}}</td>
							<td>


                     
                                <a class="btn btn-default btn-xs pull-left" href="{{url('cove_factura',['id_cove'=>$row->id,'id_expediente' => $id_expediente])}}" role="button" aria-label="Left Align" title="Ver Factura COVE" ><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>

                                <a class="btn btn-default btn-xs pull-left" href="{{url('/asigna_cove',['id_cove'=>$row->id,'id_expediente'=>$id_expediente])}}" role="button" aria-label="Left Align" title="Asignar Cove" ><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a>
 

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

@push('scripts')

@endpush