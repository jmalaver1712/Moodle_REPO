 $(document).ready(function(){
    var i = $('#flag_encuesta').val();
    if(i == 1){
    	$('#Encuesta').openModal();
    }
  });

function cerrar_encuesta(){
	$('#Encuesta').closeModal();
}

function abrir_encuesta(){
	$('#Encuesta').openModal();
}

function navegacion(id){
	$(".nav_menu").removeClass("senal");
	$("#"+id).addClass("senal");
}

function aparece(){
	var panel = document.getElementById("noticias");
	if (panel.className == "noticias noticias_mostrar"){
		panel.className = "noticias noticias_ocultar";
	}else{
		panel.className = "noticias noticias_mostrar";
	}
}

function mostrar(unidad){
	$(".menu3").addClass("ocultar");
	var x = $("#"+unidad).hasClass("mostrar");
	if(x == true){
		$("#"+unidad).addClass("ocultar").removeClass("mostrar");
		return;
	}
	$("#"+unidad).addClass("mostrar").removeClass("ocultar");
	
}

function oculta_panel(){
	var panel = document.getElementById("noticias");
	if (panel.className == "noticias noticias_mostrar"){
		panel.className = "noticias noticias_ocultar";
	}
	//$("#link_contenido").addClass("ocultar").removeClass("mostrar");
}


function info_com(id){
	$(".info_com").addClass("ocultar").removeClass("mostrar");
	$("#info_"+id).addClass("mostrar").removeClass("ocultar");
}
