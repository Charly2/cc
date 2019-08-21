@extends('layout.master')
@section('content')
    <div class="row" style="display: flex; justify-content: center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('sfpt.updatePassword') }}" role="form">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Editar contraseña para la conexión al servidor
                        <a href="{{ route('programacion_pedimento.index') }}" class="btn btn-default btn-xs pull-right">
                            <span class="glyphicon glyphicon-arrow-left"></span>
                            Atras
                        </a>
                    </div>
                    <div class="panel-body">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                {!! Form::label('path', 'Contraseña anterior', ['class' => '']) !!}
                                {!! Form::password('old_password', ['class' => 'form-control', 'placeholder' => 'Contraseña anterior']) !!}
                                <span class="bmd-help text-danger">{{ $errors->has('new_password') ? $errors->first('new_password') : '' }}</span>
                            </div>

                            <div class="form-group">
                                {!! Form::label('password', 'Nueva contraseña', ['class' => '']) !!}
                                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Nueva contraseña']) !!}
                                <span class="bmd-help text-danger">{{ $errors->has('password') ? $errors->first('password') : '' }}</span>
                            </div>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-sm btn-success btn-addon"><span class="glyphicon glyphicon-ok"></span> Confirmar</button>
                        <a href="" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection