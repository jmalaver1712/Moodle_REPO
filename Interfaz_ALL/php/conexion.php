<?php

	/* VARIABLES DE CONFIGURACION */
	$array_config = objectToArray($CFG);
	$prefijo = $array_config['prefix'];
	$host_db = $array_config['host'];
	$user_db = $array_config['dbuser'];
	$pass_db = $array_config['dbpass'];
	$data_db = $array_config['dbname'];
	
	$conexion = mysqli_connect($host_db, $user_db, $pass_db, $data_db);
	
	if (!$conexion) {
		echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
		echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
		echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	//FUNCION EXPRESION REGULAR
	function valido ($digitos){
		$patron = "/^[[:digit:]]+$/";
		if (preg_match($patron, $digitos)) {
			return $digitos;
		} else {
			mysqli_close($conexion);
			print("<meta http-equiv='refresh' content='0.1; url=../index.php' />");
		}

	}

	function objectToArray($d) {
		if (is_object($d)) {
			$d = get_object_vars($d);
		}
		if (is_array($d)) {
			return array_map(__FUNCTION__, $d);
		}else{
			return $d;
		}
	}

	if(isset($_POST['usuario'])){
		$usuario = $_POST['usuario'];
		echo "recibir".$usuario;
	}
	
?>