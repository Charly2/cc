function regitrarPago()
{
	//Obtengo las variables a enviar
	var monto 		= $("#monto").val();
	var rfc   		= $("#rfc").val();
	/*var numero_cuenta = $('#numero_cuenta').val();
	var nombre_cuenta = $('#nombre_cuenta').val();*/
	var doc   		= 1;
	var tipo  		= 1.4;
	var d 	      	= new Date();
	var periodo   	= d.getMonth()+1;
	var ejercicio 	= d.getFullYear();

/*		'numero_cuenta' : numero_cuenta,
		'nombre_cuenta' : nombre_cuenta,*/
	var request = {
		'doc' : doc,
		'tipo' : tipo,
		'empresa' : rfc,
		'ejercicio' : ejercicio,
		'periodo' : periodo,
		'movimientos' : [{
			'cuenta' : 82738432,
			'carga' : "abono",
			'monto' : monto
		}]
	};
	$.ajax({
		url: 'http://54.165.25.115/ws/asdfasdf.php',
        /*url: 'http://192.168.2.251/ws/polizaFinancials.php',*/
		type: 'POST',
		contentType: "application/json",
		dataType: "json",
		success: function (msg)
		{
			console.log('exito->',msg);
		},
		error: function (msg)
		{
			console.log('error->',msg);
		},
		data: JSON.stringify( request )
	});
        
}