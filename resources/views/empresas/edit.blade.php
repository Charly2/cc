@extends('layout.master')
@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ route('empresas.update', ['id' => $empresa->id]) }}">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="PATCH">
        <div class="panel panel-default">
            <div class="panel-heading">
                Editar empresa
            </div>
            <div class="panel-body">
                <div class="form-group {{ ($errors->has('rfc')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">RFC empresa</label>
                    <div class="col-sm-10">
                        <input type="text" name="rfc" class="form-control" value="{{ old('rfc') }}">
                        <p class="help-block"><strong>{{ ($errors->has('rfc') ? $errors->first('rfc') : '') }}</strong></p>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('nombre')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Nombre</label>
                    <div class="col-sm-10">
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
                        <p class="help-block"><strong>{{ ($errors->has('nombre') ? $errors->first('nombre') : '') }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-primary btn-addon"><span class="glyphicon glyphicon-ok"></span> Actualizar</button>
                <a href="{{ route('empresas.index') }}" class="btn btn-default btn-sm btn-addon"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
            </div>
        </div>
    </form>
@endsection