<?php
/* ======= VARIABLE DE SESION */
$array_user = objectToArray($_SESSION['USER']);
$id_user_log = $array_user['id'];
$nombre_usuario =  $array_user['firstname'];
$rol_usuario =  $array_user['icq'];
$curso = $_GET['id'];

$url_campus = "http://campusvirtualurepublicana.edu.co";
//$url_campus = "http://localhost/WEB/campus";

$hoy = date("Y-m-d");

$noticias = "SELECT * FROM am_noticias WHERE (fecha_publicacion <= '$hoy' AND fecha_final >= $hoy) ORDER BY fecha_publicacion DESC LIMIT 0,3 ";
$noticias_res = $conexion->query($noticias) or die ("ERROR Noticias".$mysqli->error);
$cont_noticias = mysqli_num_rows($noticias_res);


/* ENCUESTAS */
$cont_valida = 0;

$encuesta = "SELECT id_encuesta, nombre, descripcion FROM aa_encuesta WHERE (f_inicio <= '$hoy' AND f_final >= '$hoy')";
$encuesta_res = $conexion->query($encuesta) or die ("ERROR Encuesta".$mysqli->error);
$cont_encuesta = mysqli_num_rows($encuesta_res);
if($cont_encuesta > 0){
	$valida = "SELECT * FROM aa_respuestas WHERE id_usuario = '$id_user_log' AND id_curso = '$curso'";
	$valida_res = $conexion->query($valida) or die ("ERROR VALIDA".$mysqli->error);
	$cont_valida = mysqli_num_rows($valida_res);
	if($cont_valida < 1){
		$encuesta_reg = $encuesta_res ->fetch_array();
		$cont_valida = 1;
	}
}else{
	$cont_valida = 0;
}


/* ========= CONSULTA DE RECURSOS */
$consulta = "select * from ".$prefijo."course_sections where course = $curso" ;
$res = $conexion->query($consulta) or die ("ERROR 1".$mysqli->error);


/* ========= CONSULTA DE TOP 5*/ 
$consulta_top ="select ".$prefijo."user.picture, ".$prefijo."user.firstname, ".$prefijo."user.lastname, ".$prefijo."course.shortname, ".$prefijo."grade_grades.finalgrade
from
".$prefijo."course, ".$prefijo."grade_grades, ".$prefijo."grade_items, ".$prefijo."user
where ".$prefijo."course.id = ".$curso."
AND ".$prefijo."course.id = ".$prefijo."grade_items.courseid 
AND ".$prefijo."grade_grades.userid = ".$prefijo."user.id 
AND ".$prefijo."grade_items.id = ".$prefijo."grade_grades.itemid 
AND ((".$prefijo."user.deleted=0) AND (".$prefijo."grade_items.itemtype='course'))
ORDER BY ".$prefijo."grade_grades.finalgrade desc LIMIT 0 , 5";

$res_top = $conexion->query($consulta_top) or die ("ERROR ".$mysqli->error);

//VARIABLES AUXILIARES
$aux = 0;
$aux_acti = 0;
$aux_com = 0;
$cont_act = 0;
$aux_intros = 0;

//TIEMPO HOY
$nowtime = time();

