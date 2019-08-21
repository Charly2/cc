@extends('layout.master')

@section('content')
    <div style="display: flex; justify-content: center">
        <div class="col-md-10" >
            <div class="panel panel-default">
                <div class="panel-heading">Archivos subidos al sftp<a href="{{ url()->previous() }}" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Expedientes:</p></div>
                        <div class="col-md-6">{{ $num_expedientes }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Sub carpetas:</p></div>
                        <div class="col-md-6">{{ $num_subcarpetas }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Archivos correctos:</p></div>
                        <div class="col-md-6">{{ $num_archivos_ok }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Archivos pedimentos(m):</p></div>
                        <div class="col-md-6">{{ $num_archivos_m }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Archivos pedimentos(pdf):</p></div>
                        <div class="col-md-6">{{ $num_archivos_m_pdf }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Archivos Cove:</p></div>
                        <div class="col-md-6">{{ $num_archivos_cove }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Archivos Cove(pdf):</p></div>
                        <div class="col-md-6">{{ $num_archivos_cove_pdf }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Archivos Cove(match):</p></div>
                        <div class="col-md-6">{{ $num_archivos_cove_match}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><p class="pull-right">Archivos incorrectos:</p></div>
                        <div class="col-md-6">{{ $num_archivos_fail }}</div>
                    </div>
                    @if($num_archivos_fail > 0)
                        <hr>

                        @foreach($errores as $key => $error)
                            <div class="row">
                                <div class="col-md-3"><p class="pull-right"><strong>Error {{ $key + 1 }}</strong></p></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><p class="pull-right">Metadata:</p></div>
                                <div class="col-md-9">{{ $error['metadata']}}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection