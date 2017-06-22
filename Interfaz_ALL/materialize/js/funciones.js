// INICIAR 

 $(document).ready(function(){
    $('ul.tabs').tabs();
  });

function iniciar(){
		$("#inicio").addClass("ocultar");
		$("#mundos").removeClass("ocultar").addClass("fadeIn");
		$("#docente").addClass("iniciar");
}

function niveles(nivel){
	var el = document.getElementById("docente"); 
	$("#docente").addClass("mundo_"+nivel);
	el.addEventListener(
	"animationend",
	function () {
		$("#mundos").addClass("ocultar");
		$(".mundo").addClass("ocultar");
		$("#docente").removeClass("mundo_"+nivel).addClass("iniciar");
		$("#niveles_mundo_"+nivel).removeClass("ocultar").addClass("pullDown");
	});
}

function actividades(nombre){
	$(".dash_board").addClass("ocultar");
	$(".nivel").addClass("ocultar");
	$("#"+nombre).removeClass("ocultar").addClass("pullDown");
}

function volver_mundos(mundo){
	$(".mundo").addClass("ocultar");
	$(".nivel").addClass("ocultar");
	$("#mundos").removeClass("ocultar").addClass("fadeIn");
}

function volver_nivel(nivel){
	$(".mundo").addClass("ocultar");
	$(".nivel").addClass("ocultar");
	$("#niveles_mundo_"+nivel).removeClass("ocultar").addClass("pullDown");
}

function pregunta_quiz(tipo){
	var id_pregunta = document.getElementById("id_pregunta"); 
	id_pregunta.value = tipo;
	alert(tipo);
}

function open_modal(nivel_tema){
	$('#modal_'+nivel_tema).openModal();
}

function enviarDatos(nivel_tema){
	
    //Recogemos los valores introducimos en los campos de texto
    var user_db = $("#user_db_"+nivel_tema).val();
    var pass_db = $("#pass_db_"+nivel_tema).val();
    var data_db = $("#data_db_"+nivel_tema).val();

    var usuario = $("#usuario_"+nivel_tema).val();
    var pregunta = $("#pregunta_"+nivel_tema).val();
    var opcion = $('input:radio[name=opcion_'+nivel_tema+']:checked').val();
    var respuesta = $("#rta_"+nivel_tema).val();
    
	
	
    var formData = {user_db:user_db,pass_db:pass_db,data_db:data_db,usuario:usuario,nivel_tema:nivel_tema};
	
	//enviamos las variables a 'consulta.php' 
	if(opcion == respuesta){
		alert("Bien hecho, Felicitaciones");
		
		var url = "../interfaz/php/respuesta.php"; // El script a dónde se realizará la petición.
		$.ajax({
			type: "POST",
			url: url,
		data: formData, // Adjuntar los campos del formulario enviado.
		success: function(data)
		{
			$("#respuesta_"+nivel_tema).html(data); // Mostrar la respuestas del script PHP.
		}
		});
		
	}
	else{
		alert("Fallaste, Intentalo luego");
	}
	$('#modal_'+nivel_tema).closeModal();
}


function enviar_formulario(){
   document.Form_Certificado.submit()
}