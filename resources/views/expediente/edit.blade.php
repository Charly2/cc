@extends('layout.master')
@section('content')
    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
        @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button> <strong>{{ Session::get('message') }}</strong>
        </div>
        @endif
        <form class="form-horizontal" role="form" method="POST" action="{{ route('expedientes.update',['id' => $expediente->id]) }}">
            <input type="hidden" name="_method" value="PATCH">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Editar expediente: {{$expediente->expediente}}
                    <a class="btn btn-default btn-xs pull-right" href="{{ route('expedientes.index') }}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a>
                </div>
                <div class="panel-body">

                    <div class="form-group {{ ($errors->has('nombre')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="nombre" required value="{{ $expediente->nombre }}">
                            <p class="help-block"><strong>{{ ($errors->has('nombre') ? $errors->first('nombre') : '') }}</strong></p>
                        </div>
                    </div>

                    <div class="form-group {{ ($errors->has('descripcion')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Descripción</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="descripcion" rows="3" required>{{ $expediente->descripcion }}</textarea>
                            <p class="help-block"><strong>{{ ($errors->has('descripcion') ? $errors->first('descripcion') : '') }}</strong></p>
                        </div>
                    </div>

                    <div class="form-group {{ ($errors->has('descripcion')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Fecha</label>
                        <div class="col-sm-9">
                            <label class="form-control" name="fecha" rows="3">{{ date('d-m-Y', strtotime($expediente->created_at)) }}</label>
                            <p class="help-block"><strong>{{ ($errors->has('descripcion') ? $errors->first('descripcion') : '') }}</strong></p>
                        </div>
                    </div>

                    <div class="form-group {{ ($errors->has('aduana')) ? 'has-error' : '' }}">
                        <label class="col-sm-3 control-label">Agente aduanal</label>
                        <div class="col-sm-9">
                            <select name="aduana" id="aduana" class="form-control">
                                @foreach($agencias as $row)
                                    <option value="{{ $row->id  }}" selected = "{{ $expediente['agente_aduanal'] == $row->id ? 'true'  : 'false' }}">{{ $row->nombre }} </option>
                                @endforeach
                            </select>
                            <p class="help-block"><strong>{{ ($errors->has('aduana') ? $errors->first('aduana') : '') }}</strong></p>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label class="col-sm-3 control-label">Estatus</label>
                        <div class="col-sm-9">
                            <select name="status" id="status" class="form-control">
                                <option value='{{$expediente->status}}'>{{$expediente->status}}</option>
                                @php
                                    
                                    $options = array('Abierto' => 'Abierto',
                                                     'Cerrado' => 'Cerrado',
                                                     'Proceso' => 'Proceso' );

                                @endphp
                                @foreach ($options as $row)
                                    <option value='{{$row}}'>{{$row}}</option>
                                @endforeach
                               
                            </select>
                          
                        </div>
                    </div>

                    {!! csrf_field() !!}
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-sm btn-success btn-addon"><span class="glyphicon glyphicon-ok"></span> Actualizar</button>
                    <a href="{{ route('expedientes.index') }}" class="btn btn-danger btn-sm btn-addon"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                </div>
            </div>
        </form>
    </div>
 
@push('scripts')
$(document).ready(function(){
  $( "#aduana" ).select2( { placeholder: "Seleccione una aduana" , maximumSelectionSize: 10 } );
  $( "#agente_aduanal" ).select2( { placeholder: "Seleccione un agente aduanal" , maximumSelectionSize: 10 } );
  $( "#select2-aduana-container" ).html("{{$agente[0]}}");
});

@endpush  
@endsection