while ($reg = $res ->fetch_array()){
//SECCION
	$seccion = $reg['id'];
	$ids_seccion[$aux] = $seccion;

//MATRIZ SECCION
	${'matriz_'.$seccion}[10][20] = array();
	$matriz_actividades[20][6] = array();
	$comunicacion[5][4] = array();

	for ($i=0; $i < 10; $i++) { 
		for ($j=0; $j < 20 ; $j++) { 
			${'matriz_'.$seccion}[$i][$j] = "";
		}
	}

	$separar = explode(',',$reg['sequence']);
	$tam = count($separar);

	for($j=0; $j<$tam; $j++){

		$id = $separar[$j];
		//echo "id =".$id."<br>";
		$recursos  = "select * from ".$prefijo."course_modules where id = '$id' and section = '$seccion' ";
		$res2 = $conexion->query($recursos) or die ("Error 2". $mysqli->error);
		$reg2 = $res2 ->fetch_array();
		
		$instancia = $reg2['instance'];
		$module= $reg2[module];
		
		//Si son Recursos - Documentos
		if($module == 17){
			$con_name_recursos = "select name from ".$prefijo."resource where id = '$instancia' and course= '$curso' ";
			$res3 = $conexion->query($con_name_recursos) or die ("Error 3". $mysqli->error);
			$reg3 = $res3 ->fetch_array();
			$cant = $res3 ->num_rows;
			$name = $reg3['name'];
			$enlaces = explode('-',$name);
			//Quita los estacios
			$indice = trim($enlaces['0']," ");
			$contenido = "../mod/resource/view.php?id=".$id."%_%".$enlaces['1']."%_%";
			
			if(is_numeric($indice)){
				for ($h=1; $h < 20 ; $h++) {
					if(${'matriz_'.$seccion}[$indice][$h] == ""){
						${'matriz_'.$seccion}[$indice][$h] = $contenido;
						$h = 20;
					}	
				}
			}else{
				//Recursos Intros
				if (strlen($indice) == 3){
					$aux_intros++;
					$intros_finales[$aux_intros] = utf8_encode($indice."%_%".$contenido."%_%recurso");
				}
			}
		}
		
		//Wiki
		if($module == 21){
			$wiki  = "select name from ".$prefijo."wiki where id = '$instancia' and course = '$curso'";
			$wiki_res = $conexion->query($wiki) or die ("Error WIKI". $mysqli->error);
			$wiki_reg = $wiki_res ->fetch_array();
			$name = $wiki_reg['name'];
			$enlaces = explode('-',$name);
			$indice = trim($enlaces['0']," ");
			$contenido = "../mod/wiki/view.php?id=".$id."%_%".$enlaces['1']."%_%";
			for ($h=1; $h < 20 ; $h++) {
				if(${'matriz_'.$seccion}[$indice][$h] == ""){
					${'matriz_'.$seccion}[$indice][$h] = $contenido;
					$h = 20;
				}	
			}
		}

		//SCORM
		if($module == 18){
			$scorm  = "select name from ".$prefijo."scorm where id = '$instancia' and course = '$curso'";
			$scorm_res = $conexion->query($scorm) or die ("Error WIKI". $mysqli->error);
			$scorm_reg = $scorm_res ->fetch_array();
			$name = $scorm_reg['name'];
			$enlaces = explode('-',$name);
			$indice = trim($enlaces['0']," ");
			$contenido = "../mod/scorm/view.php?id=".$id."%_%".$enlaces['1']."%_%";
			for ($h=1; $h < 20 ; $h++) {
				if(${'matriz_'.$seccion}[$indice][$h] == ""){
					${'matriz_'.$seccion}[$indice][$h] = $contenido;
					$h = 20;
				}	
			}
		}
			
		//Si son Actividades
		else{

			$actividad = "";
			$tipo = "";

			//Si son Videos
			if($module == 20){
				$videos  = "select name AS Nombre, externalurl AS url_video, intro AS Descripcion from ".$prefijo."url where id = '$instancia' and course = '$curso'";
				$res_video = $conexion->query($videos) or die ("Error quiz". $mysqli->error);
				$reg_video = $res_video ->fetch_array();
				$name = $reg_video['Nombre'];
				$enlaces = explode('-',$name);
				//Quita los estacios
				$indice = trim($enlaces['0']," ");
				$nombre_video = $enlaces['1'];
				
				
				$fuente_vimeo = strpos($reg_video['url_video'],"vimeo");
				$fuente_youtube = strpos($reg_video['url_video'],"youtube");
				$fuente_educa = strpos($reg_video['url_video'],"educaplay");

				//Son de Vimeo
				if ($fuente_vimeo !== false){
					$divide = explode("/",$reg_video['url_video']);
					$max_x = count($divide)-1;
					$ident = "https://player.vimeo.com/video/".$divide[$max_x-1];
				}
				//Son de Youtube
				if ($fuente_youtube !== false){
					$divide = explode("=",$reg_video['url_video']);
					$ident = "https://www.youtube.com/embed/".$divide[1];
				}

				//Son de EducaPlay
				if ($fuente_educa !== false){
					$divide = explode("/",$reg_video['url_video']);
					$max_x = count($divide)-1;
					$ident = "../interfaz/php/educaplay.php?recurso=".$divide[$max_x-1]."/html5/".$divide[$max_x];
				}

				$contenido = trim($ident."%_%".$nombre_video."%_%".$reg_video['Descripcion'],"</p>");

				if(is_numeric($indice)){
					for ($h=1; $h < 20 ; $h++) {
						if(${'matriz_'.$seccion}[$indice][$h] == ""){
							${'matriz_'.$seccion}[$indice][$h] = utf8_encode($contenido);
							$h = 20;
						}	
					}
				}else{
					if (strlen($indice) == 3){
						$aux_intros++;
						$intros_finales[$aux_intros] = utf8_encode($indice."%_%".$contenido."%_%video");
					}
				}
			}

					
			//Entrega
			if($module == 1){

			$tipo = "assign";
			$actividad  = "
			SELECT a.name AS Nombre, a.allowsubmissionsfromdate AS Inicio, a.duedate AS Final, a.grade AS Nota,
			b.finalgrade AS Nota_final
			FROM ".$prefijo."assign a, ".$prefijo."grade_items c
			LEFT JOIN ".$prefijo."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
			WHERE c.iteminstance = a.id
			AND c.itemmodule = '$tipo'
			AND a.id = '$instancia'
			AND a.course = '$curso'";

			}

			//Foros
			if($module == 9){

			$tipo = "forum";
			$actividad  = "
			SELECT a.name AS Nombre, a.assesstimestart AS Inicio, a.assesstimefinish AS Final, a.scale AS Nota, a.intro AS Descripcion, 
			b.finalgrade AS Nota_final
			FROM ".$prefijo."forum a, ".$prefijo."grade_items c
			LEFT JOIN ".$prefijo."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
			WHERE c.iteminstance = a.id
			AND c.itemmodule = '$tipo'
			AND a.id = '$instancia'
			AND a.course = '$curso'";

			}

			//Quiz
			if($module == 16){

			$tipo = "quiz";
			$actividad  = "
			SELECT a.name AS Nombre, a.timeopen AS Inicio, a.timeclose AS Final, a.grade AS Nota,
			b.finalgrade AS Nota_final
			FROM ".$prefijo."quiz a, ".$prefijo."grade_items c
			LEFT JOIN ".$prefijo."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
			WHERE c.iteminstance = a.id
			AND c.itemmodule = '$tipo'
			AND a.id = '$instancia'
			AND a.course = '$curso'";
			}

			//Chats
			if($module == 4){
				$tipo = "chat";
				$actividad  = "select name AS Nombre, introformat AS Nota from ".$prefijo."chat where id = '$instancia' and course = '$curso'";
			}

			// MODULOS DE ACTIVIDAD
			if($actividad != ""){
				// print("<h5>HOLA - $actividad</h5>");
				$res_acti = $conexion->query($actividad) or die ("Error Actividades". $mysqli->error);
				$reg_acti = $res_acti ->fetch_array();

				if(($tipo == "forum" || $tipo == "chat") && $reg_acti['Nota'] <= 1){
					$comunicacion[$aux_com][0] = $id;
					$comunicacion[$aux_com][1] = $tipo;
					$comunicacion[$aux_com][2] = utf8_encode($reg_acti['Nombre']);
	
					$aux_com++;
				}

				if(!empty($reg_acti['Nombre']) && $reg_acti['Nota'] > 1){

					if ($reg_acti['Inicio'] <= $nowtime && $reg_acti['Final'] >= $nowtime){
						$cont_act++;
					}
					$matriz_actividades[$aux_acti][0] = $id;
					$matriz_actividades[$aux_acti][1] = $tipo;
					$matriz_actividades[$aux_acti][2] = utf8_encode($reg_acti['Nombre']);
					$matriz_actividades[$aux_acti][3] = $reg_acti['Inicio'];
					$matriz_actividades[$aux_acti][4] = $reg_acti['Final'];
					$matriz_actividades[$aux_acti][5] = round($reg_acti['Nota'],0);
					$matriz_actividades[$aux_acti][6] = round($reg_acti['Nota_final'],3);
					$aux_acti++;
				}
			}
		}
	}
	$aux++;
}
$n_secciones  = count($ids_seccion);

?>