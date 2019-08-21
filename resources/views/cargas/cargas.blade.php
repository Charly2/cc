@extends('layout.master')
@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Carga de Documentos
                <a class="btn btn-default btn-xs pull-right"
                   @if(isset($_GET['inicio']))
                   href="{{ route('expediente.filtro_expedientes', [
                    'inicio' => $_GET['inicio'],
                    'final' => $_GET['fin']
                   ]) }}"
                   @else
                   href="{{ route('expedientes.index') }}"
                   @endif
                   role="button">
                    <span class="glyphicon glyphicon-arrow-left">
                    </span> Atras
                </a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="glyphicon glyphicon-folder-open"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection