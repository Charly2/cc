@extends('layout.master')

@section('content')
<div class="col-md-12">
	<div class="page-header">
		<h4>Detalle de pedimento </h4>
	</div>	
	<div class="panel panel-default">


 	<div class="panel-heading">PEDIMENTO
		@if(isset($expediente_id))
		<a class="btn btn-default btn-xs pull-right" href="{{ url('/expedientes/'.$expediente_id) }}" role="button">
			<span class="glyphicon glyphicon-arrow-left">
			</span> Atras
		</a>
		@else
		<a class="btn btn-default btn-xs pull-right" href="{{ route('pedimento.consulta', ['ejercicio' => $ejercicio, 'periodo' => $periodo]) }}" role="button">
			<span class="glyphicon glyphicon-arrow-left">
			</span> Atras
		</a>
		@endif
	</div>

	<div class="panel-body">
		<div class="col-md-12">
	    	<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
		    	<div class="col-md-3"><strong>NUM. PEDIMENTO: </strong>
					{{isset($pedimento['datos_pedimento'][0]['num_pedimento'])? $pedimento['datos_pedimento'][0]['num_pedimento']: $pedimento['datos_pedimento']['num_pedimento']}}
		    	</div> 
		    	<div class="col-md-3"><strong>TIP. OPER: </strong>
					{{isset($pedimento['datos_pedimento'][0]["tipoOperacion"])? $pedimento['datos_pedimento'][0]["tipoOperacion"]: $pedimento['datos_pedimento']["tipoOperacion"]}}
		    	</div>
		    	<div class="col-md-3"><strong>CVE. PEDIMENTO: </strong>
					{{isset($pedimento['datos_pedimento'][0]["cve_pedimento"])? $pedimento['datos_pedimento'][0]["cve_pedimento"]: $pedimento['datos_pedimento']["cve_pedimento"]}}
		    	</div>
		    	<div class="col-md-3"><strong>REGIMEN: </strong></div>
	    	</div>
	    	<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
		    	<div class="col-md-3"><strong>DESTINO/ORIGEN:</strong>
					 {{isset($pedimento['datos_pedimento'][0]["destino_origen"])? $pedimento['datos_pedimento'][0]["destino_origen"]: $pedimento['datos_pedimento']["destino_origen"]}}
		    	</div>
		    	<div class="col-md-3"><strong>TIPO CAMBIO: </strong>
					 {{isset($pedimento['datos_pedimento'][0]["tipo_cambio"])? $pedimento['datos_pedimento'][0]["tipo_cambio"]: $pedimento['datos_pedimento']["tipo_cambio"]}}
		    	</div>
		    	<div class="col-md-3"><strong>PESO EN KILOS: </strong>
					 {{isset($pedimento['datos_pedimento'][0]["peso_bruto"])? $pedimento['datos_pedimento'][0]["peso_bruto"]: $pedimento['datos_pedimento']["peso_bruto"]}}
		    	</div>
		    	<div class="col-md-3"><strong>ADUANA E/S: </strong>
					 {{isset($pedimento['datos_pedimento'][0]["id_aduana"])? $pedimento['datos_pedimento'][0]["id_aduana"]: $pedimento['datos_pedimento']["id_aduana"]}}
		    	</div>
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
		    					<td>
		    						{{isset($pedimento['datos_pedimento'][0]["entrada_salida"])? $pedimento['datos_pedimento'][0]["entrada_salida"]: $pedimento['datos_pedimento']["entrada_salida"]}}

		    					</td>
		    					<td>
		    						{{isset($pedimento['datos_pedimento'][0]["entrada"])? $pedimento['datos_pedimento'][0]["entrada"]: $pedimento['datos_pedimento']["entrada"]}}

		    					</td>
		    					<td>
									{{isset($pedimento['datos_pedimento'][0]["salida"])? $pedimento['datos_pedimento'][0]["salida"]: $pedimento['datos_pedimento']["salida"]}}
		    					</td>
		    				</tr>
		    			</tbody>
	    			</table>
				</div>

	    			<div class="col-md-6">
	    				<table>
	    					<tr>
	    						<td><strong>VALOR DOLARES: </strong></td>
	    						<td>${{number_format(isset($pedimento["datos_cove"][0]["valorTotalDollar"])?$pedimento["datos_cove"][0]["valorTotalDollar"]: $pedimento["datos_cove"]["valorTotalDollar"])}}</td>
	    					</tr>
	    					<tr>
	    						<td><strong>VALOR ADUANA: </strong></td>
	    						<td>${{number_format(isset($pedimento["datos_cove"][0]["valorTotalMoneda"])?$pedimento["datos_cove"][0]["valorTotalMoneda"]: $pedimento["datos_cove"]["valorTotalMoneda"])}}</td>
	    					</tr>
	    					<tr>
	    						<td><strong>PRECIO PAGADO/VALOR COMERCIAL: </strong></td>
	    						<td>${{number_format(isset($pedimento["datos_cove"][0]["valorTotalMoneda"])?$pedimento["datos_cove"][0]["valorTotalMoneda"]: $pedimento["datos_cove"]["valorTotalMoneda"])}}</td>
							</tr>
	    				</table>
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
					<div class="col-md-2"><strong>RFC: </strong>
						{{isset($pedimento['datos_pedimento'][0]["rfc_importador"])? $pedimento['datos_pedimento'][0]["rfc_importador"]: $pedimento['datos_pedimento']["rfc_importador"]}}
					</div>
					<div class="col-md-10"><strong>NOMBRE, DENOMINACION O RAZON SOCIAL: </strong>
						{{isset($pedimento['datos_pedimento'][0]["nombre_imp_exp"])? $pedimento['datos_pedimento'][0]["nombre_imp_exp"]: $pedimento['datos_pedimento']["nombre_imp_exp"]}}
					</div>
					<div class="col-md-12"><strong>DOMICILIO: </strong>
						{{isset($pedimento['datos_pedimento'][0]["direccion_imp_exp"])? $pedimento['datos_pedimento'][0]["direccion_imp_exp"]: $pedimento['datos_pedimento']["direccion_imp_exp"]}}
					</div>
				</div>

				<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
					<div class="col-md-6"><strong>ACUSE ELECTRONICO DE VALIDACION: </strong>{{isset($pedimento['datos_pedimento'][0]['num_pedimento'])? $pedimento['datos_pedimento'][0]['num_pedimento']: $pedimento['datos_pedimento']['num_pedimento']}}</div>
					<div class="col-md-6">
						<strong>CLAVE DE LA SECCION ADUANERA DE DESPACHO: </strong>
							{{isset($pedimento['datos_pedimento'][0]["id_aduana"])? $pedimento['datos_pedimento'][0]["id_aduana"]: $pedimento['datos_pedimento']["id_aduana"]}}
					</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #000;margin:10px;">
					<div class="col-md-6">
						<p class="text-center"><strong>FECHAS</strong></p>
		    			<table class="table table-striped">
			    			<tbody>
			    				<tr>
			    					<td>ENTRADA</td>
									@if(isset($pedimento['fechas']))
			    						<td>{{$pedimento['fechas']["fecha_entrada"]}}</td>
									@else
										<td>-</td>
									@endif
			    				</tr>
			    				<tr>
			    					<td>PAGO</td>
									@if(isset($pedimento['fechas']))
			    						<td>{{$pedimento['fechas']["fecha_pago"]}}</td>
									@else
										<td>-</td>
									@endif
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
										<th>Provedor</th>
									</tr>
								</thead>
								<tbody>
									<tr>
									<td>{{isset($pedimento["datos_cove"][0]["fecha_cove"])?$pedimento["datos_cove"][0]["fecha_cove"]: $pedimento["datos_cove"]["fecha_cove"]}}</td>
									<td>{{isset($pedimento["datos_cove"][0]["id_fiscal"])?$pedimento["datos_cove"][0]["id_fiscal"]: $pedimento["datos_cove"]["id_fiscal"]}}</td>
									<td>{{isset($pedimento["datos_cove"][0]["cove"])?$pedimento["datos_cove"][0]["cove"]: $pedimento["datos_cove"]["cove"]}}</td>
									<td>{{isset($pedimento["datos_cove"][0]["moneda_fact"])?$pedimento["datos_cove"][0]["moneda_fact"]: $pedimento["datos_cove"]["moneda_fact"]}}</td>
									<td>${{isset($pedimento["datos_cove"][0]["valorTotalDollar"])?$pedimento["datos_cove"][0]["valorTotalDollar"]: $pedimento["datos_cove"]["valorTotalDollar"]}}</td>
									<td>${{isset($pedimento["datos_cove"][0]["valorTotalMoneda"])?$pedimento["datos_cove"][0]["valorTotalMoneda"]: $pedimento["datos_cove"]["valorTotalMoneda"]}}</td>
									<td>{{isset($pedimento["datos_cove"][0]["pais"])?$pedimento["datos_cove"][0]["pais"]: $pedimento["datos_cove"]["pais"]}}</td>
									<td>{{isset($pedimento["datos_cove"][0]["nombre_proveedor"])?$pedimento["datos_cove"][0]["nombre_proveedor"]: $pedimento["datos_cove"]["nombre_proveedor"]}}</td>

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