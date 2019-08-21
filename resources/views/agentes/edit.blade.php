@extends('layout.master')
@section('content')
    <div class="col-md-12">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/agenteaduanal/update') }}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Registrar Agencia Aduanal
                </div>
                <div class="panel-body">

                    <div class="form-group {{ ($errors->has('nombre_agencia')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="nombre_agencia" required value="{{ old('nombre_agencia',$agencia->nombre) }}">
                            <p class="help-block"><strong>{{ ($errors->has('nombre_agencia') ? $errors->first('nombre_agencia') : '') }}</strong></p>
                        </div>
                    </div>



                    <div class="form-group {{ ($errors->has('rfc')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">RFC</label>
                        <div class="col-sm-9">
                            <input disabled class="form-control" name="rfc" required value="{{ old('rfc',$agencia->rfc) }}">
                            <p class="help-block"><strong>{{ ($errors->has('rfc') ? $errors->first('rfc') : '') }}</strong></p>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="{{$agencia->id}}">
                    {!! csrf_field() !!}
                </div>

                <div class="panel-footer">
                    <button type="submit" class="btn btn-sm btn-success btn-addon"><span class="glyphicon glyphicon-ok"></span> Actualizar</button>
                    <a href="{{ url('/agenteaduanal') }}" class="btn btn-danger btn-sm btn-addon"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
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


