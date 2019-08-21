@extends('layout.master')

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Carga Coves y Pedimentos
                <a class="btn btn-default btn-xs pull-right" href="{{ url('/expedientes/'.$id_expediente) }}" role="button">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                    Atras
                </a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 {{ (!empty($operacion_coves)) ? 'alert-success' : '' }}">
                        <form method="POST" action="{{ url('/upload_cove',['empresa'=> Session::get('id')]) }}" enctype="multipart/form-data">
                            <input type="hidden" name="expediente" id="expediente" value="{{ $id_expediente }}" />
                            <h4>Cove</h4>
                            <strong>{{ (!empty($operacion_coves)) ? $operacion_coves['mensaje'] : '' }}</strong>
                            {!! csrf_field() !!}
                            <div class="form-group" >
                                <div class="col-xs-8 {{ ($errors->has('num_cove')) ? 'alert-danger' : '' }}" style="padding:10px;">
                                    <label for="num_cove">N° COVE</label>
                                    <input class="form-control" id="num_cove" name="num_cove" type="text" value="" required>
                                    <strong>{{ ($errors->has('num_cove')) ? $errors->first('num_cove') : '' }}</strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-8 {{ ($errors->has('cove_xml')) ? 'alert-danger' : '' }}" style="padding:10px;">
                                    <label for="cove_xml">Archivo XML</label>
                                    <input type="file" name="cove_xml" id="cove_xml">
                                    <strong>{{ ($errors->has('cove_xml')) ? $errors->first('cove_xml') : '' }}</strong>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-8 {{ ($errors->has('cove_pdf')) ? 'alert-danger' : '' }}" style="padding:10px;">
                                    <label for="cove_pdf">Archivo PDF</label>
                                    <input type="file" name="cove_pdf" id="cove_pdf">
                                    <strong>{{ ($errors->has('cove_pdf')) ? $errors->first('cove_pdf') : '' }}</strong>
                                </div>
                            </div>

                            <button id="submit" class="btn btn-primary pull-right">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="glyphicon glyphicon-plus"></span>
                                Subir Cove
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            </button>
                        </form>
                    </div>

                    <div class="col-md-12 {{ (!empty($operacion_pedimento)) ? 'alert_success' : '' }} ">
                        <form method="POST" action="{{url('/upload_pedimento',['empresa' => Session::get('id')])}}" enctype="multipart/form-data">
                            <input type="hidden" name="expediente" id="expediente" value="{{ $id_expediente }}" />
                            <h4>Pedimento</h4>
                            {!! csrf_field() !!}
                            <strong>{{ (!empty($operacion_pedimento)) ? $operacion_pedimento['mensaje'] : '' }}</strong>
                            <div class="form-group">
                                <div class="col-xs-8 {{ ($errors->has('pedim_file_m')) ? 'alert-danger' : '' }}" style="padding:10px;">
                                    <label for="pedim_file_m">Archivos M</label>
                                    <input type="file" name="pedim_file_m" id="pedim_file_m" >
                                    <strong>{{ ($errors->has('pedim_file_m')) ? $errors->first('pedim_file_m') : '' }}</strong>
                                </div>
                            </div>
                            <div class="form-group" >
                                <div class="col-xs-8 {{ ($errors->has('pedim_file_pdf')) ? 'alert-danger' : '' }}" style="padding:10px;">
                                    <label for="pedim_file_pdf">Archivos PDF</label>
                                    <input type="file" name="pedim_file_pdf" id="pedim_file_pdf">
                                    <strong>{{ ($errors->has('pedim_file_pdf')) ? $errors->first('pedim_file_pdf') : '' }}</strong>
                                </div>
                                <button id="submit" class="btn btn-primary pull-right">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    Subir Pedimento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    $(document).ready(function () {
        console.log('funcion');
        $('.col-md-12').animate({backgroundColor:'#fff'},5000);
        $('.col-md-12').find('h4').animate({color:'#000'},5000);
        $('.col-xs-8').animate({backgroundColor:'#fff'},5000);
        $('.col-xs-8').find('label').animate({color:'#000'},5000);
        $('.col-xs-8').find('input').animate({color:'#000'},5000);
    });
@endpush