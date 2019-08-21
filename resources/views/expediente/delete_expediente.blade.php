   <form action="{{url('/delete_expediente',['action' => 'delete','id'=>$expediente->id]) }}" method="get" accept-charset="utf-8">
            
{!! csrf_field() !!}

   <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">¿Esta seguro de borrar el Expediente?</h4>
                </div>
            
                <div class="modal-body">
                    <p>Se borrará todo lo relacionado al expediente.</p>
                    <p>{{$expediente->expediente}}</p>
                    <p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                   
                    <button type="submit" class="btn btn-danger">Borrar</button>
                </div>
            </div>
        </div>
    </div>

</form>
      
 
