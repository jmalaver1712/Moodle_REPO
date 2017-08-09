<?php 
if(isset($_GET['id'])){
	define('ID_CURSO',$_GET['id']);
}else{
	define('ID_CURSO','0');
}
// CLASS
class interfaz extends Conexion{

    public function __construct(){
        parent::__construct();
    }

    public function config_interfaz(){
		$data = array();
		$result = $this->_db->query("SELECT * FROM am_interfaz");
		$config_int = $result->fetch_all(MYSQLI_ASSOC);
		foreach ($config_int[0] as $row => $value) {
			$data[$row] = $value;
		}
		return $data;
	}


    // DATOS DEL USUARIO LOGIN
	public function datos_usuario(){
		$data = json_decode(json_encode($_SESSION['USER']),true);

		$result = $this->_db->query("
		SELECT a.roleid FROM ".DB_PRE."role_assignments a, ".DB_PRE."context b 
		WHERE a.userid = '$data[id]' AND a.contextid = b.id AND b.instanceid =".ID_CURSO
		);

		$rol = 0;
		if (!$result) {
			printf("Errormessage datos_usuario: %s\n", $this->_db->error);
		}else{
	        $rol_user = $result->fetch_all(MYSQLI_ASSOC);
	        if(count($rol_user) > 0){
	        	$rol = $rol_user['0']['roleid'];
	        }
    	}

		$usuario = array(
			"id" => $data['id'],
			"nombre" => $data['firstname'],
			"apellido" => $data['lastname'],
			"codigo_estudiante" => $data['idnumber'],
			"rol" => $rol
		);

		$datos_add = $this->_db->query("
			SELECT e.per_aca, b.id_facultad, a.id_programa, e.idnumber as codigo_curso, a.id_genero, a.idnumber
			FROM am_usuario a, am_programa b, ".DB_PRE."course e
			WHERE a.id_programa = b.id_programa
			AND e.id = ".ID_CURSO."
			AND id_moodle = ".$data['id']
			);

		if (!$datos_add) {
			printf("Errormessage datos_add: %s\n", $this->_db->error);
		}else{
			$row = $datos_add->fetch_all(MYSQLI_ASSOC);
			foreach ($row as $key) {
				foreach ($key as $data => $value) {
					$usuario[$data] = $value;
				}
			}
		}

		return $usuario;
	}


	// ESTRUCTURA DEL CURSO
	public function estructura(){
		$data = array();
		$result = $this->_db->query("SELECT a.modulo, a.semanas FROM am_tipo_curso a, am_cursos b WHERE a.tipo = b.tipo AND b.moodle_course_id = ".ID_CURSO);
		$estructura = $result->fetch_all(MYSQLI_ASSOC);
		foreach ($estructura as $row) {
			$data[$row['modulo']] = $row['semanas'];
		}
		return $data;
	}
	

