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
      <div class="panel-heading">Estado de Cuenta General<a class="btn btn-default btn-xs pull-right" href="{{url('estado_cuenta/'.$id_expediente.'/general')}}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
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



            </div>
         </div>
        
          

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

                          <th>Impuesto</th>
                          <th>Tasa</th>
                          <th>Importe</th>
                          <th>Subtotal</th>
                          
                          <th>Total</th>
                         <!-- <th>Pago</th>
                          <th>Saldo</th>-->
                          <th>Estatus</th>
                </tr>
              </thead>
           
           

              @if(count($total)>0)


                    @foreach ($facturas as $row)
                    @php
                      $factura = json_decode($row['json_cfdi'],true);
                    @endphp

                    @if ($row['tipo_factura']!='cta_gastos' and $row['pago']==$row['total'])
                     <tr> 
                          <td></td>  
                          <td>Factura</td>
                          <td>{{$row['emisor_rfc']}} </td>
                          <td>{{$factura['Traslado']['impuesto']}}</td>
                          <td>{{$factura['Traslado']['tasa']}}</td>
                          <td> ${{ number_format($row['importe'],2) }}</td>
                         <!-- <td>{{ date('d/m/Y', strtotime($row['fecha'])) }} </td> -->
                          <td> ${{ number_format($row['subtotal'],2) }}</td>

                         
                          <td>${{ number_format($row['total'],2) }}</td>
                        <!-- 
                          <td> ${{ number_format($row['pago'],2) }}</td>
                          <td>${{ number_format((is_null($row['saldo'])) ? $row['total'] : $row['saldo'],2)  }}</td>

                        --> 
                        <td>{{$row['status_factura']}}</td>
                      </tr>


                    @endif

                    @endforeach


                    @foreach ($facturas as $row)
                      @php
                        $factura = json_decode($row['json_cfdi'],true);
                        
                      @endphp
                      @if ($row['tipo_factura']=='cta_gastos' and $row['pago']==$row['total'] )                      
                      <tr> 
                          <td></td>  
                          <td>Factura</td>
                          <td>{{$row['emisor_rfc']}} </td>
                          <td>{{$factura['Traslado']['impuesto']}}</td>
                          <td>{{$factura['Traslado']['tasa']}}</td>
                          <td> ${{ number_format($row['importe'],2) }}</td>
                         <!-- <td>{{ date('d/m/Y', strtotime($row['fecha'])) }} </td> -->
                          <td> ${{ number_format($row['subtotal'],2) }}</td>

                         
                          <td>${{ number_format($row['total'],2) }}</td>
                        <!--
                          <td> ${{ number_format($row['pago'],2) }}</td>
                          <td>${{ number_format((is_null($row['saldo'])) ? $row['total'] : $row['saldo'],2)  }}</td>
                          <td>
              

                          </td>      
                        -->
                        <td>{{$row['status_factura']}}</td>
                      </tr>


                        @endif
                    @endforeach






                    <tr>
<!--                      
                      <td colspan="5"></td>
                      <td>${{number_format($total['importe'],2)}}</td>
                      <td>${{number_format($total['subtotal'],2)}}</td>
                     
                      <td>${{number_format($total['total'],2)}}</td>

                     <td>${{number_format($total['pago'],2)}}</td> 
                      @if($total['saldo']==0)
                      <td></td>
                      @else
                      <td>${{number_format($total['saldo'],2)  }}</td>
                      @endif
-->
                    </tr>   
<!--
                     <tr>
                        <td colspan="6" class="text-left no-border"></td>
                        <td><strong>Total</strong></td>
                        
                        <td class="text-left">${{number_format($total['total']+$total['pedim_total']+$total['cove_total'],2)}}</td>
                        <td></td>
                        <td>${{number_format(($total['total']+$total['pedim_total']+$total['cove_total'])-$total['pago'],2)}}</td>
                     </tr>
-->


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







    @endsection

