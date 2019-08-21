@extends('layout.master')
@section('content')
<div>
	<div class="panel panel-default">
		<div class="panel-heading">
			Carga de pedimento
			{{--<a class="btn btn-default btn-xs pull-right" href="{{url('coves',['id_empresa'=> Session::get('id')])}}" role="button">--}}
			<a class="btn btn-default btn-xs pull-right" href="{{url('pedimento')}}" role="button">
				<span class="glyphicon glyphicon-arrow-left"></span>
				Atras
			</a>
		</div>
		<div class="panel-body">
			<form method="POST" action="{{ url('upload_pedimento',['id_expediente'=> Session::get('id')]) }}" enctype="multipart/form-data">
				<div class="col-sm-12">
					<div class="col-sm-6">
					{!! csrf_field() !!}
                        <div class="form-group {{ ($errors->has('pedim_file_m')) ? 'has-error' : '' }}">
							<div class="col-xs-8">
								<label for="pedim_file_m">Archivo M</label>
								<input type="file" name="pedim_file_m" id="pedim_file_m">
								<p class="help-block">
									<strong class="text-danger">
										{{ ($errors->has('pedim_file_m') ? $errors->first('pedim_file_m') : '') }}
										{{ ($errors->has('filem') ? $errors->first('filem') : '') }}
									</strong>
								</p>

							</div>
						</div>
					</div>
					<div class="col-sm-7">
						{!! csrf_field() !!}

						<div class="form-group {{ ($errors->has('pedim_file_pdf')) ? 'has-error' : '' }}">
							<div class="col-xs-9">
								<label for="pedim_file_pdf">Archivo PDF</label>
								<input type="file" name="pedim_file_pdf" id="pedim_file_pdf">
								<p class="help-block"><strong>{{ ($errors->has('pedim_file_pdf') ? $errors->first('pedim_file_pdf') : '') }}</strong></p>
							</div>
						</div>
					</div>
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-plus"></span>
                        Subir Pedimento
                    </button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
$(document).ready(function(){ 
	$( "#id_expediente" ).select2( { placeholder: "Seleccione un expediente" , maximumSelectionSize: 10 } );
});

@endpush