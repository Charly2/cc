<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form" method="POST" action="{{ route('movimientos.store') }}">
             {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Anticipo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">    
                            <div class="form-group">                        
                                <label for="monto">Monto</label>                
                                 <input type="text" class="form-control" id="monto" placeholder="Monto" required="true" name="monto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">                        
                                <label for="rfc">RFC</label>                        
                                <input type="text" class="form-control" id="rfc" name="rfc" placeholder="RFC" required="true">
                                <input type="hidden" name="id" id="id" value="{{$id}}">
                                <input type="hidden" name="tipoPago" id="tipoPago" value=2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="agente_aduanal">Agente aduanal</label>
                            
                         <input type="text" name="agente_aduanal" class="form-control" value="{{ $agencia['nombre'] }}" required readonly="true">

                        </div>
                        <div class="form-group">
                            <label for="forma_pago">Forma de pago</label>
                            <select id="forma_pago" name="forma_pago" class="form-control">

                                @foreach($tipoPago as $pago)
                                    <option value="{{ $pago->id }}">{{ $pago->nombreTipo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Registrar Anticipo</button>
                </div>
            </form>
        </div>
    </div>
</div> 