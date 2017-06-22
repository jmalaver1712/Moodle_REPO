<?php
	$user = $_POST['user_db'];
	$pass = $_POST['pass_db'];
	$data = $_POST['data_db'];

	$conexion = mysqli_connect("localhost", $user, $pass, $data);


	//SI NO ES EVALUACION
	if(empty($_POST['evaluacion'])){

		$usuario = $_POST['usuario'];
		$nivel_tema = $_POST['nivel_tema'];
		$docente = $_POST['docente'];

		$con_medalla = "SELECT * FROM cap_nivel_user WHERE id_usuario = '$usuario' AND nivel = '$nivel_tema'";
		$res_medalla = $conexion->query($con_medalla) or die ("ERROR ACTIVIDADES".$mysqli->error);
		$reg_medalla = count($res_medalla ->fetch_array());
		if($reg_medalla < 1){
			$consulta_user = "INSERT INTO cap_nivel_user (id_usuario, nivel, calificacion, docente) VALUES ('$usuario', '$nivel_tema', '1',  '$docente')";
			$res_user = $conexion->query($consulta_user) or die ("ERROR ACTIVIDADES".$mysqli->error);
		}
		print($nivel_tema." <img src='../interfaz/imgs/medalla.png'>");
	
	}

	//SI ES EVALUACION
	else{
	$id_usuario = $_POST['id_usuario'];
	$docente = $_POST['docente'];
	$mundo1 = $_POST['mundo1'];
	$mundo2 = $_POST['mundo2'];
	$mundo3 = $_POST['mundo3'];
	$mundo4 = $_POST['mundo4'];
	$mundo5 = "";
	$observacion = $_POST['observacion'];
	
			$query_eval = "INSERT INTO cap_evaluacion (id_usuario, docente, mundo1, mundo2, mundo3, mundo4, mundo5, observacion) 
			VALUES ('$id_usuario', '$docente', '$mundo1', '$mundo2', '$mundo3', '$mundo4', '$mundo5', '$observacion')";
			$insert_eval = $conexion->query($query_eval) or die ("ERROR EVALUACION".$mysqli->error);
			print("<script> window.history.go(-1); </script>");
	}
?>