@extends('layout.master')
@section('content')
    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
        <form class="form-horizontal" role="form" method="POST" action="{{ route('expedientes.store') }}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Crear nuevo expediente
                </div>
                <div class="panel-body">

                    <div class="form-group {{ ($errors->has('nombre')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="nombre" required value="{{ old('nombre') }}">
                            <p class="help-block"><strong>{{ ($errors->has('nombre') ? $errors->first('nombre') : '') }}</strong></p>
                        </div>
                    </div>
                
                    <div class="form-group {{ ($errors->has('agente_aduanal')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Agente Aduanal</label>
                        <div class="col-sm-9">
                            <select name="agente_aduanal" id="agente_aduanal" class="form-control select2-choice select2-default">
                                 <option ></option>
                                @foreach($agencias as $agencias)
                                    <option value="{{ $agencias->id }}">{{ $agencias->nombre }}</option>
                                @endforeach
                            </select>
                            <p class="help-block"><strong>{{ ($errors->has('agente_aduanal') ? $errors->first('agente_aduanal') : '') }}</strong></p>
                        </div>
                    </div>

                    <div class="form-group {{ ($errors->has('descripcion')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Descripcion</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
                            <p class="help-block"><strong>{{ ($errors->has('descripcion') ? $errors->first('descripcion') : '') }}</strong></p>
                        </div>
                    </div>
                    {!! csrf_field() !!}
                </div>

                <div class="panel-footer">
                    <button type="submit" class="btn btn-sm btn-success btn-addon"><span class="glyphicon glyphicon-ok"></span> Crear</button>
                    <a href="{{ route('expedientes.index') }}" class="btn btn-danger btn-sm btn-addon"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                </div>
            </div>
        </form>
    </div>

@push('scripts')
$(document).ready(function(){ 
  $( "#agente_aduanal" ).select2( { placeholder: "Seleccione un agente aduanal" , maximumSelectionSize: 10 } ); 

});

@endpush
   
@endsection

  
 