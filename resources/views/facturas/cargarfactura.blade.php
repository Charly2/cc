@extends('layout.master')
@section('head')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
@stop

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Informaci&oacute;n del expediente <a class="btn btn-default btn-xs pull-right" href="{{ url('/facturas/'.$expediente_id) }}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atr&aacute;s</a></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Facturas</h4>
                        <div class="panel-body">

                            <form method="POST" action="{{ url('uploadFactura',['id_empresa'=> Session::get('id')]) }}" enctype="multipart/form-data">

                                {!! csrf_field() !!}

                                <button id="submit" class="btn btn-primary pull-right" ><span class="glyphicon glyphicon-plus"></span>  Subir todas las Facturas</button>

                                <div class="form-group {{ ($errors->has('factura_xml')) ? 'has-error' : '' }}">
                                    <div class="col-xs-8">
                                        <label for="factura_xml">Archivo XML</label>
                                        <input type="file" name="factura_xml" id="factura_xml">
                                        <p class="help-block"><strong>{{ ($errors->has('factura_xml') ? $errors->first('factura_xml') : '') }}</strong></p>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('factura_pdf')) ? 'has-error' : '' }}" >
                                    <div class="col-xs-9">
                                        <label for="factura_pdf">Archivo PDF</label>
                                        <input type="file" name="factura_pdf" id="factura_pdf" >
                                    </div>
                                </div>
                                <input type="hidden" name="tipo_factura" value="{{$tipo_factura}}">
                                <input type="hidden" name="expediente_id" value="{{$expediente_id}}">
                            </form>

                        </div>
                    
                    <span id="response" style="color: red;">
                      <ul></ul>
                    </span>
                </div>

            </div>
        </div>
    </div>






 @endsection
@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>

@stop
@push('scripts')


    $(document).ready(function () {
    });



 @endpush



