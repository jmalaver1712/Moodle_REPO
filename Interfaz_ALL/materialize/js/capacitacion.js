// INICIAR 

 $(document).ready(function(){
    $('ul.tabs').tabs();
    //Comprobar si ya contesto la Evaluacion
    var e = $('#flag_eval').val();
    if(e == 1){
    	$('#modal_evaluacion_final').openModal();
    }

    var i = $('#flag_tuto').val();
    if(i == 1){
    	$('#modal_instrucciones').openModal();
    }

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

function top_est(){
	var x = $("#Mejores_Estudiantes").hasClass("ocultar");
	if(x == false){
		$("#Mejores_Estudiantes").addClass("ocultar");
		return;
	}
	$("#Mejores_Estudiantes").removeClass("ocultar");
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
	$('#interfaz_capacitacion').addClass("disabled");
}

function open_modal_preg(nivel_tema){
	 var x = Math.floor((Math.random() * 6) + 1);
	$('#'+x+'modal_'+nivel_tema).openModal();
	$('#interfaz_capacitacion').addClass("disabled");
}

function enviarDatos(id_preg,nivel_tema){
    //Recogemos los valores introducimos en los campos de texto
    var user_db = $("#"+id_preg+"user_db_"+nivel_tema).val();
    var pass_db = $("#"+id_preg+"pass_db_"+nivel_tema).val();
    var data_db = $("#"+id_preg+"data_db_"+nivel_tema).val();

    var docente = $("#"+id_preg+"user_game_"+nivel_tema).val();
    var usuario = $("#"+id_preg+"usuario_"+nivel_tema).val();
    var pregunta = $("#"+id_preg+"pregunta_"+nivel_tema).val();
    var opcion = $("input:radio[name="+id_preg+"opcion_"+nivel_tema+"]:checked").val();
    var respuesta = $("#"+id_preg+"rta_"+nivel_tema).val();
    
    var formData = {user_db:user_db,pass_db:pass_db,data_db:data_db,usuario:usuario,docente:docente,nivel_tema:nivel_tema};
	
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
	$("#"+id_preg+"modal_"+nivel_tema).closeModal();
	$('#interfaz_capacitacion').removeClass("disabled");
}


function enviar_formulario(){
   document.Form_Certificado.submit()
}