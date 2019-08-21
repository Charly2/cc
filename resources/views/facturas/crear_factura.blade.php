@extends('layout.master')

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Informacion del expediente <a class="btn btn-default btn-xs pull-right" href="{{ url('/facturas/'.$expediente_id) }}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
            <div class="panel-body">
          
                <div class="row">
                    <div class="col-md-12">

                        <a role="button" class="btn btn-primary pull-right"  href="" ><span class="glyphicon glyphicon-plus"></span> Guardar Factura</a>
                        <h4>Facturas</h4>
                        <div class="panel-body">

                            <form action="#" class="bs-example form-horizontal" method="post" accept-charset="utf-8">
                                

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Ref No <span class="text-danger">*</span></label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" value="INV2292" name="reference_no">
                                    </div>

                                </div>



                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Cliente <span class="text-danger">*</span> </label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" value="INV2292" name="reference_no">
                                    </div>
<!--
                                    <a role="button" class="btn btn-primary "  href="" ><span class="glyphicon glyphicon-user"></span> Cliente Nuevo</a>
-->
                                </div>

         

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Impuestos 1</label>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">%</span>
                                            <input class="form-control money" type="text" value="2.00" name="tax">
                                        </div>
                                    </div>
                                </div>

       
     
               

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Moneda</label>
                                    <div class="col-lg-5">
                                        <select name="currency" class="form-control">
                                            <option value="">Moneda del cliente predeterminada</option>
                                                                                          
                                        </select>
                                    </div>
                                </div>

  
                                




                            </form>                                
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
@push('scripts')
  
	$(document).ready(function(){
  

});

 @endpush

   

@endsection