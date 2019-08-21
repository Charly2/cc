@extends('layout.master')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Editar conexión SFTP
            <a href="{{ route('programacion_pedimento.index') }}" class="btn btn-default btn-xs pull-right" role="button">
                <span class="glyphicon glyphicon-arrow-left"></span>
                Atras
            </a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['url' => $url, 'method' => $method, 'class' => '']) !!}
                    <div class="form-group">
                        {!! Form::label('host', 'Host', ['class' => '']) !!}
                        {!! Form::text('host', $sftp->host, ['class' => 'form-control', 'placeholder' => 'Host']) !!}
                        <span class="bmd-help text-danger">{{ $errors->has('host') ? $errors->first('host') : '' }}</span>
                    </div>

                    <div class="form-group">
                        {!! Form::label('user', 'Usuario', ['class' => '']) !!}
                        {!! Form::text('user', $sftp->user, ['class' => 'form-control', 'placeholder' => 'Usuario']) !!}
                        <span class="bmd-help text-danger">{{ $errors->has('user') ? $errors->first('user') : '' }}</span>
                    </div>


                    <div class="form-group">
                        {!! Form::label('path', 'Ruta de la carpeta', ['class' => '']) !!}
                        {!! Form::text('path', $sftp->path, ['class' => 'form-control', 'placeholder' => 'Ruta de la carpeta']) !!}
                        <span class="bmd-help text-danger">{{ $errors->has('path') ? $errors->first('path') : '' }}</span>
                    </div>

                    <p>Ejemplo de ruta:</p>
                    <p>/carpeta/carpeta</p>

                    <a href="{{ route('sftp.changePassword') }}">Cambiar contraseña para la conexión con el servidor</a>
                    <br>
                    <br>

                    <div class="form-group">
                        {!! Form::hidden('id_empresa', Session::get('id')) !!}
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection