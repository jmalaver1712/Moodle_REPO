<?php
header('Content-Type: text/html;charset=UTF-8');
	$id_usuario = $_POST['id_user'];
	$id_curso = $_POST['id_curso'];
	$user_db = $_POST['user_db'];
	$pass_db = $_POST['pass_db'];
	$data_db = $_POST['data_db'];
	$link_encuesta = mysqli_connect("localhost", $user_db, $pass_db, $data_db);

	$numero_post = count($_POST);
	$tags = array_keys($_POST);
	$valores = array_values($_POST);

	if($numero_post > 0){
		//Tomamos Valores
		for($i=5;$i < $numero_post ;$i++){

			$respuesta = $valores[$i];

			if(!is_numeric($respuesta)){
				$insert_txt = "INSERT INTO aa_opciones (id_pregunta, opcion) VALUES ('$tags[$i]', '$respuesta')";
				$res_insert_txt = $link_encuesta->query($insert_txt) or die ("ERROR RTA TEXTO".$mysqli->error);
				$respuesta = mysqli_insert_id($link_encuesta);

			}
			
			$insert = "INSERT INTO aa_respuestas (id_usuario, id_curso, id_pregunta, respuesta) VALUES ('$id_usuario', '$id_curso', '$tags[$i]', '$respuesta')";
			$res_insert = $link_encuesta->query($insert) or die ("ERROR RTA".$mysqli->error);
		}
	}
	print("<script>window.history.go(-1);</script>");
	mysqli_close($link_encuesta);

	

?>