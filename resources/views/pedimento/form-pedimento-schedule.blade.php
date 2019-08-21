{!! Form::open(['url'=> $url ,'method' => $method,'class'=> '']) !!}

	@if (Session::has('info'))
	<strong>{{ Session::get('info') }}</strong>

	@endif
	<div class="col-sm-12">
		<div class="col-sm-5">
			<br>
			<div class="form-group">
				{!! Form::label('host', 'Host',['class'=>'']) !!}
				{!! Form::text('host','$sftp->host', ['class'=> 'form-control','placeholder'=>'Host']) !!}
				<span class="bmd-help text-danger"> {{ $errors->has('host') ? $errors->first('host') : '' }} </span>
			</div>

			<div class="form-group">
				{!! Form::label('user', 'Usuario',['class'=>'']) !!}
			  	{!! Form::text('user','$sftp->user',['class'=> 'form-control','placeholder'=>'Usuario']) !!}
			  	<span class="bmd-help text-danger"> {{ $errors->has('user') ? $errors->first('user') : '' }} </span>
			</div>		

			<div class="form-group">
				{!! Form::label('password', 'Password',['class'=>'']) !!}
			  	{!! Form::password('password',['class'=> 'form-control']) !!}

			  	<span class="bmd-help text-danger"> {{ $errors->has('password') ? $errors->first('password') : '' }} </span>
			</div>		

			<div class="form-group">
				{!! Form::label('path', 'Ruta de la Carpeta',['class'=>'']) !!}
			  	{!! Form::text('path','$sftp->password',['class'=> 'form-control','placeholder'=>'Ruta de la Carpeta']) !!}
				
			  	<span class="bmd-help text-danger"> {{ $errors->has('path') ? $errors->first('email') : '' }} </span>
			<aside>Agregar la ruta de la carpeta donde se encuentra los Pedimentos.
				<br> Ejemplo: (/usr/local/pedimentos/) .<br></aside>
		    <aside>En caso que no tenga carpeta dejar en blanco.<br> </aside>
			</div>	


			<div class="form-group text-left">
				
				<button type="submit" class="btn btn-primary">Subir</button>
			</div>	
		</div>

	</div>

{!! Form::close() !!}