<?php 
require_once("php/conexion.php");//conexion BD
require_once("php/config_interfaz.php");//conexion BD
?>
<meta name="viewport" content="width=device-width, initial-scale=0.5"/>
<!--Import Google Icon Font-->
<link type="text/css" rel="stylesheet" href="../interfaz/materialize/css/icon.css"  media="screen,projection"/>
<!--Import materialize.css -->
<link type="text/css" rel="stylesheet" href="../interfaz/materialize/css/interfaz.css"  media="screen,projection"/>
<link type="text/css" rel="stylesheet" href="../interfaz/materialize/css/materialize2.css"  media="screen,projection"/>
<link type="text/css" rel="stylesheet" href="../interfaz/materialize/css/animations.css"  media="screen,projection"/>

<script type="text/javascript" src="../interfaz/materialize/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../interfaz/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../interfaz/materialize/js/interfaz.js"></script>


<!-- VENTANA MODAL -->

<div id="Encuesta" class="modal hide fade in" data-keyboard="false" data-backdrop="static" style="display: none;">
	<div class="modal-header">
		<a class="close_modal right" onclick="cerrar_encuesta()" ><h2 class="fa fa-times"></h2></a>
		<h3><?= $encuesta_reg['nombre']; ?></h3>
		<p><?= utf8_encode($encuesta_reg['descripcion']); ?></p>
	</div>
	<div class="modal-body">
	
	<form method="POST" action="../interfaz/php/encuesta.php">
	<input type="hidden" name="user_db" value="<?= $user_db; ?>"/>
	<input type="hidden" name="pass_db" value="<?= $pass_db; ?>"/>
	<input type="hidden" name="data_db" value="<?= $data_db; ?>"/>

	<input type="hidden" id="id_curso" name="id_curso" value="<?= $curso; ?>"/>
	<input type="hidden" id="id_user" name="id_user" value="<?= $id_user_log; ?>"/>
	<?php
		$preguntas = "SELECT * FROM aa_preguntas WHERE id_encuesta = '$encuesta_reg[id_encuesta]' ";
		$preguntas_res = $conexion->query($preguntas) or die ("ERROR Pregunta".$mysqli->error);
		while ($preguntas_reg = $preguntas_res ->fetch_array()){
			// DESCRIPCION DE LA ENCUESTA
			print("<p>".utf8_encode($preguntas_reg['pregunta'])."</p>");
			// TIPO SELECCION MULTIPLE
			if($preguntas_reg['tipo'] == 1){
				$opciones = "SELECT * FROM aa_opciones WHERE id_pregunta = '$preguntas_reg[id_pregunta]'";
				$opciones_res = $conexion->query($opciones) or die ("ERROR OPCIONES".$mysqli->error);
				while ($opciones_reg = $opciones_res ->fetch_array()){
					print("<input type='radio' name='$preguntas_reg[id_pregunta]' value='$opciones_reg[id_opcion]' required/>$opciones_reg[opcion] </br>");
				}
			}
			// TIPO DE PREGUNTA ABIERTA
			if($preguntas_reg['tipo'] == 2){
					print("<textarea placeholder='Escribe aqui...' cols='70' rows='3' name='$preguntas_reg[id_pregunta]' required></textarea>");
			}
			print("<br>");
		}
	 ?>              
	</div>
	<div class="modal-footer">
		<input type="submit" class="btn right" value="Guardar"/>
	</div>
	</form>
</div>
<input type="hidden" value="<?= $cont_valida ?>" id="flag_encuesta">

