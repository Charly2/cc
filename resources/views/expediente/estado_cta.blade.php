   @extends('layout.master')

@section('content')
<style>
  .wrapper_full 
{
    width: 100%;
}
</style>

<div class="col-md-12">
   <div class="panel panel-default">
      <div class="panel-heading">Estado de Cuenta General<a class="btn btn-default btn-xs pull-right" href="{{url('expedientes/'.$id_expediente)}}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
      <div class="panel-body">
         <div class="row">
            <div class="col-xs-8" style="color: #817d7d;">
                <p class="h4">
                  <span style="font-size: 18pt;">
                      {{Session::get('nombre_agencia')}} <br>
                     
                  </span>
                </p>

                <span class="pull-left">Nombre de la empresa</span> <br>
                <strong>{{Session::get('empresas')}}</strong><br>
                <span class="pull-left">RFC</span> <br>
                <strong>{{Session::get('rfc')}}</strong><br>                


            </div>
            <div class="col-xs-4 text-right" style="color: #817d7d;">
              <a role="button" class="btn btn-primary"  href="{{ url('estado_cuenta',['id' => $id_expediente ,'tipo_estado' => 'agente' ]) }}"><span class="glyphicon glyphicon-list-alt"></span> Agente Aduanal</a>
            </div>

      

         </div>
        
          
        @if($message)
        <div class="alert alert-info alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button> <strong>{{ $message }}</strong>

        <button 
           type="button" 
           data-modal="true"
           class="btn btn-primary" 
           data-toggle="modal"
           
           data-target="#modal" >

          Aplicar Pago
        </button>



        </div>
        @endif

         <div class="line"></div>
         <div>
           
         </div>
         <div class="table-responsive">
           
         
         <table  class="table sorted_table small" type="invoices">
         
              <thead>
  
                <tr>
                          <th width="5%">#</th>
                          <th>Tipo Documento</th>
                          <th>Emisor</th>
                          <th>Concepto</th>

                          <th>Precio Unitario</th>
                          <th>Subtotal</th>
                          <th>Impuesto</th>
                          <th>Total</th>
                          <th>Pago</th>
                          <th>Saldo</th>
                          <th></th>
                </tr>
              </thead>
    




              @if(count($total)>0)


                    @foreach ($facturas as $row)
                    @if ($row['tipo_factura']!='cta_gastos')
                     <tr> 
                          <td></td>  
                          <td>Factura</td>
                          <td>{{$row['emisor_rfc']}} </td>
                          <td></td>
                         <td></td>
                         <!-- <td>{{ date('d/m/Y', strtotime($row['fecha'])) }} </td> -->
                          <td> ${{ number_format($row['subtotal'],2) }}</td>

                          <td> ${{ number_format($row['importe'],2) }}</td>
                          <td>${{ number_format($row['total'],2) }}</td>
                        
                          <td> ${{ number_format($row['pago'],2) }}</td>
                          <td>${{ number_format((is_null($row['saldo'])) ? $row['total'] : $row['saldo'],2)  }}</td>

                        
                      </tr>


                    @endif

                    @endforeach


                    @foreach ($facturas as $row)
                      @php
                        $factura = json_decode($row['json_cfdi'],true);
                        
                      @endphp
                      @if ($row['tipo_factura']=='cta_gastos')                      
                      <tr> 
                          <td></td>  
                          <td>Factura</td>
                          <td>{{$row['emisor_rfc']}} </td>
                          <td></td>
                         <td></td>
                         <!-- <td>{{ date('d/m/Y', strtotime($row['fecha'])) }} </td> -->
                          <td> ${{ number_format($row['subtotal'],2) }}</td>

                          <td> ${{ number_format($row['importe'],2) }}</td>
                          <td>${{ number_format($row['total'],2) }}</td>
                        
                          <td> ${{ number_format($row['pago'],2) }}</td>
                          <td>${{ number_format((is_null($row['saldo'])) ? $row['total'] : $row['saldo'],2)  }}</td>
                          <td>
                              <button class="btn btn-primary btn-sm dropdown-toggle" href="#" data-toggle="collapse" data-target=".ocultar-factura" >
                              Ver <span class="caret"></span>
                          </button>

                          </td>      
                        
                      </tr>


                        @foreach($factura['Conceptos'] as $concepto)

                         <tr class="collapse ocultar-factura">
                            
                            <td width="5%">{{ number_format($concepto['cantidad'],0)}}</td>
                            <td width="25%">{{$concepto['unidad']}} </td>
                            <td></td>
                            <td width="7%" >{{$concepto['descripcion']}} </td>
                            <td width="10%" >${{ number_format($concepto['valorUnitario'],2)}}</td>

                            <td width="12%" >${{ number_format($concepto['importe'],2)}}</td>
                            <td></td>
                            <td width="12%" >${{ number_format($concepto['importe'],2)}}</td>
                            <td ></td>
                            <td ></td>

                         </tr>
                         @endforeach
                        @endif
                    @endforeach





