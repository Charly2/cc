{!! Form::open(['url'=> $url ,'method' => $method,'class'=> '']) !!}

	<div class="col-sm-12">
		<div class="col-sm-5">
			<br>
			<div class="form-group">
				{!! Form::label('host', 'Host',['class'=>'']) !!}
				{!! Form::text('host', $sftp->host, ['class'=> 'form-control','placeholder'=>'Host']) !!}
				<span class="bmd-help text-danger"> {{ $errors->has('host') ? $errors->first('host') : '' }} </span>
			</div>

			<div class="form-group">
				{!! Form::label('user', 'Usuario',['class'=>'']) !!}
			  	{!! Form::text('user', $sftp->user,['class'=> 'form-control','placeholder'=>'Usuario']) !!}
			  	<span class="bmd-help text-danger"> {{ $errors->has('user') ? $errors->first('user') : '' }} </span>
			</div>		

			<div class="form-group">
				{!! Form::label('password', 'Contraseña',['class'=>'']) !!}
			  	{!! Form::text('password', $sftp->password, ['class'=> 'form-control', 'placeholder'=>'Contraseña']) !!}
			  	<span class="bmd-help text-danger"> {{ $errors->has('password') ? $errors->first('password') : '' }} </span>
			</div>		

			<div class="form-group">
				{!! Form::label('path', 'Ruta de la carpeta',['class'=>'']) !!}
			  	{!! Form::text('path', $sftp->path,['class'=> 'form-control','placeholder'=>'Ruta de la carpeta']) !!}
			  	<span class="bmd-help text-danger"> {{ $errors->has('path') ? $errors->first('path') : '' }} </span>

				<aside>Agregar la ruta de la carpeta donde se encuentra los archivos.
				<br> Ejemplo: (/usr/local/carpeta_archivos) .<br></aside>
		    	<aside>En caso que no tenga carpeta dejar en blanco.<br> </aside>
			</div>	

			<div class="form-group text-left">
				{!! Form::hidden('accion', 'sftp') !!}
				{!! Form::hidden('id_empresa', Session::get('id')) !!}
				<button type="submit" class="btn btn-primary">Actualizar</button>
			</div>

            <a role="button" href="{{ url('job_pedimento_ftp') }}" class="btn btn-primary">Cargar archivos</a>
		</div>
	</div>

{!! Form::close() !!}