<!-- FIN VENTANA MODAL -->
<div class="cuerpo_interfaz">
	<!-- BODY -->
	<div class="row">
		<!-- MENU PANEL -->         
		<div class="col s3">
			<div class="panel border_bajo">
				<div class="usuario">
					<?php print ("<h3>Bienvenido<br>$nombre_usuario</h3>") ?>
					<!-- <h2><a data-toggle="modal" href="#" onclick="abrir_encuesta()">Encuesta</a></h2> -->
				</div>
				<ul class="tabs menu_panel">
					<!-- INICIO-->
					<li class="tab submenu_panel">
						<a href="#inicio" class="active" onclick="oculta_panel()">
							<i class="material-icons">home</i><u>Inicio</u>
						</a>
					</li>
					
					<!-- CONTENIDO -->
					<li class="tab submenu_panel">
						<!--Nivel 1-->
						<a title="link_contenido" href="#contenido" onclick="mostrar(this.title)">
							<i class="material-icons">book</i><u>Contenido</u>
						</a>
						<!--Nivel 2-->
						<ul id="link_contenido" class="tabs ocultar">
							<?php 
							print("
								<li class='tab submenu_panel menu2'>
									<a href='#contenido_$ids_seccion[1]' onclick='oculta_panel()'>
										<i class='material-icons'>description</i><u>Aspectos Generales</u>
									</a>
								</li>");
							
							for ($ax=1; $ax <= 4 ; $ax++){
								
								$s_1 = $ax*2;
								$s_1_s = $s_1+1;

								$s_2 = $ax*2-1;
								$s_2_s = $s_2+1;
								
								print("
									<li title='nav_v_inicio_$ax' class='tab submenu_panel menu2' onclick='navegacion(this.title)'>
										<a title='modulo$ax' href='#v_inicio_$ax' onclick='mostrar(this.title)'>
											<i class='material-icons'>view_list</i><u>Modulo $ax</u>
										</a>
									</li>
									<ul id='modulo$ax' class='ocultar menu3'>
										<li id='nav_v_inicio_$ax' class='nav_menu' onclick='navegacion(this.id)'>
											<a href='#v_inicio_$ax' onclick='oculta_panel()'><u class='semanas'>Video Intro</u></a>
										</li>
										<li id='nav_semana1_$ax' class='nav_menu' onclick='navegacion(this.id)'>
											<a href='#contenido_$ids_seccion[$s_2_s]' onclick='oculta_panel()'><u class='semanas'>Semana $s_2</u></a>
										</li>
										<li id='nav_semana2_$ax' class='nav_menu' onclick='navegacion(this.id)'>
											<a href='#contenido_$ids_seccion[$s_1_s]' onclick='oculta_panel()'><u class='semanas'>Semana $s_1</u></a>
										</li>
										<li id='nav_v_final_$ax' class='nav_menu' onclick='navegacion(this.id)'>
											<a href='#v_final_$ax'><u class='semanas' onclick='oculta_panel()'>Video Cierre</u></a>
										</li>
									</ul>
									");
							}
							
							?>
						</ul>
					</li>
					<li class="tab submenu_panel" onclick="oculta_panel()">
						<?php
						$pulse = "";
						$sonido = ""; 
						if ($cont_act > 0){
							$pulse =  "pulse";
							$sonido = "<audio src='../interfaz/imgs/sonido.ogg' autoplay />";
						} 
						?>
						<a href="#actividades">
							<i class="material-icons <?php echo $pulse; ?>">date_range</i><u>Actividades Valorativas</u>
							<?php echo $sonido; ?>
						</a>
					</li>

					<!-- 					
					<li class="tab submenu_panel">
						<a href="../grade/report/index.php?id=<?= $curso ?>" target="_blank" onclick="ver_calificacion(this.href)">
							<i class="material-icons">playlist_add_check</i><u>Calificaciones</u>
						</a>
					</li> 
					-->
					

					<li class="tab submenu_panel" onclick="oculta_panel()">
						<a href="#comunicacion">
							<i class="material-icons">forum</i><u>Comunicación</u>
						</a>
					</li>

					<?php
					for ($a1=1; $a1 < $n_secciones ; $a1++){
						print("<li class='tab submenu_panel oculto'><a href='#contenido_$ids_seccion[$a1]'></a></li>");
					}
					?>

					
					
				</ul>               
				<!-- BOTON AYUDA -->
				<div class="ribbon-wrapper">
					<div class="ribbon-front">
						<div class="fixed-action-btn horizontal">
							<a class="btn-floating">
								<i class="large material-icons">arrow_forward</i>
							</a>
							<ul>
								<?php
								$clase_boton = "";
								if($rol_usuario == ""){
									?>
									<li>
										<a target="_blank" href="http://aulas.campusvirtualurepublicana.edu.co/course/view.php?id=3" class='btn-floating green tooltipped' data-position='bottom' data-delay='50' data-tooltip='Curso Capacitacion'>
											<i class='material-icons'>video_library</i>
										</a>
									</li>
									<?php 
									}else{
									?>

									<!--

									<li>
										<a class="btn-floating yellow darken-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Asuntos Financiaros">
											<i class="material-icons">attach_money</i>
										</a>
									</li>
									<li>
										<a class="btn-floating red darken-1 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Asuntos Academicos">
											<i class="material-icons">school</i>
										</a>
									</li>
									<li>
										<a class="btn-floating blue tooltipped" data-position="bottom" data-delay="50" data-tooltip="Biblioteca">
											<i class="material-icons">local_library</i>
										</a>
									</li>

									-->

									<?php } ?>

									<!-- GENERALES -->

									<li>
										<a target="_blank" href="http://campusvirtualurepublicana.edu.co/soporte/" class="btn-floating red tooltipped" data-position="bottom" data-delay="50" data-tooltip="Mesa de Ayuda">
											<i class="material-icons">help_outline</i>
										</a>
									</li>

									<li>
										<a target="_blank" href="https://outlook.office.com/owa/?realm=urepublicana.edu.co" class="btn-floating blue darken-3 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Correo Institucional">
											<i class="material-icons">email</i>
										</a>
									</li>

									<li>
										<a class="btn-floating yellow darken-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="Como usar la Interfaz" target="_blank" href="http://campusvirtualurepublicana.edu.co/info_moodle/">
											<i class="material-icons">live_help</i>
										</a>
									</li>


								</ul>
							</div>
						</div>
						<div class="ribbon-edge-topleft"></div>
					</div>
					<br>
				</div>          
			</div>
			<div id="panel_contenido" class="col s9">
				<!-- HEADER CONTENIDO -->
				<div class="row border_bajo">
					<div class="col s10">
						<?php 
						$top_cnt = mysqli_num_rows($res_top);
						$style_not = "min-height: 100%;"; 
						if($top_cnt == 5){
							$style_not = "min-height: 88%;"; 
							print("
								<h3>Estudiantes destacados del Curso</h3>
								<center>
									<table style='width: 50% !important;'>
										<tr>
											"); 
										$num = 0; 
										while ($reg_top = $res_top ->fetch_array()){

											$picture = $reg_top['picture'];
											$nombre = utf8_encode($reg_top['firstname']." ".$reg_top['lastname']);
											if($picture != "0"){
												$imgs = "select contextid, picture from ".$prefijo."user, ".$prefijo."files
												where ".$prefijo."user.picture = '$picture' AND ".$prefijo."user.picture = ".$prefijo."files.id";

												$res_imgs = $conexion->query($imgs) or die ("ERROR ".$mysqli->error);
												while($reg_imgs = $res_imgs ->fetch_array()){
													$contextid = $reg_imgs['contextid']; 
													$picture = "../pluginfile.php/".$contextid."/user/icon/aardvark/f1?rev=".$reg_imgs['picture'];
												}
											}
											else{
												$picture = "../interfaz/imgs/estudiante.png";
											}
								print("
									<td width='20%'>
										<a class='tooltipped' data-position='bottom' data-delay='40' data-tooltip='$nombre'>
											<img src='$picture' class='img-circle foto_est'>
										</a>
									</td>
									");
							// picture  firstname   shortname   finalgrade == http://moodle/pluginfile.php/??/user/icon/clean/f2?rev=$reg_top[firstname]
							}
							print("</tr>
						</table>
					</center>");
						}else{
							print("<br><br>");
						}
						?>
					</div>
					<div class="col s2 btn-noticias">
						<a onclick="aparece()">
							<i class="material-icons campana">notifications</i>
							<span class="notificacion"><?php echo $cont_noticias; ?></span>
						</a>
					</div>
				</div>
				<div class="row">
					<!-- PANEL DE NOTICIAS Y ALERTAS-->
					<div id="noticias" class="oculto" style="<?php echo $style_not; ?>">
						<div class="row">
							<div class="col s12">
								<h2>Noticias</h2>
								<table width="100%" class="bordered resalta">
									<?php 
									while ($noticias_reg = $noticias_res ->fetch_array()){
										$resumen = substr($noticias_reg[resumen], 0, 100)."...";
										print("
											<tr>
												<td width='25%' align='center'>
													<a href='$url_campus/pages/ver_noticia.php?id=$noticias_reg[id_noticia]&pagina=campus' target='_blank'>
														<img src='$url_campus/admin/imgs_news/$noticias_reg[imagen]'>
													</a>
												</td>
												<td width='75%'>
													<a href='$url_campus/pages/ver_noticia.php?id=$noticias_reg[id_noticia]&pagina=campus' target='_blank'>
														<h4>$noticias_reg[titular]</h4>
													</a>
													<em>
														$resumen
													</em>
													<br>
													<span class='fecha_not'> 
														Publicación :$noticias_reg[fecha_publicacion]
													</span>
												</td>
											</tr>
											");
									}
									
									?>
								</table>
								<?php print("<a  class='fecha_not' href='$url_campus/pages/noticias.php' target='_blank'><h3>Ver todas</h3></a>");?>
							</div>
						</div>
					</div>
					<!-- CONTENIDOS PANEL -->
					
					<!-- INICIO -->
					<div id="inicio">
						<?php
						$id_inicio = $ids_seccion[1];
						for ($t=0; $t < 5; $t++){
							$inicio = explode('%_%',${'matriz_'.$id_inicio}[1][$t]);
							$video = $inicio[0];
							$nombre_video = $inicio[1];
							$desc_video = $inicio[2]; 
							if($video != ""){
								print("
									<center>
										<div class='inicio'>
											<h2>$nombre_video</h2>
											<iframe width='100%' height='400' src='$video' frameborder='0' allowfullscreen></iframe><br><br>
											$desc_video
										</div>
									</center>
									");
							}
						}
						?>
					</div>
					<div id="modulo"></div>
					
					<!-- FORMA DE ESTUDIO -->
					<div id="contenido">
						<img src="../interfaz/imgs/forma_estudio.jpg" width="100%">
					</div>
					
					
					<!-- CONTENIDO -->
					<?php
					
					for($v=1; $v<=4; $v++){

					// VIDEO_INICIO
						print("
							<div id='v_inicio_$v' >
								<center>
									<div class='inicio'>
										<br>
										Este es el inicio de tu modulo
										<br><br>
										");
						for ($i_f=0; $i_f <= count($intros_finales); $i_f++) { 
							$partes = explode('%_%',$intros_finales[$i_f]);
								// Valida que es Modulo
							if (strpos($partes[0],"".$v."") == true){
									//Inicio o Final
								if(strpos($partes[0],"I") == true){
									$partes_max = count($partes)-1;
										//Video o Recurso
									$datos = $partes[1];
									if(strcmp($partes[$partes_max],"video") == 0){
										print("<iframe width='100%' height='450' src='$datos' frameborder='0' allowfullscreen></iframe><br><br>");
									}else{
										$icono = "<img src='../interfaz/imgs/objetivo.png' width='40px'>"; 
										print("
											<br>
											<a target='_blank' href='../mod/resource/view.php?id=$datos'>
												$icono<i>$partes[2]</i>
											</a>
											<br>
											");       
									}
								}
							}
						}
						print("
					</div>
				</center>
			</div>");


					// VIDEO_FINAL
						print("
							<div id='v_final_$v' >
								<center>
									<div class='inicio'>
										<br>
										Este es el Final de tu modulo
										<br><br>
										");
						for ($i_f=0; $i_f <= count($intros_finales); $i_f++) { 
							$partes = explode('%_%',$intros_finales[$i_f]);
								// Valida que es Modulo
							if (strpos($partes[0],"".$v."") == true){
									//Inicio o Final
								if(strpos($partes[0],"F") == true){
									$partes_max = count($partes)-1;
										//Video o Recurso
									$datos = $partes[1];
									if(strcmp($partes[$partes_max],"video") == 0){
										print("<iframe width='100%' height='450' src='$datos' frameborder='0' allowfullscreen></iframe><br><br>");
									}else{
										$icono = "<img src='../interfaz/imgs/objetivo.png' width='40px'>"; 
										print("
											<br>
											<a target='_blank' href='../mod/resource/view.php?id=$datos'>
												$icono<i>$partes[2]</i>
											</a>
											<br>
											");       
									}
								}
							}
						}
						print("
					</div>
				</center>
			</div>");

					}
					
				//ASPECTOS GENERALES
					print("
						<div id='contenido_$ids_seccion[1]'>
							<center>
							<h2>Aspectos Generales</h2>
							<p>Aqui encontraras los documentos que definen el curso</p>
							</center>
							<div class='contenido'>");
						for ($j=0; $j < 10; $j++) {
							$inicio = explode('%_%',${'matriz_'.$ids_seccion[1]}[2][$j]);
							$datos = $inicio[0];
							$nombres = utf8_encode($inicio[1]);
							$descrip = utf8_encode($inicio[2]); 
							
							$icono = "<img src='../interfaz/imgs/archivos.png'>"; 
							
							if($datos != ""){
							//RECURSOS
								print("
									<br><a class='aspectos' target='_blank' href='$datos'>
									$icono<em>$nombres</em>
								</a><br>");
								
							}
						}
						
						print("
					</div>
				</div>
				");
						
						for ($k=2; $k < $n_secciones ; $k++){
							$varaux = $ids_seccion[$k];
							for ($h=1; $h <= 6; $h++) { 
								${'habilitar_'.$varaux}[$h] = "disabled";
								for ($l=0; $l<count(${'matriz_'.$varaux}) ; $l++) { 
									if(!empty(${'matriz_'.$varaux}[$h][$l])){
										${'habilitar_'.$varaux}[$h] = "";
										$l = count(${'matriz_'.$varaux});
										break;
									}
								}   
							}
							$semana = $k - 1;
							print("
								<div id='contenido_$varaux'>
									<center><h2>Semana $semana</h2></center>
									<div>
										<ul class='tabs ts_aux'>
											
											<li class='tab ts_menu ".${'habilitar_'.$varaux}[2]."'><a href='#seccion2_$varaux'>Lecturas</a></li>
											<li class='tab ts_menu ".${'habilitar_'.$varaux}[5]."'><a href='#seccion5_$varaux'>Videos</a></li>
											<li class='tab ts_menu ".${'habilitar_'.$varaux}[4]."'><a href='#seccion4_$varaux'>Material Didactico</a></li>
											<li class='tab ts_menu ".${'habilitar_'.$varaux}[3]."'><a href='#seccion3_$varaux'>Actividades Didacticas</a></li>
											<li class='tab ts_menu ".${'habilitar_'.$varaux}[6]."'><a href='#seccion6_$varaux'>Wiki</a></li>
										</ul>
									</div>
									");
							for ($i=2; $i <= 6; $i++) {
								print("<div id='seccion".$i."_".$varaux."' class='alto_jjma'>
									<div class='intro_cont'>");
									
							//LECTURAS
									if($i == 2){
										print("<p><br>
												La mejor forma de interiorizar los conocimientos es leyendo así puedes sacar tus propias conclusiones y aprender mucho más, lee y participa.  
											</p>");
										$icono = "<img src='../interfaz/imgs/lecturas.png'>";   
									}
							//TALLERES
									if($i == 3){
										print("<p><br>
											Desarrollando las actividades didácticas, practicas lo aprendido y fortaleces tus conocimientos.
										</p>");
										$icono = "<img src='../interfaz/imgs/taller.png'>";     
									}
							//MATERIAL DIDACTICO
									if($i == 4){
										print("<p><br>
											Aquí encuentras el material de apoyo con el cual puedes desarrollar las actividades que ayudan a reforzar tus competencias.
										</p>
										"); 
										$icono = "<img src='../interfaz/imgs/material.png'>";
									}
							//VIDEOS
									if($i == 5){
										print("<p><br>
											En estos videos encuentras los contenidos de la semana, es aquí en donde se concentra tu proceso de aprendizaje y te brinda las bases para el desarrollo de las demás actividades.
										</p>"); 
										$icono = "<img src='../interfaz/imgs/videos.png'>";
									}
									
									if($i == 6){
										print("<p><br>
											Wiki - Trabajo colaborativo
										</p>"); 
										$icono = "<img src='../interfaz/imgs/wiki.png'>";
									}
									print("
								</div>
								<div class='contenido'>
									<ul class='collapsible' data-collapsible='accordion'>
										");
									for ($j=0; $j < 10; $j++) {
										$inicio = explode('%_%',${'matriz_'.$varaux}[$i][$j]);
										$datos = $inicio[0];
										$nombres = utf8_encode($inicio[1]);
										$descrip = utf8_encode($inicio[2]); 
										
										if($datos != ""){
											//VIDEOS
											if($i == 5){
												print("<li>
													<div class='collapsible-header'>$icono $nombres</div>
													<div class='collapsible-body'>
														<iframe width='50%' height='200' src='$datos' frameborder='0' allowfullscreen></iframe>
													</div>
												</li>
												");
											}
											//TODO LO DEMAS
											else{
												print("
													<a target='_blank' href='$datos'>
														$icono<i>$nombres</i>
													</a>
													<br><br>");
											}                                         
										}
									}
									print("
								</ul>
							</div>
						</div>");
								}
								print("</div>");
							}
							?>
							<!-- ACTIVIDADES -->
							<div id="actividades">
								<div class="comunicacion">
									<p>
										Estas actividades valorativas son parte fundamental de tu aprendizaje y del proceso que llevas, desarróllalas en su totalidad y obtén un mejor resultado. 
									</p>
								</div>
								<table class="bordered resalta">
									<thead>
										<tr>
											<th data-field="N">N.</th>
											<th data-field="Nombre">NOMBRE</th>
											<th data-field="Inicio">FECHA DE INICIO</th>
											<th data-field="Final">FECHA DE CIERRE</th>
											<th data-field="Nota">CALIFICACIÓN</th>
											<th data-field="Ver">PRESENTAR<br>ACTIVIDAD</th>
										</tr>
									</thead>
									<tbody>
										<?php
										usort($matriz_actividades, 'ordename');

										function ordename ($a, $b) {
											return $a['4'] - $b['4'];
										}

										setlocale(LC_TIME,"es_ES");
										$cont = 0;

										foreach ($matriz_actividades as $key => $row) {
											if(!empty($row['0'])){
												$cont++;
												if ($row['3'] <= $nowtime && $row['4'] >= $nowtime){
													$tablatr = "tablatr";
												} else {
													$tablatr = "";
												}
												$act_inicio = utf8_encode( strftime('%#d %B %Y', $row['3']) );
												$act_final = utf8_encode( strftime('%A - %#d %B %Y / %H:%M', $row['4']) );
												print("
													<tr class='$tablatr'>
														<td><b>$cont</b></td>
														<td><p class='name'>$row[2]</p></td>
														<td><p>$act_inicio</p></td>
														<td><p>$act_final</p></td>
														<td><p>$row[6] / $row[5]</p></td>
														<td align='center'><a target='_blank' href='../mod/$row[1]/view.php?id=$row[0]'><i class='material-icons link_acti'>open_in_new</i></a></td>
													</tr>
													");
											}
										}
										?>
									</tbody>
								</table>
							</div>
							
							<!-- COMUNICACION -->
							<div id="comunicacion" class="comunicacion">
								
								<p>
									Aquí encuentras tres formas de comunicación con el tutor, puedes aclarar las dudas utilizando los foros, chat o mensajes, recuerda consultar antes el foro en donde puedes verificar la disponibilidad del tutor en el chat. 
								</p>
								<center>
									<table class="tabla_com">
										<tr>
											<?php
											for ($i=0; $i < count($comunicacion)-1; $i++) {
												$id = $comunicacion[$i][0];
												$tipo = $comunicacion[$i][1];
												$nombre = $comunicacion[$i][2];
												$descripcion = $comunicacion[$i][3];
												print(" <td>
													<a target='_blank' href='../mod/$tipo/view.php?id=$id'>
														<img id='desc_$tipo' src='../interfaz/imgs/$tipo.png' onmouseover='info_com(this.id)' />
													</a>
												</td>");
												
											}
											print("<td>
												<a target='_blank' href='../user/index.php?id=$curso'>
													<img id='desc_mensaje' src='../interfaz/imgs/mensajes.png' onmouseover='info_com(this.id)' />
												</a>
											</td>");
											?>
										</tr>
									</table>

									<div id="info_desc_mensaje" class="info_com ocultar">
										<p>
										Por medio de este enlace podras comunicarte con tu tutor por medio de los mensajes que maneja la plataforma internamente
										<p>
									</div>

									<div id="info_desc_forum" class="info_com ocultar">
										<p>
										En este foro podras generar preguntas o dudas que tengas sobre el curso, y tanto el tutor como tus compañeros podran ayudarte a solucionar o aclarar tus inquietudes
										</p>
									</div>

									<div id="info_desc_chat" class="info_com ocultar">
										<p>
										Por medio de esta herramienta, podras comunicarte directamente con tu tutor ,ten en cuenta el horario de atención de tutor
										</p>
									</div>

								</center>
							</div>
						</div>
					</div>
				</div>
			</div>