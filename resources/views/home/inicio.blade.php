@extends('layout.master')

@section('head')
<link rel="stylesheet" type="text/css" href="/css/style.css"/>  
@endsection

@section('content')

	<div class="row">
		<div class="col-md-12 row-cont-info">
			<div onclick="location.href='{{url('pedimento')}}' " id="sensor_pedimentos" onmouseover="destaca('home_boton_pedimentos');" onmouseleave="restaura();"></div>
			<img id="maincircle" src="img/circulo/circulo.png" class="circulo img-responsive center-block">
			<div onclick="location.href='{{route('expedientes.index')}}' " id="sensor_expediente" onmouseover="destaca('home_boton_expediente');" onmouseleave="restaura();"></div>
            <div onclick="location.href='{{route('expedientes.index')}}' " id="sensor_importacion" onmouseover="destaca('home_boton_importacion');" onmouseleave="restaura();"></div>
            <div onclick="location.href='{{url('agentes')}}' " id="sensor_agentes" onmouseover="destaca('home_boton_agentes');" onmouseleave="restaura();"></div>
		</div>
	</div>


<!--Empieza Slider-->
	<div id="carousel-example" class="carousel slide" data-ride="carousel">
	    <!-- Wrapper for slides -->
	    <div class="row">
	        <div class="col-xs-offset-3 col-xs-6 col-md-10 col-md-offset-1">
	            <div class="carousel-inner">
	                <div class="item active">
	                    <div class="carousel-content">
	                        <div>
	                            <h3 class="welcome_title">Bienvenid@ a Customs &amp; Trade</h3>
	                            <p class="welcome_paragrph">
	                            	Customs & Trade tiene acceso permanente a las distintas plataformas del SAT/Aduanas que permite conocer en tiempo real la información de pedimentos, pagos y tráfico obteniendo  así la totalidad de los campos de archivos que contienen un pedimento de importación.
	                            </p>
	                        </div>
	                    </div>
	                </div>
	                <div class="item">
	                    <div class="carousel-content">
	                        <div>
	                            <h3 class="welcome_title">Bienvenid@ a Customs & Trade</h3>
	                            <p class="welcome_paragrph">
	                            	Customs & Trade verifica más de 800 reglas, tanto las proporcionadas por la plataforma  SAT/Aduanas como reglas creadas especialmente por nuestro servicio.
	                            </p>
	                        </div>
	                    </div>
	                </div>
	                <div class="item">
	                    <div class="carousel-content">
	                        <div>
	                            <h3 class="welcome_title">Bienvenid@ a CPA Risk3</h3>                            
	                            <p class="welcome_paragrph">
	                            	CPA Risk evalúa y reporta los posibles riesgos de incumplimiento en la documentación solicitada por entidades gubernamentales.Detecta y notifica de posibles riesgos en transacciones financieras y contables.
	                            </p>
	                        </div>
	                    </div>
	                </div>
	                
	            </div>
	        </div>
	    </div>
	    <!-- Controls --> 
	    <a class="left carousel-control" href="#carousel-example" data-slide="prev">
	    	<span><img src="img/circulo/slider_flecha_izq.png"></span>
	  	</a>
	 	<a class="right carousel-control" href="#carousel-example" data-slide="next">
	    	<span> <img src="img/circulo/slider_flecha_der.png"></span>
	  	</a>
	</div>

<!-- Termina Slider -->

@endsection

@section('footer')
<script type="text/javascript" src="js/circulo.js"></script>
<script type="text/javascript" src="js/jquery.foggy.min.js"></script>

@endsection