<!--
                    <tr>
                      <td colspan="4"></td>
                          <td></td>
                      <td>${{number_format($total['subtotal'],2)}}</td>
                      <td>${{number_format($total['importe'],2)}}</td>
                      <td>${{number_format($total['total'],2)}}</td>
                      <td>${{number_format($total['pago'],2)}}</td>
                      <td>${{number_format((empty($total['saldo'])) ? $total['total'] : $total['saldo'],2)  }}</td>
                      

                    </tr>   
--> 
                     <tr>
                        <td colspan="6" class="text-left no-border"></td>
                        <td><strong>Total</strong></td>
                        
                        <td class="text-left">${{number_format($total['total'],2)}}</td>
                        <td></td>
                        <td>${{number_format(($total['total'])-$total['pago'],2)}}</td>
                     </tr>



              @else
                          <tr>
                            <td colspan="10" ><center>No se ha cargado ninguna factura, por favor cargue todas las facturas relacionadas a esta importación.</center></td>
                          </tr>

              @endif
                    
                    


         </table>
         </div>
      </div>
   </div>
</div>
<!-- end -->
 

@if(count($total)>0)

<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form" method="GET" id="my_form" action="{{url('/aplicar_pago',['id' => $id_expediente ])}}">
             {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Aplicar Gasto</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12"> 
                    <h4>Saldo a Favor: $<span id="saldo_a_favor">{{ str_replace('-', '', $total['saldo'])  }}</span></h4>
                    <table class="table sorted_table small">
                        <thead>
                        <tr>
                            <th><input type="checkbox" onclick="marcar(this);" />  </th>
                            <th>RFC</th>
                            <th>Razón Social</th>
                            <th>Total</th>
                            
                            
                            
                            
                        </tr>
                        </thead>
                        <tbody>
               
                            @foreach ($facturas as $row)
                            @if ($row['tipo_factura']!='cta_gastos' && $row['status_factura']=='')
                            @php
                              $cfdi      = json_decode($row['json_cfdi'],true);
                              $totalsuma = array_sum(array_column($facturas, 'total'));

                            @endphp
                                <tr>
                                <td><input type="checkbox" name="factura" data-total="{{$row['total']}}" value="{{$row['id']}}" onclick="checkbox()"></td>
                                <td>{{$row['emisor_rfc']}}</td>
                                <td>{{ isset($cfdi['Emisor']['nombre']) ? $cfdi['Emisor']['nombre'] : $cfdi['Emisor']['Nombre']}}</td>
                                <td>${{number_format($row['total'],2)}}</td>
                                </tr>

                            @endif

                            @if ($row['tipo_factura']=='cta_ gastos')
                                <input type="hidden" name="id_cta_gastos"  value="{{$row['id_mov']}}">
                            @endif

                            @endforeach
             <!--
                            <tr>
                                <td></td>
                                <td><strong>Saldo a Favor</strong></td>
                                <td>$<span id="saldo_a_favor">{{ $total['saldo']  }}</span>
                                </td>
                            </tr>
            -->                            
                        </tbody>
                    </table>

        <input type="hidden" name="ids" id="ids" value="">

                    </div>
      
                    <div class="clearfix"></div>            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Aplicar Gasto</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif




    @endsection

<script>
function checkbox(){
    var str = $('#saldo_a_favor').text();
    var factura = [];
    var value = 0 ;
    $.each($("input[name='factura']:checked"), function(){            
        factura.push($(this).val());
        
        //total = $(this).data('total');  

        //resultado = parseFloat(saldo) - parseFloat(total);

        //var monto = factura.push($(this).data('total'));
    });
    var ids = factura.join(", ");


$("#ids").val(ids);



       
}
  function marcar(source) 
  {
    checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
    for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
    {
      if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
      {
        checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
        checkbox(); //ejecutamos la funcion para almacenar el id
      }
    }
  } 
</script>
@push('scripts')

@endpush

