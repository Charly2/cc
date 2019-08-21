@extends('layout.master')
@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ route('usuarios.update', ['id' => $usuario->id]) }}">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="PATCH">
        <div class="panel panel-default">
            <div class="panel-heading">
                Editar usuario
            </div>
            <div class="panel-body">
                <div class="form-group {{ ($errors->has('username')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Nombre de usuario</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" class="form-control" value="{{ old('username') ?: $usuario->username }}" required>
                        <p class="help-block"><strong>{{ ($errors->has('username') ? $errors->first('username') : '') }}</strong></p>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" value="{{ old('email') ?: $usuario->email }}" required>
                        <p class="help-block"><strong>{{ ($errors->has('email') ? $errors->first('email') : '') }}</strong></p>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" value="">
                        <p class="help-block"><strong>{{ ($errors->has('password') ? $errors->first('password') : '') }}</strong></p>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('usertype_id')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Tipo usuario</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="usertype_id" id="usertype_id">
                                <option value="{{ $usuario->usertype_id }}">{{ $usertype[$usuario->usertype_id-1]['usertype'] }}</option>
                                @foreach($usertype as $usertype)
                                   <option value="{{ $usertype->id }}">{{ $usertype->usertype }}</option>
                                @endforeach
                        </select>
                        <p class="help-block"><strong>{{ ($errors->has('usertype_id') ? $errors->first('usertype_id') : '') }}</strong></p>
                    </div>
                </div>

                <div id="select-empresa">
                    <div class="form-group" {{ ($errors->has('empresa')) ? 'has-error' : '' }}>
                        <label class="col-sm-2 control-label">Empresa</label>
                        <div class="col-sm-10" id="select_empresa_new_user">
                            <select name="empresa" id="empresa" class="form-control select2-default">
                                <option value="0">Seleccione una Empresa</option>
                                @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-primary btn-addon"><span class="glyphicon glyphicon-ok"></span> Actualizar</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-default btn-sm btn-addon"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script></script>
    <script type="text/javascript" src="{{ URL::asset('js/usuario.js') }}"></script>
@endpush
