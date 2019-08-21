<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content"> 
            <form class="form" method="POST" action="{{ route('movimientos.store',['id_expediente'=> $id]) }}">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close form-pago-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar pago</h4>
                </div>
                <div class="modal-body">

                    <div id="asigna_proveedor" style="display: block;" >
                        
                        <div class="col-md-12 jumbotron" style="padding: 5px;">
                            <!-- 
                        <div class="alert alert-info">
                             *Seleccione un <strong>proveedor</strong> de la lista.
                             <br>
                             *O asigne un nuevo <strong>proveedor</strong>.
                        </div>
                    -->
                            <div class="col-md-12">
                                <!--inicio -->
                                <div class="panel panel-info">
                                  <div class="panel-heading">Seleccione una Factura</div>
                                  <div class="panel-body">
                                    <input type="hidden" name="id_factura" id="id_factura" value="">
                                       <table id="mytable" class="table table-hover">
                                        <thead>
                                          <tr>
                                            <th></th>
                                            <th>Proveedor</th>
                                            <th>RFC </th>
                                            <th>Monto</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($facturas as $factura)
                                                @php
                                                    $factura_json=json_decode($factura->json_cfdi,true);

                                                @endphp
                                                <tr>
                                                    <td style="visibility: hidden">{{$factura->id}}</td>
                                                    <td>{{ $factura_json['Emisor']['Nombre'] }}</td>
                                                    <td>{{ $factura_json['Emisor']['Rfc'] }}</td>
                                       
                                                    <td style="text-align: right;">${{ $factura_json['cfdiComprobante']['Total'] }}</td>
                                         
                                                </tr>
                                            @endforeach
                                
                                        </tbody>
                                      </table>
                                  </div>
                                </div>
                                <!-- fin -->
                            </div>
                        </div>




                     </div>

                    <div id="proveedor_nuevo"  >
                        
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Proveedor</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre Proveedor" required="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">                        
                                    <label for="rfc">RFC</label>                        
                                    <input type="text" class="form-control" id="rfc" name="rfc" placeholder="RFC" required="true">
                                
                                </div>
                            </div>
                        </div>

                     </div>

                    <div class="col-md-12"> 
                        <div class="col-md-6">    
                            <div class="form-group">                        
                                <label for="monto">Monto</label>                
                                <input type="text" class="form-control" id="monto" placeholder="Monto" required="true" name="monto">
                            </div>
                        </div>
                        <!-- 
                        <div class="col-md-6">    
                             <label for="new"></label>                                
                            <div class="checkbox">
                              <label><input id="nuevo_proveedor" name="nuevo_proveedor" type="checkbox" value="">Nuevo Proveedor</label>
                            </div>
                        </div>
                        -->
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="forma_pago">Forma de pago</label>
                                    <select id="forma_pago" name="forma_pago" class="form-control">
                                        @foreach($tipoPago as $pago)
                                            <option value="{{ $pago->id }}">{{ $pago->nombreTipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>

                    </div>
                    {{--<div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monto">Numero de cuenta</label>
                                <input type="text" class="form-control" id="numero_cuenta" placeholder="Número de la cuenta" required="true" name="numero_cuenta">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="forma_pago">Nombre o razón social </label>
                                <input type="text" class="form-control" id="nombre_cuenta" placeholder="Nombre o Razón social" required="true" name="nombre_cuenta">
                            </div>
                        </div>

                    </div>--}}

                    <div class="clearfix"></div>            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default form-pago-close" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" onclick="regitrarPago()">Registrar pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    $("#nuevo_proveedor").on("change",function(){
       var change_check;
        change_check = document.getElementById("nuevo_proveedor").checked; 

        if(change_check==true) {
            
            $("#proveedor_nuevo").css("display", "block"); 
            $("#asigna_proveedor").css("display", "none"); 

        }else{
            $("#proveedor_nuevo").css("display", "none");
           // document.getElementById("date_bautismo_ES").value = "";
           $("#asigna_proveedor").css("display", "block"); 
            
  
        }
    });


     $("#id_proveedor").on("change",function(){
       var id_proveedor;
        id_proveedor = document.getElementById("id_proveedor").value; 

       var rfc;
        rfc =  $( "#id_proveedor option:selected" ).data('rfc');

        $("#rfc").val(rfc);
       // alert(rfc)
        

    });

  
 

$('#mytable').find('tr').click( function(){
  var id = $(this).find("td:eq(0)").text();
  var name = $(this).find("td:eq(1)").text();
  var rfc = $(this).find("td:eq(2)").text();
  var monto = $(this).find("td:eq(3)").text();
  //alert('ID --->' + id);
  $("#rfc").val(rfc);
  $("#nombre").val(name);
  $("#id_factura").val(id);
  $("#monto").val(monto);
  
});

</script>


