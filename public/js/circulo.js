var isChrome = !!window.chrome && !!window.chrome.webstore;

$(document).ready(function()
{
    //closeIframe();
   
});

function destaca (id)
{

	if (id == 'home_boton_expediente') { $("#maincircle").attr( 'src', 'img/circulo/expedientes_circulo.png'); }
	if (id == 'home_boton_pedimentos') { $("#maincircle").attr( 'src', 'img/circulo/pedimentos_circulo.png'); }
	if (id == 'home_boton_importacion') { $("#maincircle").attr( 'src', 'img/circulo/importacion_circulo.png'); }
	if (id == 'home_boton_agentes') { $("#maincircle").attr( 'src', 'img/circulo/agentes_circulo.png'); }
	
}


function showMenu()
{
	$('.menu-item').slideToggle(350);
}

function restaura()
{	

	$("#maincircle").attr('src','img/circulo/circulo.png');
}

