@extends('layout.master')
@section('content')
    <pre>
        {{$sftp}}
    </pre>
    <div>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <div class="panel panel-default">
            <div class="panel-heading">
                Carga de archivos del servidor SFTP
                <a href="{{ url('pedimento') }}" class="btn btn-default btn-xs pull-right" role="button">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                    Atras
                </a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        @if(isset($sftp))
                        <table id="myTable" class="table table-striped table-bordered dataTable table-responsive">
                            <thead>
                                <tr>
                                    <th>Host</th>
                                    <th>Usuario</th>
                                    <th>Ruta</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $sftp->host }}</td>
                                    <td>{{ $sftp->user }}</td>
                                    <td>{{ $sftp->path }}</td>
                                    <td>{{ $sftp->created_at }}</td>
                                    <td>
                                        <a href="{{ route('programacion_pedimento.edit', ['id' => $sftp->id]) }}" class="btn btn-default btn-sm" role="button" title="Editar conexión"><span class="glyphicon glyphicon-pencil"></span></a>
                                        {{--<a href="{{ route('SFTP_download') }}" class="btn btn-default btn-sm" role="button" title="Descargar archivos"><span class="glyphicon glyphicon-arrow-down"></span></a>--}}
                                        <button data-url="{{ url('') }}" class="btn btn-default btn-sm button-download" title="Descargar archivos"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Estado de la descarga:</div>
            <div class="panel-body">
                <strong id="status_download"></strong><br>
                <strong id="status_process"></strong>
                <div class="progress">
                    <div id="progress_bar_download" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="sr-only">45% Complete</span>
                    </div>
                </div>
                <div id="download_sftp" class="card-info-container" style="display: none">
                {{--<div id="download_sftp" class="card-info-container">--}}
                    <div class="card-info" style="background-color: #3498db">
                        <div class="">Archivos procesados:</div>
                        <div id="download_total" class=""></div>
                    </div>
                    <div class="card-info" style="background-color: #27ae60">
                        <div class="">Pedimentos(xml):</div>
                        <div id="download_m" class=""></div>
                    </div>
                    <div class="card-info" style="background-color: #e74c3c">
                        <div class="">Pedimentos(pdf):</div>
                        <div id="download_m_pdf" class=""></div>
                    </div>
                    <div class="card-info" style="background-color: #27ae60">
                        <div class="">Coves(xml):</div>
                        <div id="download_cove" class=""></div>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-warning" role="alert"><strong>{{$errors->first()}}</strong></div>
        @endif
    </div>
    <script type="text/javascript" src="{{ URL::asset('js/download.js') }}"></script>
@endsection