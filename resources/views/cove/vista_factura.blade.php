@extends('layout.master')

@section('content')

<div class="col-md-12">
   <div class="panel panel-default">
      <div class="panel-heading">Informacion de la factura <a class="btn btn-default btn-xs pull-right" href="{{url('expedientes/'.$id_expediente)}}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
      <div class="panel-body">
         <div class="row">
            <div class="col-xs-8" style="color: #817d7d;">

            </div>
            <div class="col-xs-4 text-right" style="color: #817d7d;">
                
            </div>
         </div>
         <div class="well m-t">
            <div class="row">
                
               <div class="col-xs-6">
                  <strong>Destinatario:</strong>
                 
                  <p>
                    <span class="col-xs-12 no-gutter"><strong>Nombre: </strong>{{$cove['comprobantes']['destinatario']['nombre']}}</span>
                     <span class="col-xs-12 no-gutter"><strong>Identificacion: </strong>{{$cove['comprobantes']['destinatario']['identificacion']}}</span>
                     
                     <span class="col-xs-12 no-gutter"><strong>Dirección: </strong>

                        {{empty($cove['comprobantes']['destinatario']['domicilio']['calle']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['calle']}}
                        {{empty($cove['comprobantes']['destinatario']['domicilio']['numeroExterior']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['numeroExterior']}}
                        {{empty($cove['comprobantes']['destinatario']['domicilio']['numeroInterior']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['numeroInterior']}}
                        {{empty($cove['comprobantes']['destinatario']['domicilio']['colonia']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['colonia']}}
                        {{empty($cove['comprobantes']['destinatario']['domicilio']['localidad']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['localidad']}}
                        {{empty($cove['comprobantes']['destinatario']['domicilio']['municipio']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['municipio']}}
                        {{empty($cove['comprobantes']['destinatario']['domicilio']['entidadFederativa']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['entidadFederativa']}}
                        {{empty($cove['comprobantes']['destinatario']['domicilio']['pais']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['pais']}}
                        {{empty($cove['comprobantes']['destinatario']['domicilio']['codigoPostal']) ?'' : $cove['comprobantes']['destinatario']['domicilio']['codigoPostal']}}

                     </span>
                     <span class="col-xs-3 no-gutter"></span>
                  </p> 
               </div>
               <div class="col-xs-6">
                  <strong>Emisor :</strong>
               
                  <p>
                     <span class="col-xs-12 no-gutter"><strong>Nombre: </strong>{{$cove['comprobantes']['emisor']['nombre']}} </span>
                     <span class="col-xs-12 no-gutter"><strong>Dirección: </strong>
                        {{empty($cove['comprobantes']['emisor']['domicilio']['calle']) ?'' : $cove['comprobantes']['emisor']['domicilio']['calle']}}
                        {{empty($cove['comprobantes']['emisor']['domicilio']['numeroExterior']) ?'' : $cove['comprobantes']['emisor']['domicilio']['numeroExterior']}}
                        {{empty($cove['comprobantes']['emisor']['domicilio']['numeroInterior']) ?'' : $cove['comprobantes']['emisor']['domicilio']['numeroInterior']}}
                        {{empty($cove['comprobantes']['emisor']['domicilio']['colonia']) ?'' : $cove['comprobantes']['emisor']['domicilio']['colonia']}}
                        {{empty($cove['comprobantes']['emisor']['domicilio']['localidad']) ?'' : $cove['comprobantes']['emisor']['domicilio']['localidad']}}

                        {{empty($cove['comprobantes']['emisor']['domicilio']['municipio']) ?'' : $cove['comprobantes']['emisor']['domicilio']['municipio']}}
                        {{empty($cove['comprobantes']['emisor']['domicilio']['entidadFederativa']) ?'' : $cove['comprobantes']['emisor']['domicilio']['entidadFederativa']}}
                        {{empty($cove['comprobantes']['emisor']['domicilio']['pais']) ?'' : $cove['comprobantes']['emisor']['domicilio']['pais']}}
                        {{empty($cove['comprobantes']['emisor']['domicilio']['codigoPostal']) ?'' : $cove['comprobantes']['emisor']['domicilio']['codigoPostal']}}</span>
                        
                      <span class="col-xs-12 no-gutter"><strong>N° Factura Original: </strong> </span>
                     
                      {{--<span class="col-xs-12 no-gutter"><strong>Patente Aduanal: </strong>{{$cove['patentesAduanales']['patenteAduanal']}} </span>--}}
                      <span class="col-xs-12 no-gutter"><strong>Fecha de Expedicion: </strong>{{$cove['comprobantes']['fechaExpedicion']}} </span>
                  </p>
               </div>
            </div>
         </div>
         <div class="line"></div>
         <table id="inv-details" class="table sorted_table small" type="invoices">
            <thead>
               <tr>
                  <th width="15%">Cantidad </th>
                  <th width="15%">Precio Unitario </th>
                  <th width="15%">Total </th>
                  <th width="10%" >Total Dolares </th>
                  <th width="10%" >Moneda </th>
                  <th width="30%">Descripción</th>
               </tr>
            </thead>
            <tbody>
            @if(isset($cove['comprobantes']['mercancias']['cantidad']))
              <tr>   
                <td>{{empty($cove['comprobantes']['mercancias']['cantidad']) ?'' : number_format($cove['comprobantes']['mercancias']['cantidad'],0)}} </td>

                <td>${{empty($cove['comprobantes']['mercancias']['valorUnitario']) ?'' : number_format($cove['comprobantes']['mercancias']['valorUnitario'],2)}} </td>
                <td>${{empty($cove['comprobantes']['mercancias']['valorTotal']) ?'' : number_format($cove['comprobantes']['mercancias']['valorTotal'],2)}}</td>
                <td>${{empty($cove['comprobantes']['mercancias']['valorDolares']) ?'' : number_format($cove['comprobantes']['mercancias']['valorDolares'],2)}}</td>
                <td>{{empty($cove['comprobantes']['mercancias']['tipoMoneda']) ?'' : $cove['comprobantes']['mercancias']['tipoMoneda']}}</td>
                <td>{{empty($cove['comprobantes']['mercancias']['descripcionGenerica']) ?'' : $cove['comprobantes']['mercancias']['descripcionGenerica']}}</td>

              </tr>
            @elseif($cove['comprobantes']['mercancias'][0])
                    @foreach($cove['comprobantes']['mercancias'] as $mercancia)
                        <tr>
                            <td>{{$mercancia['cantidad']}}</td>
                            <td>${{$mercancia['valorUnitario']}}</td>
                            <td>${{$mercancia['valorTotal']}}</td>
                            <td>${{$mercancia['valorDolares']}}</td>
                            <td>{{$mercancia['tipoMoneda']}}</td>
                            <td>{{$mercancia['descripcionGenerica']}}</td>
                        </tr>
                    @endforeach
              @endif
        
            
            </tbody>
         </table>
      </div>
   </div>
</div>
<!-- end -->

    @endsection