	// NOTIFICACIONES
	public function data_notificaciones(){
		$data_user = $this->datos_usuario();

		$filtro = "";
		if(count($data_user) > 5) {
			$aux=0;
			foreach ($data_user as $key => $value) {
				$aux++;
				if($aux > 5){
					$filtro = $filtro."AND ($key = $value OR $key IS NULL) ";
				}
			}
			$filtro = $filtro."OR importancia = 1";
		}
		else{
			$filtro = "AND importancia = 1";
		}

		$data = array();
		$hoy = date("Y-m-d");

		$result = $this->_db->query("
		SELECT id,
		titular,
		imagen,
		enlace,
		resumen,
		fecha_publicacion,
		fecha_final
		FROM am_notificaciones
		WHERE (fecha_publicacion <= '$hoy' AND fecha_final >= $hoy)
		$filtro
		ORDER BY fecha_publicacion, importancia DESC
		LIMIT 0,5
		");

		if (!$result) {
			printf("Errormessage data_noticias: %s\n", $this->_db->error);
			 return $data;
		}else{
			$noticias = $result->fetch_all(MYSQLI_ASSOC);
				foreach ($noticias as $noticia) {
					if(is_null($noticia['enlace'])){
						$noticia['enlace'] = URL_CAMPUS."/pages/ver_noticia.php?id=$noticia[id]&pagina=campus";
					}
				$noticia['imagen'] = URL_CAMPUS."/admin/imgs_news/$noticia[imagen]";
				$data[] = $noticia;
			}
		}
        return $data; 
	}

	// TOP 5
	public function top5(){
		$data = array();
		$result = $this->_db->query("
		SELECT d.picture, e.contextid, d.firstname, d.lastname, a.shortname, b.finalgrade
		FROM
		".DB_PRE."course a, ".DB_PRE."grade_grades b, ".DB_PRE."grade_items c, ".DB_PRE."user d
		LEFT JOIN ".DB_PRE."files e ON d.picture = e.id
		WHERE a.id = ".ID_CURSO."
		AND a.id = c.courseid 
		AND b.userid = d.id 
		AND c.id = b.itemid
		AND ((d.deleted=0) AND (c.itemtype='course'))
		ORDER BY b.finalgrade desc LIMIT 0,5");

		if (!$result) {
			printf("Errormessage top5: %s\n", $this->_db->error);
		}
        $top5 = $result->fetch_all(MYSQLI_ASSOC);
        $aux = 0;
        foreach ($top5 as $top) {
        	if($top['contextid'] == NULL){
        		$imagen = "../interfaz/imgs/estudiante.png";  
        	}
        	else{
        		$imagen = "../pluginfile.php/$top[contextid]/user/icon/aardvark/f1?rev=$top[picture]";
        	}
        	$data[$aux]['nombre'] = utf8_encode($top['firstname']." ".$top['lastname']);
        	$data[$aux]['imagen'] = $imagen;
        	$aux++;
        }
        return $data; 
	}

	// SECCIONES
	public function secciones(){
		$data = array();
		$result = $this->_db->query("SELECT id, sequence FROM ".DB_PRE."course_sections WHERE course = ".ID_CURSO);
		if (!$result) {
			printf("Errormessage secciones: %s\n", $this->_db->error);
		}
		$secciones = $result->fetch_all(MYSQLI_ASSOC);
		$aux = 0;
		foreach ($secciones as $row){
			$seccion = $row['id'];
			$secuencia = $row['sequence'];
			$separar = explode(',',$secuencia);
			
			// Si tiene contenido
			if(strlen($secuencia) > 0){
				$data[$aux]['contenidos'] = $separar;
				$data[$aux]['seccion'] = $seccion;
				$aux++;
			}

		}
		return $data;
	}

	// CONTENIDO DEL CURSO
	public function all_contenido(){
		$origen = $this->secciones();
		$aux = 0;
		$data = array();
		foreach ($origen as $row){
			$ids = $row['contenidos'];
			$seccion = $row['seccion'];	
			foreach ($ids as $id){
				$result = $this->_db->query("select instance, module, id, section from ".DB_PRE."course_modules where id = '$id' and section = '$seccion'");
				if (!$result) {
					printf("Errormessage all_contenido: %s\n", $this->_db->error);
				}
				$recursos = $result->fetch_all(MYSQLI_ASSOC);
				$data[$aux] = $recursos; 
				$aux++;
			}
		}
		return $data;
	}

	// CONSULTA DE DOCUMENTOS
	public function con_documentos(){
		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();
		$tipo = "documento";
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['module'] == 17){
				$seccion = $value['section'];
				$id = $value['id'];
				$result = $this->_db->query("select name from ".DB_PRE."resource where id = ".$value['instance']." and course= ".ID_CURSO);
				if (!$result) {
					printf("Errormessage con_documentos: %s\n", $this->_db->error);
				}
				$documentos = $result->fetch_all(MYSQLI_ASSOC);
				//print_r($documentos);

					foreach ($documentos as $documento) {
						$enlaces = explode('-',$documento['name']);
						$indice = trim($enlaces['0']," ");
						$enlace = "../mod/resource/view.php?id=".$id;
						$nombre = utf8_encode($enlaces['1']);
						//if(is_numeric($indice)){
							$data[$aux]['seccion'] = $seccion;
							$data[$aux]['indice'] = $indice;
							$data[$aux]['tipo'] = $tipo;
							$data[$aux]['enlace'] = $enlace;
							$data[$aux]['nombre'] = $nombre;
							$aux++;
						//}
					}
				}
			}
		}
		return $data;
	}

	// CONSULTA DE ENLACES
	public function con_enlaces(){
		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['module'] == 20){

					$seccion = $value['section'];
					
					$result = $this->_db->query("select name, externalurl from ".DB_PRE."url where id = ".$value['instance']." and course = ".ID_CURSO);
					if (!$result) {
						printf("Errormessage con_enlaces: %s\n", $this->_db->error);
					}
					$urls = $result->fetch_all(MYSQLI_ASSOC);

					foreach ($urls as $url){
						$enlaces = explode('-',$url['name']);
						$indice = trim($enlaces['0']," ");
						$enlace = $url['externalurl'];
						$nombre = utf8_encode($enlaces['1']);

						$tipo = "enlace";
						$ident = $enlace;
						

						$fuente_vimeo = strpos($enlace,"vimeo");
						$fuente_youtube = strpos($enlace,"youtube");
						$fuente_educa = strpos($enlace,"educaplay");

						//Son de Vimeo
						if ($fuente_vimeo !== false){
						$tipo = "video";
						$divide = explode("/",$enlace);
						$max_x = count($divide)-1;
						$ident = "https://player.vimeo.com/video/".$divide[$max_x-1];
						}
						//Son de Youtube
						elseif($fuente_youtube !== false){
						$tipo = "video";
						$divide = explode("=",$enlace);
						$ident = "https://www.youtube.com/embed/".$divide[1];
						}

						//Son de EducaPlay
						elseif ($fuente_educa !== false){
						$tipo = "juego";
						$divide = explode("/",$enlace);
						$max_x = count($divide)-1;
						$ident = "../interfaz/php/educaplay.php?recurso=".$divide[$max_x-1]."/html5/".$divide[$max_x];
						}

							$data[$aux]['seccion'] = $seccion;
							$data[$aux]['indice'] = $indice;
							$data[$aux]['tipo'] = $tipo;
							$data[$aux]['enlace'] = $ident;
							$data[$aux]['nombre'] = $nombre;
							$aux++;
					}
				}
			}
		}
		return $data;
		//print_r($data);
	}

	// CONSULTA DE WIKIS
	public function con_wikis(){
		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();
		$tipo = "wiki";
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['module'] == 21){
				$seccion = $value['section'];
				$id = $value['id'];
				$result = $this->_db->query("select name from ".DB_PRE."wiki where id = ".$value['instance']." and course = ".ID_CURSO);
				if (!$result) {
					printf("Errormessage con_wikis: %s\n", $this->_db->error);
				}
				$wikis = $result->fetch_all(MYSQLI_ASSOC);
					foreach ($wikis as $wiki) {

						$enlaces = explode('-',$wiki['name']);
						$indice = trim($enlaces['0']," ");
						$enlace = "../mod/wiki/view.php?id=".$id;
						$nombre = utf8_encode($enlaces['1']);

						$data[$aux]['seccion'] = $seccion;
						$data[$aux]['indice'] = $indice;
						$data[$aux]['tipo'] = $tipo;
						$data[$aux]['enlace'] = $enlace;
						$data[$aux]['nombre'] = $nombre;
						$aux++;

					}
				}
			}
		}
		return $data;
	}

	// CONSULTA DE SCORM
	public function con_scorm(){
		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();
		$tipo = "scorm";
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['module'] == 18){
				$seccion = $value['section'];
				$id = $value['id'];
				$result = $this->_db->query("select name from ".DB_PRE."scorm where id = ".$value['instance']." and course = ".ID_CURSO);
				if (!$result) {
					printf("Errormessage con_scorm: %s\n", $this->_db->error);
				}
				$scorms = $result->fetch_all(MYSQLI_ASSOC);
					foreach ($scorms as $scorm) {

						$enlaces = explode('-',$scorm['name']);
						$indice = trim($enlaces['0']," ");
						$enlace = "../mod/scorm/view.php?id=".$id;
						$nombre = utf8_encode($enlaces['1']);

						$data[$aux]['seccion'] = $seccion;
						$data[$aux]['indice'] = $indice;
						$data[$aux]['tipo'] = $tipo;
						$data[$aux]['enlace'] = $enlace;
						$data[$aux]['nombre'] = $nombre;
						$aux++;

					}
				}
			}
		}
		return $data;
	}

	// CONSULTA DE RECURSOS POR SECCION
	public function con_enlaces_seccion($id_seccion){
		$documentos = $this->con_documentos();
		$enlaces = $this->con_enlaces();
		$wikis = $this->con_wikis();
		$scorms = $this->con_scorm();

		$data = array();
		foreach ($documentos as $documento) {
			if($documento['seccion'] == $id_seccion){
				$data[] = $documento;
			}
		}

		foreach ($enlaces as $enlace) {
			if($enlace['seccion'] == $id_seccion){
				$data[] = $enlace;
			}
		}

		foreach ($wikis as $wiki) {
			if($wiki['seccion'] == $id_seccion){
				$data[] = $wiki;
			}
		}

		foreach ($scorms as $scorm) {
			if($scorm['seccion'] == $id_seccion){
				$data[] = $scorm;
			}
		}

		return $data;
	}

	// VERIFICA EL TIPO DE CONTENIDO QUE SE ENCUENTRA
	public function busca_indices($arreglo){
		$data = array();
		for ($i=2; $i <= 6; $i++) { 
			$rta = array_search($i,array_column($arreglo,'indice'));
			if($rta === false){
				$data[$i] = "disabled";	
			}else{
				$data[$i] = "";	
			}
		}
		return $data;
	}

	// ACTIVIDADES VALORATIVAS
	public function actividades(){
		$data_user = $this->datos_usuario();
		$id_user_log = $data_user['id'];

		$origen = $this->all_contenido();
		$aux = 0;
		$data = array();

		foreach ($origen as $row) {
			foreach ($row as $value) {
				$con_actividad = "";
				$tipo = "";
				//Entrega
				if($value['module'] == 1){
					$tipo = "assign";
					$con_actividad  = "
					SELECT a.id AS Id, a.name AS Nombre, a.allowsubmissionsfromdate AS Inicio, a.duedate AS Final, a.grade AS Nota,b.finalgrade AS Nota_final
					FROM ".DB_PRE."assign a, ".DB_PRE."grade_items c
					LEFT JOIN ".DB_PRE."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
					WHERE c.iteminstance = a.id
					AND c.itemmodule = '$tipo'
					AND a.id = ".$value['instance']."
					AND a.course = ".ID_CURSO;
				}

				//Foros
				if($value['module'] == 9){
					$tipo = "forum";
					$con_actividad  = "
					SELECT a.id AS Id, a.name AS Nombre, a.assesstimestart AS Inicio, a.assesstimefinish AS Final, a.scale AS Nota, b.finalgrade AS Nota_final
					FROM ".DB_PRE."forum a, ".DB_PRE."grade_items c
					LEFT JOIN ".DB_PRE."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
					WHERE c.iteminstance = a.id
					AND c.itemmodule = '$tipo'
					AND a.id = ".$value['instance']."
					AND a.course = ".ID_CURSO;
				}

				//Quiz
				if($value['module'] == 16){
					$tipo = "quiz";
					$con_actividad  = "
					SELECT a.id AS Id, a.name AS Nombre, a.timeopen AS Inicio, a.timeclose AS Final, a.grade AS Nota,b.finalgrade AS Nota_final
					FROM ".DB_PRE."quiz a, ".DB_PRE."grade_items c
					LEFT JOIN ".DB_PRE."grade_grades b ON c.id = b.itemid AND b.userid = '$id_user_log'
					WHERE c.iteminstance = a.id
					AND c.itemmodule = '$tipo'
					AND a.id = ".$value['instance']."
					AND a.course = ".ID_CURSO;
				}

				if($tipo != ""){
					$result = $this->_db->query($con_actividad);
					if (!$result) {
						printf("Errormessage actividades: %s\n", $this->_db->error);
					}
					$nowtime = time();

					$actividad = $result->fetch_all(MYSQLI_ASSOC);
					foreach ($actividad as $val) {

						if ($val['Inicio'] <= $nowtime && $val['Final'] >= $nowtime){
							$tablatr = "tablatr";
						} else {
							$tablatr = "";
						}

						// ORGANIZAR POR FECHA 
						$data[$aux]['id'] = $value['id'];
						$data[$aux]['nombre'] = utf8_encode($val['Nombre']);
						$data[$aux]['f_inicio'] = $val['Inicio'];
						$data[$aux]['f_final'] = $val['Final'];
						$data[$aux]['nota'] = round($val['Nota'],0);
						$data[$aux]['nota_final'] = round($val['Nota_final'],3);
						$data[$aux]['enlace'] = "../mod/$tipo/view.php?id=".$value['id'];
						$data[$aux]['tipo'] = $tipo;
						$data[$aux]['clase'] = $tablatr;
						$aux++;
					}
				}
			}
		}
		return $data;
	}

	// ELEMENTOS DE COMUNICACION 
	public function con_comunicacion($secciones){
		$origen = $this->all_contenido();
		$data = array();
		$max_seccion = count($secciones)-1;
		$aux = 0;
		foreach ($origen as $row) {
			foreach ($row as $value) {
				if($value['module'] == 4 && $value['section'] == $secciones[$max_seccion]){
					$data[$aux]['id'] = "../mod/chat/view.php?id=".$value['id'];
					$data[$aux]['tipo'] = "chat";
					$data[$aux]['desc'] = "Comunicate con tu tutor de manera rapida y sencilla atraves del Chat, este solo estara disponible en los Horarios que el Tutor indique, asi que consulta los horarios de disponibilidad del tutor.";
					$aux++;
				}
				elseif($value['module'] == 9 && $value['section'] == $secciones[$max_seccion]){
					$data[$aux]['id'] = "../mod/forum/view.php?id=".$value['id'];
					$data[$aux]['tipo'] = "forum";
					$data[$aux]['desc'] = "Con la ayuda de los foros, podras generar inquietudes para que el tutor o tus compañeros del curso puedan resolver.";
					$aux++;
				}
			}
		}
		$data[$aux]['id'] = "../user/index.php?id=".ID_CURSO;
		$data[$aux]['tipo'] = "mensajes";
		$data[$aux]['desc'] = "Através de los mensajes te podras comunicar de manera privada con tu Tutor, funciona de manera similar a un Correo Electronico, pero este es propio de la plataforma Moodle.";
		return $data;
	}

	public function lista_cursos(){
        $data = array();
        $data_del = array();

        $data_user = $this->datos_usuario();
        $result = $this->_db->query("
			SELECT DISTINCT 
			b.id, 
			b.estado,
			a.id AS idhost,
			a.wwwroot,
			d.nombre_curso,
			d.moodle_course_id
			FROM 
			".DB_PRE."mnet_host	a,
			am_usuario_curso    b,
			am_numero_campus    c,
			am_cursos           d,
			am_campus           e
			WHERE     c.codigo_curso = b.codigo_curso
			AND d.codigo_curso = b.codigo_curso
			AND c.n_campus = e.id
			AND c.n_campus = b.n_campus
			AND d.n_campus = c.n_campus
			AND a.wwwroot = e.url_master
			AND b.fecha <> 0
			AND b.idnumber = ".$data_user['codigo_estudiante']."
			ORDER BY b.id ASC");

        if (!$result) {
            printf("Errormessage lista_cursos: %s\n", $this->_db->error);
        }else{
        	$aux = 0;
            $hosts = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($hosts as $host) {

            	if($host['estado'] == 'A'){
            		$n_exist = array_search($host['moodle_course_id'], array_column($data,'id_curso'));

            		if($n_exist === false){
						$data[$aux]['id_curso'] = $host['moodle_course_id'];

						if($host['id'] == 1){
							$data[$aux]['enlace'] = URL_ROOT."/course/view.php?id=".$host['moodle_course_id'];
						}else{
							$data[$aux]['enlace'] = URL_ROOT."/auth/mnet/jump.php?hostid=".$host['idhost']."&course=".$host['moodle_course_id'];		
						}
						$data[$aux]['nombre'] = utf8_encode($host['nombre_curso']);
            		}
            	}
            	else{
            		$data_del = $host['moodle_course_id'];
					$fila = array_search($data_del, array_column($data,'id_curso'));
					unset($data[$fila]);
            	}
            	
            	$aux++;
            }
        }
        
        return $data;

    }

    public function lista_cursos_esclavo(){
        $data = array();
        $data_user = $this->datos_usuario();
        $result = $this->_db->query("
			SELECT c.codigo_curso, c.nombre_curso, a.wwwroot, c.moodle_course_id
			FROM ".DB_PRE."mnet_host       a,
			am_usuario_curso    b,
			am_cursos           c,
			am_campus           d
			WHERE a.wwwroot = d.url_master
			AND c.codigo_curso = b.codigo_curso
			AND a.id > '2'
			AND b.fecha <> 0
			AND b.estado = 'A'
			AND b.idnumber = ".$data_user['codigo_estudiante']
			);
        if (!$result) {
            printf("Errormessage lista_cursos esclavo: %s\n", $this->_db->error);
        }else{
        	$aux = 0;
            $hosts = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($hosts as $host) {
            	if($host['moodle_course_id'] != null && $host['moodle_course_id'] != ID_CURSO){

					$data_host = $this->obtener_idhost($host['moodle_course_id']);
					if($data_host == 1){
					$data[$aux]['enlace'] = URL_ROOT."/course/view.php?id=".$host['moodle_course_id'];
					}else{
					$data[$aux]['enlace'] = $host['wwwroot']."/auth/mnet/jump.php?course=".$host['moodle_course_id'];
					}
					$data[$aux]['nombre'] = utf8_encode($host['nombre_curso']);	
					$aux++;	
					
            	}
        	}
        }
        return $data;
    }


    public function obtener_idhost($id_curso){
    	$data = 0;
    	$result = $this->_db->query("
			SELECT a.id FROM ".DB_PRE."mnet_host a, am_campus b, am_cursos c
			WHERE a.wwwroot = b.url_master
			AND b.id = c.n_campus
			AND c.moodle_course_id =".$id_curso
			);

		if (!$result) {
		    // printf("Errormessage lista_cursos host: %s\n\n\n", $this->_db->error);
		}else{
		    $hosts = $result->fetch_all(MYSQLI_ASSOC);
		    foreach ($hosts as $host) {
		    	if($host['id'] != null){	
		    		$data = $host['id'];
		    	}
			}
		}
		return $data;
    }

}

