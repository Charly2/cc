<!DOCTYPE html>
<html>
<head>
	<title>FACTURA IMPRESA</title>
	<style>
		table{
			width: 100%;
			border-collapse: collapse;
		}
		th.border,td.border{
			border:1px solid #000;
		}
		.txt-right{
			text-align: right;
		}
               tr.header{
                 text-align: right;
        }
	</style>
</head>
<body style="font-size: 8px;">
	<table>
		<thead>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			<tr class="header">
				<td>
					
				</td>
				<td>
					<strong>EMISOR</strong><br/>
					@if(isset($cfdi['Emisor']['Nombre']))
					{{ $cfdi['Emisor']['Nombre'] }}<br/>
					@endif
                    {{isset($cfdi["DomicilioFiscal"]['calle']) ? $cfdi['DomicilioFiscal']['calle'] : '' }}
                    {{isset($cfdi['DomicilioFiscal']['noExterior']) ? $cfdi['DomicilioFiscal']['noExterior'] : '' }}
					{{isset($cfdi['DomicilioFiscal']['noInterior']) ? $cfdi['DomicilioFiscal']['noInterior'] : '' }}<br/>
					 {{isset($cfdi['DomicilioFiscal']['colonia']) ? $cfdi['DomicilioFiscal']['colonia'] : '' }} C.P. {{isset($cfdi['DomicilioFiscal']['codigoPostal']) ? $cfdi['DomicilioFiscal']['codigoPostal'] : '' }} <br/>
					{{isset($cfdi['DomicilioFiscal']['municipio']) ? $cfdi['DomicilioFiscal']['municipio'] : '' }}, 
					{{isset($cfdi['DomicilioFiscal']['estado']) ? $cfdi['DomicilioFiscal']['estado'] : '' }} 
					{{isset($cfdi['DomicilioFiscal']['Regimen']) ? $cfdi['DomicilioFiscal']['Regimen'] : '' }} 	<br/>RFC: {{ $cfdi['Emisor']['Rfc'] }}<br/>


				</td>
			</tr>
			<tr class="header">   
				<td style="text-align: left;">
					<strong>LUGAR DE EXPEDICION:</strong><br/>{{ $cfdi["cfdiComprobante"]["LugarExpedicion"] }}<br/>
					<strong>FECHA Y HORA DE EXPEDICION:</strong><br/>{{ $cfdi["cfdiComprobante"]["Fecha"] }}<br/>
					<strong>SERIE / FOLIO:</strong><br/>{{ $cfdi["cfdiComprobante"]['Serie']." / ".$cfdi["cfdiComprobante"]['Folio'] }}<br/>
					<strong>SERIE DEL CERTIFICADO EMISOR:</strong><br/>{{ $cfdi["cfdiComprobante"]["NoCertificado"] }}<br/>
					<strong>MONEDA:</strong><br/>{{ $cfdi["cfdiComprobante"]["Moneda"] }}<br/>
					<strong>TOTAL A PAGAR:</strong><br/>${{ number_format($cfdi["cfdiComprobante"]["Total"],2) }}<br/>
				</td>

				<td>
					<strong>RECEPTOR</strong><br/>
					@if(isset($cfdi['Receptor']['Nombre']))
					{{ $cfdi['Receptor']['Nombre'] }}<br/>
					@endif
                    <div width="30px">{{isset($cfdi['ReceptorDomicilio']['calle']) ? $cfdi['ReceptorDomicilio']['calle'] : '' }} </div>
                    {{isset($cfdi['ReceptorDomicilio']['noExterior']) ? $cfdi['ReceptorDomicilio']['noExterior'] : '' }}
					{{isset($cfdi['ReceptorDomicilio']['colonia']) ? $cfdi['ReceptorDomicilio']['colonia'] : '' }} 
                    C.P. {{isset($cfdi['ReceptorDomicilio']['codigoPostal']) ? $cfdi['ReceptorDomicilio']['codigoPostal'] : '' }} 
					{{isset($cfdi['ReceptorDomicilio']['municipio']) ? $cfdi['ReceptorDomicilio']['municipio'] : '' }}, 
					{{isset($cfdi['ReceptorDomicilio']['estado']) ? $cfdi['ReceptorDomicilio']['estado'] : '' }} 
					{{isset($cfdi['ReceptorDomicilio']['Regimen']) ? $cfdi['ReceptorDomicilio']['Regimen'] : '' }} 	<br/><strong>RFC:</strong> {{ $cfdi['Emisor']['Rfc'] }}<br/>


					<strong>FORMA DE PAGO:</strong><br/>{{ $cfdi["cfdiComprobante"]["FormaPago"] }}<br/>
					<strong>METODO DE PAGO:</strong>
		
				</td>
			</tr>
		</tbody>
	</table>
	<table>
		<thead>
			<tr>
				<th class="border" style="width:10%">Cantidad</th>
				<th class="border" style="width:10%">Unidad</th>
				<th class="border" style="width:40%">Concepto</th>
				<th class="border" style="width:10%">Valor unitario</th>
			
				<th class="border" style="width:5%">Valor Total</th>
				
			
			</tr>
		</thead>
		<tbody>


      

		@foreach($cfdi['Conceptos'] as $concepto)
			<tr>
				<td class="border" style="width:10%">{{ number_format($concepto['cantidad'],2) }}</td>
				@if(isset($concepto['unidad']))
				<td class="border" style="width:10%">{{ $concepto['unidad'] }}</td>
				@endif
				<td class="border" style="width:40%">{{ $concepto['descripcion'] }}</td>
                <td class="border txt-right" style="width:10%">{{ number_format($concepto['valorUnitario'],2) }}</td>
				
			
                <td class="border txt-right" style="width:10%">{{ number_format($concepto['importe'],2) }}</td>
				
			</tr>
		@endforeach
		<tr>
			<td colspan="4" style="border:none;text-align:right;font-weight:bold;padding:5px;">SUBTOTAL: </td>
			<td class="border txt-right">{{ number_format($cfdi["cfdiComprobante"]["SubTotal"],2) }}</td>
		</tr>

        <tr>
            <td colspan="4" style="border:none;text-align:right;font-weight:bold;padding:5px;">+ Total IVA: </td>
            <td class="border txt-right">{{ number_format($cfdi["Traslado"]["Importe"],2) }}</td>
        </tr>
  
		<tr>
			<td colspan="4" style="border:none;text-align:right;font-weight:bold;padding:5px;">TOTAL: </td>
			<td class="border txt-right">{{ number_format($cfdi["cfdiComprobante"]["Total"],2) }}</td>
		</tr>
		</tbody>
	</table>
	<br/>
	<br/>
	<div>

		<p></p>
	</div>
	<table>
		<tr>
			<td><p><strong>Sello digital de CFDI:</strong></p></td>
		</tr>
		<tr>
			<td>
                <div width="50 px" style="background-color: red;">
                    {{ $cfdi["tfd"]["SelloCFD"] }}
                </div>

            </td>
		</tr>
		<tr>
			<td><p><strong>Sello SAT: </strong></p></td>
		</tr>
		<tr>
			<td><p>{{ $cfdi["tfd"]["SelloSAT"] }}</p></td>
		</tr>
		<tr>
			<td><p><strong>Cadena Original:</strong></p></td>
		</tr>
		<tr>
			<td><p>{{ $cfdi["tfd"]["NoCertificadoSAT"] }}</p></td>
		</tr>
	</table>
	<br/><br/>
	<strong>Folio fiscal: </strong>{{ $cfdi["tfd"]["UUID"] }}<br/>
	<strong>Fecha y Hora Certificacion del CFDI: </strong>{{ $cfdi["tfd"]["FechaTimbrado"]  }}<br/>
	<strong>Certificado SAT: </strong>{{ $cfdi["tfd"]["NoCertificadoSAT"]  }}<br/>
</body>
</html>