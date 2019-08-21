@extends('layout.master')
@section('content')
<div class="col-md-12">
	<div class="page-header">
		<h4>Detalle de pedimento {{ $data->REFERENCIA }}</h4>
	</div>	
	<div class="panel panel-default">
		<div class="panel-heading">PEDIMENTO<a class="btn btn-default btn-xs pull-right" href="{{url('/expedientes/'.$expediente_id)}}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>

	  <div class="panel-body">
	    <div class="col-md-12">
	    	<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
		    	<div class="col-md-3"><strong>NUM. PEDIMENTO: </strong>{{ $data->NUM_PEDIMENTO }}</div>
		    	<div class="col-md-3"><strong>TIP. OPER: </strong>{{ $data->TIPO_OPER }}</div>
		    	<div class="col-md-3"><strong>CVE. PEDIMENTO: </strong>{{ $data->CVE_PEDIMENTO }}</div>
		    	<div class="col-md-3"><strong>REGIMEN: </strong>{{ $data->REGIMEN }}</div>
	    	</div>
	    	<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
		    	<div class="col-md-3"><strong>DESTINO/ORIGEN: </strong>{{ $data->DESTINO_ORIGEN }}</div>
		    	<div class="col-md-3"><strong>TIPO CAMBIO: </strong>{{$data->TIPO_CAMBIO }}</div>
		    	<div class="col-md-3"><strong>PESO EN KILOS: </strong>{{ $data->PESO_KILOS}}</div>
		    	<div class="col-md-3"><strong>ADUANA E/S: </strong>{{ $data->ADUANA }}</div>
	    	</div>
	    	<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
	    		<div class="col-md-6">
	    			<p class="text-center"><strong>MEDIOS DE TRANSPORTE</strong></p>
	    			<table class="table table-striped">
		    			<thead>
		    				<tr>
		    					<th>ENTRADA/SALIDA</th>
		    					<th>ARRIBO</th>
		    					<th>SALIDA</th>
		    				</tr>
		    			</thead>
		    			<tbody>
		    				<tr>
		    					<td>{{ $data->ENTRADA_SALIDA }}</td>
		    					<td>{{$data->ARRIBO  }}</td>
		    					<td>{{ $data->SALIDA }}</td>
		    				</tr>
		    			</tbody>
	    			</table>
	    		</div>
	    		<div class="col-md-6">
	    			<p><strong>VALOR DOLARES: </strong>{{ $data->VALOR_DOLARES }}</p>
	    			<p><strong>VALOR ADUANA: </strong>{{ $data->VALOR_ADUANA }}</p>
	    			<p><strong>PRECIO PAGADO/VALOR COMERCIAL: </strong>{{ $data->VALOR_COMERCIAL }}</p>
	    		</div>
	    	</div>	    	
	    </div>
	  </div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">DATOS DEL IMPORTADOR/EXPORTADOR</div>
		<div class="panel-body">
			<div class="com-md-12">
				<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
					<div class="col-md-2"><strong>RFC: </strong>{{ $data->RFC_IMP_EXP  }}</div>
					<div class="col-md-10"><strong>NOMBRE, DENOMINACION O RAZON SOCIAL: </strong>{{  $data->RAZON_SOCIAL_IMP_EXP }}</div>
					<div class="col-md-12"><strong>DOMICILIO: </strong>{{  $data->DOMICILIO }} </div>
				</div>
				<div class="row" tyle="border-bottom: 1px solid #000;margin:10px;">
					<div class="col-md-2 col-md-offset-1"><strong>VAL.SEGUROS </strong><br/>0.00</div>
					<div class="col-md-2"><strong>SEGUROS </strong><br/>0.00</div>
					<div class="col-md-2"><strong>FLETES </strong><br/></div>
					<div class="col-md-2"><strong>EMBALAJES </strong><br/>0.00</div>
					<div class="col-md-3"><strong>OTROS INCREMENTABLES </strong><br/>0.00</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
					<div class="col-md-6"><strong>ACUSE ELECTRONICO DE VALIDACION: </strong><br/>-----</div>
					<div class="col-md-6">
						<strong>CLAVE DE LA SECCION ADUANERA DE DESPACHO: </strong>
			
					</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
					<div class="col-md-6">
						<p class="text-center"><strong>FECHAS</strong></p>
		    			<table class="table table-striped">
			    			<thead>
			    				<tr>
			    					<th>TIPO</th>
			    					<th>FECHA</th>
			    				</tr>
			    			</thead>
			    			<tbody>
			    				
					    				<tr>
					    					<td>ENTRADA</td>
					    					<td>{{  $data->FECHA_ENTRADA }}</td>				    					
					    				</tr>
					    	
			    	
					    				<tr>
					    					<td>PAGO</td>
					    					<td>{{ $data->FECHA_PAGO }}</td>				    					
					    				</tr>
					    		
			    			</tbody>
		    			</table>
					</div>
					<div class="col-md-6">
						<p class="text-center"><strong>TASAS NIVEL PEDIMENTO</strong></p>
		    			<table class="table table-striped">
			    			<thead>
			    				<tr>
			    					<th>CONTRIB.</th>
			    					<th>CVE. T. TASA</th>
			    					<th>TASA</th>
			    				</tr>
			    			</thead>
			    			<tbody>
			    				
			    				
					    				<tr>
					    					<td>{{ 'N/A' }}</td>
					    					<td></td>	
					    					<td></td>			    					
					    				</tr>
					    	
			    			</tbody>
		    			</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">COVES</div>
		<div class="panel-body">
			<div class="com-md-12">
				<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Factura</th>
										<th>Clave</th>
										<th>Moneda</th>
										<th>Total dolares</th>
										<th>Valor total</th>
										<th>Pais Facturacion</th>
										<th>PAIS </th>
									</tr>
								</thead>
								<tbody>
								
						    		
						    				<tr>
						    					<td>{{ $data->FECHA_COVE}}</td>
						    					<td>{{ $data->FACTURA_COVE }}</td>	
						    					<td>{{ $data->CLAVE_COVE }}</td>
						    					<td>{{ $data->FECHA_PAGO }}</td>		
						    					<td>{{ $data->MODEDA}}</td>		
						    					<td>{{ $data->TOTAL}}</td>		
						    					<td>{{ $data->TOTAL }}</td>		
						    					<td>{{ $data->PAIS_FACTURA }}</td>			    					
						    				</tr>
						    			
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 
@endsection