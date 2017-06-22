<?php

$name_mundos[1]= "Mundo Campus Virtual";
$name_mundos[2]= "Mundo Interfaz";
$name_mundos[3]= "Mundo Moodle";
$name_mundos[4]= "Herramientas";
$name_mundos[5]= "Mundo Pedagogico";

/* ======= VARIABLE DE SESION */
$array_user = objectToArray($_SESSION['USER']);
$nombre_usuario =  $array_user['firstname']." ".$array_user['lastname'];
$rol_usuario =  $array_user['icq'];
$curso = $_GET['id'];

$id_usuario = $array_user['id'];
$id_docente = $array_user['username'];
$correo_docente = $array_user['email'];

$quiz_array = array();

/* USUARIOS */
$consulta_user = "SELECT * FROM cap_usuarios WHERE id_docente = '$id_docente'";
$res_user = $conexion->query($consulta_user) or die ("ERROR cosas".$mysqli->error);
$num_user = mysqli_num_rows($res_user);
if($num_user == 0){
	?>
	<script type="text/javascript">
		var x = "instrucciones";
		open_modal(x);
	</script>
	<?php
	// $usuario = "INSERT INTO cap_usuarios (id_docente, nombre_docente, nivel_docente, correo_docente) VALUES ('$id_docente', '$nombre_usuario', 1, '$correo_docente')";
	// $query_us = $conexion->query($usuario) or die ("ERROR 1".$mysqli->error);
}
$reg_user = $res_user ->fetch_array();
$mundo = $reg_user['nivel_docente'];

/* ========= CONSULTA DE RECURSOS */
$consulta = "select * from ".$prefijo."course_sections where course = $curso" ;
$res = $conexion->query($consulta) or die ("ERROR 1".$mysqli->error);

$aux = 0;
$aux_nivel = 1;
$cont_act = 0;
$nivel_cont = 0;

//TIEMPO HOY
$nowtime = time();

while ($reg = $res ->fetch_array()){
//SECCION
	$seccion = $reg['id'];
	$ids_seccion[$aux] = $seccion;

	$separar = explode(',',$reg['sequence']);
	$tam = count($separar);

	//MATRIZ SECCION
	${'matriz_'.$seccion}[$tam][4] = array();
	for ($i=0; $i < $tam; $i++) { 
		for ($j=0; $j < 4; $j++) { 
			${'matriz_'.$seccion}[$i][$j] = "";
		}
	}

	for($j=0; $j<$tam; $j++){

		$id = $separar[$j];
		$recursos  = "select * from ".$prefijo."course_modules where id = '$id' and section = '$seccion' ";
		$res2 = $conexion->query($recursos) or die ("Error 2". $mysqli->error);
		$reg2 = $res2 ->fetch_array();

		$instancia = $reg2['instance'];
		$module= $reg2[module];
		
		//Si son Documentos
		if($module == "17"){
			$con_name_recursos = "select  name AS Nombre from ".$prefijo."resource where id = '$instancia' and course= '$curso' ";
			$res_doc = $conexion->query($con_name_recursos) or die ("Error 3". $mysqli->error);
			$reg_doc = $res_doc ->fetch_array();

			$aux_tema = explode("-",$reg_doc['Nombre']);

			${'matriz_'.$seccion}[$j][0] = $id;
			${'matriz_'.$seccion}[$j][1] = trim($aux_tema[0]);
			${'matriz_'.$seccion}[$j][2] = utf8_encode($aux_tema[1]);
			${'matriz_'.$seccion}[$j][3] = "../mod/resource/view.php?id=".$id;
			${'matriz_'.$seccion}[$j][4] = "Documento";
		}

		//Si son Videos
		if($module == 20){
			$videos  = "select name AS Nombre, externalurl AS url_video from ".$prefijo."url where id = '$instancia' and course = '$curso'";
			$res_video = $conexion->query($videos) or die ("Error quiz". $mysqli->error);
			$reg_video = $res_video ->fetch_array();
			
			$aux_tema = explode("-",$reg_video['Nombre']);
			$divide = explode("=",$reg_video['url_video']);

			${'matriz_'.$seccion}[$j][0] = $id;
			${'matriz_'.$seccion}[$j][1] = trim($aux_tema[0]);
			${'matriz_'.$seccion}[$j][2] = utf8_encode($aux_tema[1]);
			${'matriz_'.$seccion}[$j][3] = "https://www.youtube.com/embed/".$divide[1];
			${'matriz_'.$seccion}[$j][4] = "Video";
		}

		//Si son Quiz 
		if($module == 16){
			$actividad  = "select name AS Nombre from ".$prefijo."quiz where id = '$instancia' and course = '$curso'";
			$res_acti = $conexion->query($actividad) or die ("Error quiz". $mysqli->error);
			$reg_acti = $res_acti ->fetch_array();

			$aux_tema = explode("-",$reg_acti['Nombre']);

			${'matriz_'.$seccion}[$j][0] = $instancia;
			${'matriz_'.$seccion}[$j][1] = trim($aux_tema[0]);
			${'matriz_'.$seccion}[$j][2] = utf8_encode($aux_tema[1]);
			${'matriz_'.$seccion}[$j][3] = "../mod/quiz/view.php?id=$id";
			${'matriz_'.$seccion}[$j][4] = "Quiz";

			$quiz_array[] = $instancia; 

		}

		if(strcmp(${'niveles_'.$seccion}[$aux_nivel-1], $aux_tema[0]) != 0){
			${'niveles_'.$seccion}[$aux_nivel] = $aux_tema[0];
			$aux_nivel++;
		}
	}
	$aux++;
}
$genera_cert = "disabled"; 
$n_mundos  = count($ids_seccion);
$aux_exam = 0;
$puntajes = array();
for ($h=0; $h < count($quiz_array); $h++) { 
	$id_quiz = $quiz_array[$h];
	$query_mundo = "SELECT userid, quiz, grade FROM ".$prefijo."quiz_grades WHERE userid = '$id_usuario' AND quiz = '$id_quiz'";
	$res_mundo = $conexion->query($query_mundo) or die ("Error quiz". $mysqli->error);
	while($reg_mundo = $res_mundo ->fetch_array()){
		$puntajes[] = $reg_mundo['grade'];
		if($reg_mundo['grade'] > "0"){
			$aux_exam++;
			$mundo = $aux_exam+1;
			$query_up_mundo = "UPDATE cap_usuarios SET nivel_docente = '$mundo' WHERE id_docente = '$id_docente';";
			$res_up_mundo = $conexion->query($query_up_mundo) or die ("Error actualizar");
		}
	}
}

if($mundo == 6){
	$genera_cert = "floating"; 
}
?>