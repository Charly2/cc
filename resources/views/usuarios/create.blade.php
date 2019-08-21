@extends('layout.master')
@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ route('usuarios.store') }}">
        {!! csrf_field() !!}
        <div class="panel panel-default">
            <div class="panel-heading">
                Crear un nuevo usuario
            </div>
            <div class="panel-body">
                <div class="form-group {{ ($errors->has('username')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Nombre de usuario</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                        <p class="help-block"><strong>{{ ($errors->has('username') ? $errors->first('username') : '') }}</strong></p>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        <p class="help-block"><strong>{{ ($errors->has('email') ? $errors->first('email') : '') }}</strong></p>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" value="" required>
                        <p class="help-block"><strong>{{ ($errors->has('password') ? $errors->first('password') : '') }}</strong></p>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('usertype_id')) ? 'has-error' : '' }}">
                    <label class="col-sm-2 control-label">Tipo usuario</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="usertype_id" id="usertype_id">
                                 <option value="0">Seleccione una opción</option>
                                @foreach($usertype as $usertype)
                                    <option value="{{ $usertype->id }}">{{ $usertype->usertype }}</option>
                                @endforeach
                        </select>
                        <p class="help-block"><strong>{{ ($errors->has('usertype_id') ? $errors->first('usertype_id') : '') }}</strong></p>
                        <p class="help-block" style="color:#a94442;" ><strong>{{ ($errors->has('empresa') ? $errors->first('empresa') : '') }}</strong></p>
                        <p class="help-block" style="color:#a94442;"><strong>{{ ($errors->has('agente') ? $errors->first('agente') : '') }}</strong></p>
                    </div>
                </div>

                <div id="select-agente" style="display: none;">
                    <div class="form-group {{ ($errors->has('agente')) ? 'has-error' : '' }}">
                        <label class="col-sm-2 control-label">Agente</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="agente" id="agente">
                                     <option value="0">Seleccione un Agente</option>
                                        @foreach($agentes as $row)
                                            <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                        @endforeach  
                            </select>
                        </div>
                    </div>
                </div>

                <div id="select-empresa" style="display: none;">
                    <div class="form-group {{ ($errors->has('empresa')) ? 'has-error' : '' }}">
                        <label class="col-sm-2 control-label">Empresa</label>
                        <div class="col-sm-10" id="select_empresa_new_user">
                            <select class="form-control select2-default" name="empresa" id="empresa">
                                     <option value="0">Seleccione una Empresa</option>
                                        @foreach($empresas as $row)
                                            <option value="{{ $row->id }}">{{ $row->nombre }}</option>
                                        @endforeach          
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-success btn-addon"><span class="glyphicon glyphicon-ok"></span> Crear</button>
                <a href="{{ url('/usuarios') }}" class="btn btn-default btn-sm btn-addon"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
            </div>
        </div>
    </form>
@endsection 



@push('scripts')
    <script></script>
    <script type="text/javascript" src="{{ URL::asset('js/usuario.js') }}"></script>
@endpush