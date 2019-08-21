@extends('layout.master')
@section('content')
<div>
	<div class="panel panel-default">
     <div class="panel-heading">Carga de pedimento<a class="btn btn-default btn-xs pull-right" href="{{url('pedimento')}}" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>		
	  <div class="panel-body">

	    	{!! csrf_field() !!}
	  		<div class="col-sm-12">
				<ul  class="nav nav-tabs">
					<li class="active">
						<a  href="#local" data-toggle="tab">Carpeta Local</a>
					</li>
					<li>
						<a href="#ftp" data-toggle="tab">SFTP</a>
					</li>
					<li>
						<a href="#schedule" data-toggle="tab">Programar Ejecución</a>
					</li>
				</ul>

				<div class="tab-content clearfix">
				  	<div class="tab-pane active" id="local">
						@include('pedimento.form-pedimento-local',['url'=>'/programacion_pedimento','method'=>'POST','msg_local'=> $msg_local,'exists' => $exists ])
					</div>
					<div class="tab-pane" id="ftp">
						@include('pedimento.form-pedimento-ftp',['sftp'=>$sftp,'url'=>'/programacion_pedimento','method'=>'POST'])
					</div>
					<div class="tab-pane" id="schedule">
						@include('pedimento.form-pedimento-schedule',['sftp'=>$sftp,'url'=>'/programacion_pedimento','method'=>'POST'])
					</div>					
				</div>
			</div>
							

	  </div>
	</div>	
</div>
@endsection

@push('scripts')
$(document).ready(function(){ 
  $( "#id_expediente" ).select2( { placeholder: "Seleccione un expediente" , maximumSelectionSize: 10 } ); 

});

@endpush