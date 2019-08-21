{!! Form::open(['url'=> $url ,'method' => $method,'class'=> '']) !!}
	<div class="col-sm-12">
		<div class="col-sm-12">
			<br> 
			@if($msg_local)
			      <div class="alert alert-info" role="alert">{{ $msg_local }}</div>
			@endif

			@if(!$exists)
				<div class="form-group text-left">
					{!! Form::hidden('accion', 'local') !!}
					<button type="submit" class="btn btn-primary">Crear Carpeta</button>
				</div>
			@endif
	
		</div>

	</div>

{!! Form::close() !!}