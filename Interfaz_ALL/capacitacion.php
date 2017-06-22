<meta name="viewport" content="width=device-width, initial-scale=0.5"/>
<!--Import Google Icon Font-->
<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!--Import materialize.css -->
<link type="text/css" rel="stylesheet" href="../interfaz/materialize/css/capacitacion.css"  media="screen,projection"/>
<link type="text/css" rel="stylesheet" href="../interfaz/materialize/css/materialize2.css"  media="screen,projection"/>
<link type="text/css" rel="stylesheet" href="../interfaz/materialize/css/animations.css"  media="screen,projection"/>

<script type="text/javascript" src="../interfaz/materialize/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../interfaz/materialize/js/capacitacion.js"></script>
<script type="text/javascript" src="../interfaz/materialize/js/materialize.js"></script>

<?php 
require_once("php/conexion.php");//conexion BD
require_once("php/config_capacitacion.php");//conexion BD
?>
<div class="cuerpo_interfaz">
<input type="hidden" id="flag_eval" value="<?php // echo $valor_eval; ?>">
<input type="hidden" id="flag_tuto" value="<?php echo $instrucciones; ?>">

<div id="modal_instrucciones" class="modal hide fade in" data-keyboard="true" data-backdrop="false" style="display: none;">
	<div class="modal-header">
		<h1>Instrucciones</h1>
	</div>
	<div class="modal-body">

	Bienvenidos a nuestro campamento de tutores, a continuación, encontraran algunas recomendaciones para la realización de este curso.
	<br><br>
	<ul>
		<li>
		<b>1.</b>	Cómo funciona el curso:  Es bastante sencillo, solo debes dar clic en cada uno de los mundos, al ingresar te encontrarás con una serie de videos que te explicarán paso a paso el uso de las herramientas y servicios con los que contarás, una vez veas todos los contenidos tendrás un quiz con una o dos preguntas las cuales evaluarán lo aprendido en los videos, si respondes de manera correcta, el sistema te entregará una medalla como recompensa a tu trabajo.<br><br>
		<center>
		<video src="../interfaz/imgs/instruccion_1.mp4" type="video/mp4" width="90%" controls></video>
		</center>
		</li>
		<br>
		<li>
		<b>2.</b>	Cada uno de los mundos contiene un examen “Evaluación Mundo” con la cual validarás todo lo aprendido en cada uno de los videos, ten en cuenta que esta actividad tendrá 2 intentos, para poder continuar al siguiente mundo debes aprobar el quiz con una nota mínima de 3.0, el tiempo de presentación en cada uno de los intentos es de 12 minutos, estos exámenes tienen fechas de inicio para su presentación así que revísalas antes de presentarlo; Al aprobar la actividad se te entregarán unas estrellas de acuerdo a tu nota, estas te permitirán estar en la espacio de <b>Tutores Destacados</b>.
		<br><br>
		<center>
		<video src="../interfaz/imgs/instruccion_2.mp4" type="video/mp4" width="90%" controls></video>
		</center>
		</li>
		<br>
		<li>
		<b>3.</b>	Una vez pases todos los mundos podrás descargar un certificado con todas tus insignias ganadas y las notas obtenidas en cada una de ellas, ten en cuenta que este certificado tendrá una vigencia y esta la encontrarás en la parte inferior, este documento se te solicitará para la contratación como tutor de nuestra corporación en cada semestre.<br><br>
		<center><img src="../interfaz/imgs/instruccion_3.png" ></center>
		</li>
		<br>
		<li>
		<b>4.</b>	Anímate a ser el mejor, esto te pondrá en nuestro podio de tutores destacados.
		<center><img id="Top_Estudiantes" src="../interfaz/imgs/top.png" ></center>
		</li>
	</ul>
	</div>
	<div class="modal-footer">
		<input type="button" class="modal-action modal-close waves-effect" value="Entendido"/>
	</div>
</div>

<div id="modal_creditos" class="modal hide fade in" data-keyboard="false" data-backdrop="static" style="display: none;">
	<div class="modal-header">
		<h1>Creditos</h1>
	</div>
	<div class="modal-body">
	<center>
	<img src="../interfaz/imgs/logo_campus.png" width="40%"/> 
	</center>
	<br><br>
		<h5>Desarrollado e implementado por el equipo de Educacion Virtual de la Corporacion Universitaria Republicana</h5>
	</div>
	<div class="modal-footer">
		<input type="button" class="modal-action modal-close waves-effect" value="Entendido"/>
	</div>
