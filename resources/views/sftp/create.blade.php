@extends('layout.master')
@section('content')
    <div class="row" style="display: flex; justify-content: center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('programacion_pedimento.store') }}" role="form">
                {!! csrf_field() !!}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Datos para la conexión a un SFTP
                        <a href="{{ route('programacion_pedimento.index') }}" class="btn btn-default btn-xs pull-right" role="button">
                            <span class="glyphicon glyphicon-arrow-left"></span>
                            Atras
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-3 control-label">Host</div>
                            <div class="col-sm-9">
                                <input type="text" name="host" class="form-control" value="{{ old('host') }}" required>
                                <p class="help-block"><strong>{{ ($errors->has('host') ? $errors->first('host') : '') }}</strong></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">Usuario</div>
                            <div class="col-sm-9">
                                <input type="text" name="user" class="form-control" value="{{ old('user') }}" required>
                                <p class="help-block"><strong>{{ ($errors->has('user') ? $errors->first('user') : '') }}</strong></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">Contraseña</div>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control" required>
                                <p class="help-block"><strong>{{ ($errors->has('password') ? $errors->first('password') : '') }}</strong></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">Ruta de archivos</div>
                            <div class="col-sm-9">
                                <input type="text" name="path" class="form-control">
                                <p class="help-block"><strong>{{ ($errors->has('path') ? $errors->first('path') : '') }}</strong></p>
                            </div>
                        </div>

                        <p>Ejemplo de ruta:</p>
                        <p>/carpeta/carpeta</p>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-sm btn-success btn-addon"><span class="glyphicon glyphicon-ok"></span> Crear</button>
                        <a href="" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection