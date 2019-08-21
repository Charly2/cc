@extends('layout.master')
@section('content')
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Carga de archivos cove(s)
                <a class="btn btn-default btn-xs pull-right" href="{{url('coves',['id_empresa'=> Session::get('id')])}}" role="button">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                    Atras
                </a>
            </div>
            <div class="panel-body">
                @if(Session::has('message'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button> <strong>{{ Session::get('message') }}</strong>
                </div>
                @endif
                <form method="POST" action="{{ url('upload_cove',['id_empresa'=> Session::get('id')]) }}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-plus"></span> Subir Cove</button>
                        <div class="col-sm-6">
                            {!! csrf_field() !!}
                            <div class="form-group {{ ($errors->has('num_cove')) ? 'has-error' : '' }}">
                                <div class="col-xs-7">
                                    <label for="num_cove">N° COVE</label>
                                    <input class="form-control" id="num_cove" name="num_cove" type="text" value="" required>
                                    <p class="help-block"><strong>{{ ($errors->has('num_cove') ? $errors->first('num_cove') : '') }}</strong></p>
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('cove_xml')) ? 'has-error' : '' }}">
                                <div class="col-xs-8">
                                    <label for="cove_xml">Archivo xml</label>
                                    <input type="file" name="cove_xml" id="cove_xml">
                                    <p class="help-block"><strong>{{ ($errors->has('cove_xml') ? $errors->first('cove_xml') : '') }}</strong></p>
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('cove_pdf')) ? 'has-error' : '' }}" >
                                <div class="col-xs-9">
                                    <label for="cove-pdf">Archivo PDF</label>
                                    <input type="file" name="cove_pdf" id="cove_pdf" >
                                    <p class="help-block"><strong>{{ ($errors->has('cove_pdf') ? $errors->first('cove_pdf') : '') }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
$(document).ready(function(){ 
  $( "#id_expediente" ).select2( { placeholder: "Seleccione un expediente" , maximumSelectionSize: 10 } );
});
@endpush