// LECTURAS
$t_lectura = "La mejor forma de interiorizar los conocimientos es leyendo así puedes sacar tus propias conclusiones y aprender mucho más, lee y participa.";
$i_lectura = "../interfaz/imgs/lecturas.png";

// VIDEOS
$t_video = "En estos videos encuentras los contenidos de la semana, es aquí en donde se concentra tu proceso de aprendizaje y te brinda las bases para el desarrollo de las demás actividades.";
$i_video = "../interfaz/imgs/videos.png";

// MATERIAL DIDACTICO
$t_mat_didac = "Aquí encuentras el material de apoyo con el cual puedes desarrollar las actividades que ayudan a reforzar tus competencias.";
$i_mat_didac = "../interfaz/imgs/material.png";

// ACTIVIDADES DIDACTICAS
$t_act_didac = "Desarrollando las actividades didácticas, practicas lo aprendido y fortaleces tus conocimientos.";
$i_act_didac = "../interfaz/imgs/taller.png";

// WIKI
$t_wiki = "Wiki - Trabajo colaborativo";
$i_wiki = "../interfaz/imgs/wiki.png";

$data_add = array(
	array('descripcion' => "",'icono' => ""),
	array('descripcion' => "",'icono' => ""),
	array('descripcion' => $t_lectura,'icono' => $i_lectura),
	array('descripcion' => $t_video,'icono' => $i_video),
	array('descripcion' => $t_mat_didac,'icono' => $i_mat_didac),
	array('descripcion' => $t_act_didac,'icono' => $i_act_didac),
	array('descripcion' => $t_wiki,'icono' => $i_wiki),
	);


?>
