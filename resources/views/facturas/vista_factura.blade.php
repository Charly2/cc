   @extends('layout.master')

@section('content')


<div class="col-md-12">
   <div class="panel panel-default">
      <div class="panel-heading">Informacion de la factura <a class="btn btn-default btn-xs pull-right" href="{{url('/expedientes/'.$expediente_id)}}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
      <div class="panel-body">
         <div class="row">
            <div class="col-xs-8" style="color: #817d7d;">
                <p class="h4">
                  <span style="font-size: 18pt;">
                      {{$factura['Emisor']['Nombre']}} <br>
                      {{$factura['Emisor']['Rfc']}}
                  </span>
                </p>

                <span class="pull-left">Nombre del Receptor</span> <br>
                @if(isset($factura['Receptor']['Nombre']))
                <strong>{{$factura['Receptor']['Nombre']}}</strong><br>
                @else
                <strong>-</strong>
                @endif
                <span class="pull-left">RFC del receptor</span> <br>
                <strong>{{$factura['Receptor']['Rfc']}}</strong><br>
                <span class="pull-left">Dirección</span> <br>
                <strong>{{ 
                    isset($factura['ReceptorDomicilio']['calle']) ? $factura['ReceptorDomicilio']['pais'] : '' }}
                    {{isset($factura['ReceptorDomicilio']['noExterior']) ? $factura['ReceptorDomicilio']['noExterior'] : '' 

                }}
              </strong><br> 

            </div>
            <div class="col-xs-4 text-right" style="color: #817d7d;">
               <p class="h4">
                  <span style="font-size: 28pt;">{{$id_control}}</span>
               </p>
                <span class="pull-right">Fecha de la Factura</span> <br>
                <strong>{{$factura['cfdiComprobante']['Fecha']}}</strong><br>
                <span class="pull-right">N° Certificado del SAT</span> <br>
                <strong>{{$factura['tfd']['NoCertificadoSAT']}}</strong><br>
                <span class="pull-right">UUID</span> <br>
                <strong>{{$factura['tfd']['UUID']}}</strong><br>                
            </div>
         </div>
         <div class="well m-t">
            <div class="row">
                <!--
               <div class="col-xs-6">
                  <strong>Cobrar a:</strong>
                  <h4> <br></h4>
                  <p>
                     <span class="col-xs-3 no-gutter">Dirección:</span>
                     <span class="col-xs-9 no-gutter">direccion</span>
                     <span class="col-xs-3 no-gutter">Teléfono:</span>
                     <span class="col-xs-9 no-gutter"></span>
                  </p>
               </div>
               <div class="col-xs-6">
                  <strong>Cobrar a:</strong>
                  <h4> <br></h4>
                  <p>
                     <span class="col-xs-3 no-gutter">Dirección:</span>
                     <span class="col-xs-9 no-gutter">direccion</span>
                     <span class="col-xs-3 no-gutter">Teléfono:</span>
                     <span class="col-xs-9 no-gutter"></span>
                  </p>
               </div>
           -->
            </div>
         </div>
         <div class="line"></div>
         <table id="inv-details" class="table sorted_table small" type="invoices">
            <thead>
               <tr>
                  <th></th>
                  <th width="15%">cantidad </th>
                  <th width="25%">unidad </th>
                  <th width="30%">descripcion </th>
                  <th width="15%" >Precio Unitario </th>
                  <th width="15%" >Importe </th>
                
                  <th class="text-right inv-actions"></th>
               </tr>
            </thead>
            <tbody>
             

                @foreach($factura['Conceptos'] as $concepto)
               <tr>
                  <th></th>
                  <th width="20%">{{$concepto['cantidad']}}</th>
                  @if(isset($concepto['unidad']))
                       <th width="25%">{{$concepto['unidad']}} </th>
                  @else
                      <th width="25%">-</th>
                  @endif
                  <th width="7%" >{{$concepto['descripcion']}} </th>
                  <th width="10%" >{{$concepto['valorUnitario']}}</th>

                  <th width="12%" >{{$concepto['importe']}}</th>
                
                  <th class="text-right inv-actions"></th>
               </tr>
               @endforeach
               <tr>
                  <td colspan="7" class="text-right no-border"><strong>Sub Total</strong></td>
                  <td class="text-right">
                     ${{$factura['cfdiComprobante']['SubTotal']}}
                  </td>
                  <td></td>
               </tr>
               <tr>
                  <td colspan="7" class="text-right no-border">
                     <strong>Impuestos {{$factura['Traslado']['Impuesto']}} ({{$factura['Traslado']['TasaOCuota']}}%)</strong>
                  </td>
                  <td class="text-right">
                     {{$factura['Traslado']['Importe']}}
                  </td>
                  <td></td>
               </tr>
               <tr>
                  <td colspan="7" class="text-right no-border"><strong>
                     Total</strong>
                  </td>
                  <td class="text-right">
                     ${{$factura['cfdiComprobante']['Total']}}
                  </td>
                  <td></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
<!-- end -->

    @endsection