</div>

	<div id="interfaz_capacitacion" class="row">
		<div id="sidebar">
		</div>

		<!-- INICIO -->
		<div id="inicio" class="centered">
			<img width="100%" src="../interfaz/imgs/intro.png">
			<!--
			<h1>Capacitacion de Docentes</h1>
			<button onclick="iniciar()">START</button>
			-->
			<img id="jugar" src="../interfaz/imgs/jugar.png" onclick="iniciar()">
			<img id="creditos" src="../interfaz/imgs/creditos.png" onclick="open_modal(this.id)">
			<img id="instrucciones" src="../interfaz/imgs/info.png" onclick="open_modal(this.id)">
			
		</div>

		<!-- MUNDOS -->
		<div id="mundos" class="ocultar">

			<div id="linea"></div>
			<div id="div_barra_panel">
			<img id="instrucciones_2" title="instrucciones" src="../interfaz/imgs/info.png" class="tooltipped" data-position="bottom" data-delay="40" data-tooltip="Instrucciones" onclick="open_modal(this.title)">

			<img id="Top_Estudiantes" src="../interfaz/imgs/top.png" class="tooltipped" data-position="bottom" data-delay="40" data-tooltip="Tutores Destacados" onclick="top_est()">

			<div id="Mejores_Estudiantes" class="ocultar">
				<?php 
				while ($reg_top = $res_top ->fetch_array()){
					$picture = $reg_top['picture'];
					$nombre = $reg_top['firstname']." ".$reg_top['lastname'];
					if($picture != "0"){
						//Consulta de Imagen
						$imgs = "select contextid, picture from ".$prefijo."user, ".$prefijo."files 
						where ".$prefijo."user.picture = '$picture' AND 
						".$prefijo."user.picture = ".$prefijo."files.id";

						$res_imgs = $conexion->query($imgs) or die ("ERROR ".$mysqli->error);
						while($reg_imgs = $res_imgs ->fetch_array()){
							$contextid = $reg_imgs['contextid']; 
							$picture = "../pluginfile.php/".$contextid."/user/icon/aardvark/f1?rev=".$reg_imgs['picture'];
						}
					}
					else{
						$picture = "../interfaz/imgs/docente_foto.png";
					}
					print("
					<a class='tooltipped' data-position='bottom' data-delay='40' data-tooltip='$nombre'>
						<img src='$picture' class='img-circle'>
					</a>
					");
				}
				?>
			<br>
			</div>

			<a title="Descargar Certificado" href="<?php echo $accion; ?>" id="certificado" data-position="bottom" data-delay="40" data-tooltip="Descargar Certificado" class="tooltipped">
				<img class="<?php echo $genera_cert; ?>" src="../interfaz/imgs/certificado.png">
			</a>

			</div>


			<?php
			for ($i=1; $i < $n_mundos; $i++){
				$p = $i-1;
				$p_n = round($puntajes[$p]/20, 0, PHP_ROUND_HALF_DOWN);
				$habilita = ""; 
				if($i > $mundo){
					$habilita = "disabled";
				}
				print("
				<a class='$habilita' id='$i' onclick='niveles(this.id)'>
					<div id='mundo_$i' class='centered'>
						<img class='img_mundo' src='../interfaz/imgs/mundo_$i.png'>
						<div class='estrellas'>");
						for ($e=0; $e < 5; $e++) {
						  if($e < $p_n){
						    print("<img src='../interfaz/imgs/win.png'>");
						  }
						  else{
						   print("<img src='../interfaz/imgs/lose.png'>");
						  }
						}
						print("
							<div class='nombre_mundo'><b>$name_mundos[$i]</b></div>
						</div>
					</div>
				</a>
				");
			}
			?>
			<form action="../interfaz/certificado/certificado.php" method="POST" enctype="multipart/form-data" name="Form_Certificado">
				<input type="hidden" name="nombre" value="<?php echo $nombre_usuario; ?>">
				<input type="hidden" name="fecha" value="<?php echo date("d / M / Y"); ?>">
				<input type="hidden" name="n_notas" value="<?php echo count($puntajes); ?>">
				<?php 
				for($w=0; $w < count($puntajes); $w++){
				$c_n = $w+1;
					print("
					<input type='hidden' name='mundo_$w' value='$name_mundos[$c_n]'>
					<input type='hidden' name='nota_$w' value='$puntajes[$w]'>
					");
				}
				?>
				<center>
				</center>
			</form>
			<img id="docente" src="../interfaz/imgs/docente.png">
		</div>


		<!--DASH BOARD MUNDOS-->
		<?php
		for ($j=1; $j < $n_mundos; $j++){
			$aux_nivel = 0;
			print("
				<div id='niveles_mundo_$j' class='mundo dash_board centered ocultar'>
					<a name='$j' onclick='volver_mundos(this.name)'><img src='../interfaz/imgs/atras.png' class='atras' ></a>
					
					<div class='logo_mundo'><center><img src='../interfaz/imgs/mundo_$j.png'></center></div>
					<div class='cont_mundo'>
						<h1 class='titulo_mundo'>$name_mundos[$j]</h1><br><br>");
					for ($h=0; $h < 10; $h++) {
						$tema1 = ${'matriz_'.$ids_seccion[$j]}[$h][1];
						$tema2 = ${'matriz_'.$ids_seccion[$j]}[$h-1][1];
						if(strcmp($tema1, $tema2) != 0 && strcmp($tema1, "Quiz") != 0){
							$niveles[$nivel_cont] = $tema1;
							$nivel_cont++;
							$con_medalla = "SELECT * FROM cap_nivel_user WHERE id_usuario = '$id_usuario' AND nivel = '$tema1'";
							$res_medalla = $conexion->query($con_medalla) or die ("ERROR ACTIVIDADES");
							$reg_medalla = count($res_medalla ->fetch_array());
							$medalla=""; 
							if($reg_medalla > 0){$medalla = "<img src='../interfaz/imgs/medalla.png'>";}
							print("<a class='medalla' title='$tema1' onclick='actividades(this.title)' id='respuesta_$tema1' >$tema1 $medalla</a><br><br>");
						}
						if(strcmp($tema1, "Quiz") == 0){
							$Nombre_Quiz =  trim(${'matriz_'.$ids_seccion[$j]}[$h][2]);
							$URL_Quiz =  trim(${'matriz_'.$ids_seccion[$j]}[$h][3]);
							print("<h2><a href='$URL_Quiz' class='examen_mundo'>$Nombre_Quiz</a></h2>");
						}
					}
			print("
					</div>
				</div>");
		}
		?>

		<?php
		for ($j=1; $j < $n_mundos; $j++){

			$seccion = $ids_seccion[$j];
			$n_niveles = count(${'niveles_'.$seccion});

			for ($h=1; $h < $n_niveles; $h++){
				$id_nivel = trim(${'niveles_'.$seccion}[$h]); 
				//PRUEBA
				//echo $id_nivel."-".$n_niveles."<br>"; 

				print("
				<div id='$id_nivel' class='ocultar centered nivel'>
					<a name='$j' onclick='volver_nivel(this.name)'><img src='../interfaz/imgs/atras.png' class='atras' ></a><br><br>
					<h1>$id_nivel</h1>
				<div>
					<div>
						<ul class='tabs ts_aux'>");
							for ($i=0; $i < 20; $i++){
								if(strcmp(${'matriz_'.$seccion}[$i][1], $id_nivel) == 0){
									print("<li class='tab ts_menu'><a href='#$i-$id_nivel'>".${'matriz_'.$seccion}[$i][2]."</a></li>");
								}
							}
							print("<li class='tab ts_menu'><a href='#Quiz_$id_nivel'>Quiz</a></li>
						</ul>
					</div>
					");
					
					for ($p=0; $p < 20; $p++){
						if(strcmp(${'matriz_'.$seccion}[$p][1], $id_nivel) == 0){
						print("
								<div id='$p-$id_nivel' class='centered'>
									<br>
									<iframe width='80%' height='500' src='".${'matriz_'.$seccion}[$p][3]."' frameborder='0' allowfullscreen></iframe>
									<br>
								</div>
						");
						}
					}

					print("
					<div id='Quiz_$id_nivel' class='centered'>
					<br><br>
					<p class='quiz_mundo'>
						Con ayuda de este QUIZ autoevaluaras tus conocimientos sobre lo aprendido en este nivel, al responder las preguntas correctamente 
						obtendras una medalla por cada nivel
						<br><br>
						<img src='../interfaz/imgs/medalla.png'>
						<br><br><br>
						<a title='$id_nivel' class='examen_mundo' onclick='javascript:open_modal_preg(this.title);'>Practica lo Aprendido</a>
						<br><br><br>
					</p>
					</div>");
			print("	
				</div>
			</div>
		");
			}
		}
		?>
	</div>
<!-- MODAL -->
	<?php 
	for ($i=0; $i < count($niveles); $i++) { 
		$nivel_tema = $niveles[$i];
		$query_ques  = "SELECT * FROM cap_preguntas WHERE nivel = '$nivel_tema'";
		$res_ques = $conexion->query($query_ques) or die ("Error quiz". $mysqli->error);
		$aux_preg = 1;
		while($reg_ques = $res_ques ->fetch_array()){
			print("
			<div id='".$aux_preg."modal_$nivel_tema' class='modal hide fade in' data-keyboard='false' data-backdrop='static' style='display: none;'>
			<div class='modal-header'>
			<h3>Pregunta sobre $nivel_tema</h3>
			</div>
			<form name='".$aux_preg."$nivel_tema' id='".$aux_preg."formulario_$nivel_tema' method='POST'> 
			<div class='modal-body'>
			");
					
					$Pregunta_UTF = utf8_encode($reg_ques['pregunta']);
					$Opcion1_UTF = utf8_encode($reg_ques['opcion_1']);
					$Opcion2_UTF = utf8_encode($reg_ques['opcion_2']);
					$Opcion3_UTF = utf8_encode($reg_ques['opcion_3']);
					$Opcion4_UTF = utf8_encode($reg_ques['opcion_4']);
					
					print("
					<input type='hidden' id='".$aux_preg."usuario_$nivel_tema' value='$id_usuario'>
					<input type='hidden' id='".$aux_preg."user_game_$nivel_tema' value='$id_docente'>
					<input type='hidden' id='".$aux_preg."user_db_$nivel_tema' value='$user'>
					<input type='hidden' id='".$aux_preg."pass_db_$nivel_tema' value='$pass'>
					<input type='hidden' id='".$aux_preg."data_db_$nivel_tema' value='$data'>
					<input type='hidden' id='".$aux_preg."pregunta_$nivel_tema' value='$reg_ques[id_pregunta]'>
					
					<b>$Pregunta_UTF</b><br>
					<input type='radio' name='".$aux_preg."opcion_$nivel_tema' value='1' checked>$Opcion1_UTF<br>
					<input type='radio' name='".$aux_preg."opcion_$nivel_tema' value='2' >$Opcion2_UTF<br>
					<input type='radio' name='".$aux_preg."opcion_$nivel_tema' value='3' >$Opcion3_UTF<br>
					<input type='radio' name='".$aux_preg."opcion_$nivel_tema' value='4' >$Opcion4_UTF<br>
					<input type='hidden' id='".$aux_preg."rta_$nivel_tema' value='$reg_ques[respuesta]'>
					");
					
					print("
			</div>
			<div class='modal-footer'>
				<input id='enviar_$nivel_tema' name='$aux_preg' title='$nivel_tema' type='button' class='btn' value='Responder' onclick='enviarDatos(this.name, this.title)'/>
			</div>
			</form>
			</div>
			");
			$aux_preg++;
		}
					 
	}
	?>

<div id="modal_evaluacion_final" class="modal hide fade in" data-keyboard="true" data-backdrop="false" style="display: none;">
	<div class="modal-header">
		<h1>Evaluación</h1>
	</div>
	<div class="modal-body">
	Gracias por participar en nuestro campamento de tutores, a continuación una pequeña evaluación para que califiques como te parecio el curso.  
	<br>
	
	<form method="POST" action="../interfaz/php/respuesta.php">

		<input type="hidden" name="evaluacion" id="evaluacion" value="evaluacion" />
		<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>" />
		<input type="hidden" name="docente" id="docente" value="<?php echo $nombre_usuario; ?>" />
		<input type="hidden" name="user_db" id="user_db" value="<?php echo $user; ?>" />
		<input type="hidden" name="pass_db" id="pass_db" value="<?php echo $pass; ?>" />
		<input type="hidden" name="data_db" id="data_db" value="<?php echo $data; ?>" />
		
		<center>
			<table id="tabla_calificaciones">
				<tr>
					<th></th>
					<th>1</th>
					<th>2</th>
					<th>3</th>
					<th>4</th>
					<th>5</th>
				</tr>

				<tr>
					<th>Mundo 1</th>
					<td><input type="radio" name="mundo1" value="1"></td>
					<td><input type="radio" name="mundo1" value="2"></td>
					<td><input type="radio" name="mundo1" value="3" checked></td>
					<td><input type="radio" name="mundo1" value="4"></td>
					<td><input type="radio" name="mundo1" value="5"></td>
				</tr>

				<tr>
					<th>Mundo 2</th>
					<td><input type="radio" name="mundo2" value="1"></td>
					<td><input type="radio" name="mundo2" value="2"></td>
					<td><input type="radio" name="mundo2" value="3" checked></td>
					<td><input type="radio" name="mundo2" value="4"></td>
					<td><input type="radio" name="mundo2" value="5"></td>
				</tr>

				<tr>
					<th>Mundo 3</th>
					<td><input type="radio" name="mundo3" value="1"></td>
					<td><input type="radio" name="mundo3" value="2"></td>
					<td><input type="radio" name="mundo3" value="3" checked></td>
					<td><input type="radio" name="mundo3" value="4"></td>
					<td><input type="radio" name="mundo3" value="5"></td>
				</tr>

				<tr>
					<th>Mundo 4</th>
					<td><input type="radio" name="mundo4" value="1"></td>
					<td><input type="radio" name="mundo4" value="2"></td>
					<td><input type="radio" name="mundo4" value="3" checked></td>
					<td><input type="radio" name="mundo4" value="4"></td>
					<td><input type="radio" name="mundo4" value="5"></td>
				</tr>
			</table>
			<br>
			<label><b>Observaciones Generales</b></label>
			<textarea name="observacion"></textarea>
			<br>
			<input type="submit" name="enviar" value="Enviar Respuesta">
		</center>
	</form>
	</div>
</div>






