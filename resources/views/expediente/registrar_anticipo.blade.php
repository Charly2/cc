<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form" method="POST" action="{{ route('expediente.anticipo.register.r') }}">
             {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Anticipo</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12"> 
                        <div class="col-md-6">    
                            <div class="form-group">                        
                                <label for="monto">Monto</label>                
                                <input type="text" class="form-control" id="monto" placeholder="Monto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">                        
                                <label for="rfc">RFC</label>                        
                                <input type="text" class="form-control" id="rfc" placeholder="RFC">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="agente_aduanal">Agente aduanal</label>
                            <input type="text" class="form-control" id="agente_aduanal" placeholder="Agente aduanal">
                        </div>
                        <div class="form-group">
                            <label for="forma_pago">Forma de pago</label>
                            <select id="forma_pago" name="forma_pago" class="form-control">
                                <option value="1">EFECTIVO</option>
                                <option value="2">CHEQUE</option>
                                <option value="3">TRASNFERENCIA</option>
                                <option value="4">OTRO</option>
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