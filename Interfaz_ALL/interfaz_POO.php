<?php 
require_once("php/conexion_POO.php");//conexion BD
require_once("php/config_interfaz_POO.php");//conexion BD

$interfaz = new interfaz();

$user = $interfaz->datos_usuario(); 

$estructura = $interfaz->estructura(); 

$noticias = $interfaz->data_noticias();

$top5 = $interfaz->top5(); 

$secciones = $interfaz->secciones(); 

$ids_seccion = array(); 
foreach ($secciones as $seccion){
	$ids_seccion[] = $seccion['seccion'];
}

$documentos = $interfaz->con_documentos();

$enlaces = $interfaz->con_enlaces();

$actividades = $interfaz->actividades();

$comunicacion = $interfaz->con_comunicacion($ids_seccion);

// $lista_cursos = $interfaz->lista_cursos();

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
<div id="Ventana_1" class="modal hide fade in" data-keyboard="false" data-backdrop="static" style="display: none;">
	<div class="modal-header">
		<!-- PARA CERRAR VENTANA <a data-dismiss="modal" class="close"> × </a> -->
		<h3>Cabecera de la ventana</h3>
	</div>
	<div class="modal-body">
		<h4>Texto de la ventana</h4>
		<p>Mas texto en la ventana.</p>                
	</div>
	<div class="modal-footer">
		<input type="submit" class="btn" value="Guardar" />
		<input type="reset" data-dismiss="modal" class="btn" value="Cerrar" />
	</div>
</div>
<!-- FIN VENTANA MODAL -->


<div class="cuerpo_interfaz">
	<!-- BODY -->
	<div class="row">

		<!-- MENU -->         
		<div class="col s3">
			<div class="panel border_bajo">
				<div class="usuario">
					<h3>Bienvenido<br>
						<?= $user['nombre']; ?>
					</h3>

					<!-- <a data-toggle="modal" href="#Ventana_1">Ventana Emergente </a> -->
				</div>

				<!--MENU-->
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

							<li class="tab submenu_panel menu2">
								<a href="#aspectos_generales" onclick="oculta_panel()">
									<i class="material-icons">description</i><u>Aspectos Generales</u>
								</a>
							</li>
							<?php
							$n_secciones = count($secciones); 
							$semanas = $n_secciones-3;
							$modulos = count($estructura);
							$aux = 1;
							for ($i=1; $i <= $modulos; $i++) {
								if($aux <= $semanas){
								?>
									<li title="nav_v_inicio_<?= $i ?>" class="tab submenu_panel menu2" onclick="navegacion(this.title)">
										<a href="#v_inicio_<?= $i ?>" title="modulo<?= $i ?>" onclick="mostrar(this.title)">
											<i class="material-icons">view_list</i><u>Modulo <?= $i ?></u>
										</a>
									</li>
									<ul id="modulo<?= $i ?>" class="ocultar menu3">

										<li id="nav_v_inicio_<?= $i ?>" class="nav_menu" onclick="navegacion(this.id)">
											<a href="#v_inicio_<?= $i ?>" onclick="oculta_panel()"><u class="semanas">Video Intro</u></a>
										</li>

										<?php
										for ($j=0; $j < $estructura[$i]; $j++) {
											if($aux <= $semanas){
												?>
												<li id="nav_semana<?= $i."_".$j ?>" class="nav_menu" onclick="navegacion(this.id)">
												<a href="#contenido_<?= $aux ?>" onclick="oculta_panel()"><u class="semanas">Semana <?= $aux ?></u></a>
												</li>
												<?php 
												$aux++;
											}
										} 
										?>

										<li id="nav_v_final_<?= $i ?>" class="nav_menu" onclick="navegacion(this.id)">
											<a href="#v_final_<?= $i ?>"><u class="semanas" onclick="oculta_panel()">Video Cierre</u></a>
										</li>

									</ul>
								<?php
								}
							}
							?>
						</ul>
					</li>
					<!-- FIN CONTENIDO -->
					<?php
					$not_actividad = array_search("tablatr",array_column($actividades,"clase"));
					if($not_actividad === false){
						$pulse =  "";
						$sonido = "";	
					}else{
						$pulse =  "pulse";
						$sonido = "../interfaz/imgs/sonido.ogg";	
					}
					?>
					<!-- ACTIVIDADES VALORATIVAS -->
					<li class="tab submenu_panel" onclick="oculta_panel()">
						<a href="#actividades">
							<i class="material-icons <?= $pulse; ?>">date_range</i><u>Actividades Valorativas</u>
							<audio src="<?= $sonido ?>" autoplay /> 
						</a>
					</li>

					<!-- COMUNICACION -->
					<li class="tab submenu_panel" onclick="oculta_panel()" >
						<a href="#comunicacion">
							<i class="material-icons">forum</i><u>Comunicación</u>
						</a>
					</li>



					<!-- Lista Cursos -->
					<!-- 					
					<li class="tab submenu_panel">
						<a title="link_cursos" href="#contenido" onclick="mostrar(this.title)">
							<i class="material-icons">public</i><u>Cursos</u>
						</a>
						<ul id="link_cursos" class="tabs ocultar">
						<?php
						foreach ($lista_cursos as $enlace_curso) {						
						?>
							<li class="tab submenu_panel menu2">
								<a href="<?= $enlace_curso['enlace'] ?>" onclick="redirecciona(this.href)">
									<i class="material-icons">arrow_forward</i><u><?= $enlace_curso['nombre'] ?></u>
								</a>
							</li>
						<?php
						}
						?>
						</ul>
					</li> 
					-->
					

				</ul>               
				<!-- BOTON AYUDA -->
				<div class="ribbon-wrapper">
					<div class="ribbon-front">
						<div class="fixed-action-btn horizontal">
							<a class="btn-floating">
								<i class="large material-icons">arrow_forward</i>
							</a>
							<ul>
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

							</ul>
						</div>
					</div>
					<div class="ribbon-edge-topleft"></div>
				</div>

				<br>
			</div>
		</div>
		<!-- FIN MENU -->   

		<div id="panel_contenido" class="col s9">

<!-- /////////////////////////////////////// -->

			<!-- ESTUDIANTES DESTACADOS -->
			<div class="row border_bajo">
				<div class="col s10">
				<?php
				$style_not = "min-height: 100%;";
				if(count($top5) == 5){
					$style_not = "min-height: 88%;"; 
					?>
					<h3>Estudiantes destacados del Curso</h3>
					<center>
						<table style="width: 50% !important;">
							<tr>
							<?php foreach ($top5 as $top) { ?>
								<td width="20%">
									<a class="tooltipped" data-position="bottom" data-delay="40" data-tooltip="<?= $top['nombre']; ?>">
										<img src="<?= $top['imagen']; ?>" class="img-circle foto_est">
									</a>
								</td>
							<?php } ?>
							</tr>
						</table>
					</center>
				<?php
				}
				else{
				echo "<br><br>";	
				}
				?>
				
				</div>

				<div class="col s2 btn-noticias">
					<a onclick="aparece()">
						<i class="material-icons campana">notifications</i>
						<span class="notificacion"><?= count($noticias); ?></span>
					</a>
				</div>
			</div>
			<!-- FIN ESTUDIANTES DESTACADOS -->

			<div class="row">

<!-- /////////////////////////////////////// -->

				<!-- NOTIFICACIONES -->
				<div id="noticias" class="oculto" style="<?= $style_not; ?>">
					<div class="row">
						<div class="col s12">
							<h2>Noticias</h2>
								<table width="100%" class="bordered resalta">
									<?php
									foreach ($noticias as $noticia) {
										$resumen = substr($noticia['resumen'], 0, 100)."...";
										?>
										<tr>
											<td width="15%" align="center">
												<a href="<?= $noticia['enlace'] ?>" target="_blank">
													<img src="<?=$noticia['imagen']?>">
												</a>
											</td>
											<td width="85%">
												<a href="<?= $noticia['enlace'] ?>" target="_blank">
													<h4><?= $noticia['titular']?></h4>
												</a>
												<em>
													<?= $resumen ?>
												</em>
												<br>
												<span class="fecha_not"> 
													Publicación :<?= $noticia['fecha_publicacion']?>
												</span>
											</td>

										</tr>
										<?php
									}
									?>
								</table>
							<a  class="fecha_not" href="<?= URL_CAMPUS ?>/pages/noticias.php" target="_blank"><h3>Ver todas</h3></a>
						</div>
					</div>
				</div>
				<!-- FIN NOTIFICACIONES -->

<!-- CONTENIDOS -->
<!-- /////////////////////////////////////// -->

				<!-- INICIO -->
				<div id="inicio">
					<center>
						<div class="inicio">
							<h2>Video de Introduccion</h2>
							<?php
							$aspectos_generales = $secciones[0]['seccion'];
							foreach ($enlaces as $enlace){
								if($enlace['seccion'] == $aspectos_generales){
								?>
								<iframe width="90%" height="400" src="<?= $enlace['enlace'] ?>" frameborder="0" allowfullscreen></iframe>
								<?php
								}
							}
							?>
						</div>
					</center>
				</div>
				<!-- FIN INICIO -->

				<div id="modulo"></div>

<!-- /////////////////////////////////////// -->

				<!-- FORMA DE ESTUDIO -->
				<div id="contenido">
					<img src="../interfaz/imgs/forma_estudio.jpg" width="100%">
				</div>
				<!-- FIN FORMA DE ESTUDIO -->

<!-- /////////////////////////////////////// -->

				<!-- ASPECTOS GENERALES -->
				<div id="aspectos_generales">
					<center>
						<h2>Aspectos Generales</h2>
						<p>Aqui encontraras los documentos que definen el curso</p>
					</center>
					<div class="contenido">
						<?php
							
							foreach ($documentos as $documento){
								if($documento['seccion'] == $aspectos_generales){
								?>
								<br>
								<img src="../interfaz/imgs/archivos.png">
								<a class="aspectos" target="_blank" href="<?= $documento['enlace'] ?>">
									<em><?= $documento['nombre'] ?></em>
								</a>
								<br>

								<?php
								}
							}
						?>
					</div>
				</div>
				<!-- FIN ASPECTOS GENERALES -->

<!-- /////////////////////////////////////// -->

				<!-- VIDEOS DE INTRODUCCION Y FINALES -->
				<?php
				for ($i=1; $i <= $modulos; $i++) {

					for ($j=1; $j <= 2; $j++) { 

						if($j == 1){
							$tipo_cont ="v_inicio_";
							$l_indice = "I";
						}
						elseif ($j == 2) {
							$tipo_cont ="v_final_";
							$l_indice = "F";
						}
					?>

					<div id="<?= $tipo_cont.$i ?>">
						<center>
							<div class="inicio">
								<?php 
								foreach ($enlaces as $enlace) {
									$indice = strpos($enlace['indice'],$i.$l_indice);
									if ($indice !== false) {
										if($enlace['tipo'] == "video"){
											?>
											<br>
											<?= $enlace['nombre']; ?>
											<br><br>
											<iframe width="100%" height="450" src="<?= $enlace['enlace']; ?>" frameborder="0" allowfullscreen></iframe><br><br>
											<?= $enlace['descripcion']; ?>
											<?php
										}
										elseif($enlace['tipo'] == "enlace"){
											?>
											<br>
											<a href="<?= $enlace['enlace']; ?>" target="_blank">
												<img src="../interfaz/imgs/objetivo.png" width="40px"><em><?= $enlace['nombre']; ?></em>
											</a>
											<br>
											<?php
										}
									}
								}

								foreach ($documentos as $documento) {
									$indice = strpos($documento['indice'],$i.$l_indice);
									if ($indice !== false) {
										?>
										<br>
										<a href="<?= $documento['enlace']; ?>" target="_blank">
											<img src="../interfaz/imgs/objetivo.png" width="40px"><em><?= $documento['nombre']; ?></em>
										</a>
										<br>
										<?php
									}
								}
								?>
								
							</div>
						</center>
					</div>
					<?php
					}
				}
				?>
				<!-- FIN VIDEOS DE INTRODUCCION Y FINALES -->

<!-- /////////////////////////////////////// -->

				<!-- CONTENIDO -->
				<?php
				for ($i=1; $i <= $semanas; $i++) {
					$enlaces_seccion = $interfaz->con_enlaces_seccion($ids_seccion[$i]);
					$indices = $interfaz->busca_indices($enlaces_seccion);
					?>
					<div id="contenido_<?= $i ?>">
						<center><h2>Semana <?= $i ?></h2></center>
						<?php // print_r($enlaces_seccion); ?>
						<div>
							<ul class="tabs ts_aux">
								<li class="tab ts_menu <?= $indices['2']; ?>"><a href="#seccion2<?= $i ?>">Lecturas</a></li>
								<li class="tab ts_menu <?= $indices['5']; ?>"><a href="#seccion5<?= $i ?>">Videos</a></li>
								<li class="tab ts_menu <?= $indices['3']; ?>"><a href="#seccion3<?= $i ?>">Material Didactico</a></li>
								<li class="tab ts_menu <?= $indices['4']; ?>"><a href="#seccion4<?= $i ?>">Actividades Didacticas</a></li>
								<li class="tab ts_menu <?= $indices['6']; ?>"><a href="#seccion6<?= $i ?>">Wiki</a></li>
								<!-- <li class="tab ts_menu disabled ocultar"><a></a></li> -->
							</ul>
						</div>

						<?php

						for ($j=2; $j <= 6; $j++) {
						?>
						<div id="seccion<?= $j."".$i ?>" class="alto_jjma">
							<div class="intro_cont">
								<p>
								<?= $data_add[$j]['descripcion'];?>
								</p>   
							</div>
							<div class="contenido">
								<ul class="collapsible" data-collapsible="accordion">
								<?php

								foreach ($enlaces_seccion as $documento) {
									if($documento['seccion'] == $ids_seccion[$i]){
										if ($documento['indice'] == $j) {
											/* VIDEO */
											if($documento['indice'] == 5){
												?>
												<li>
													<div class="collapsible-header"><img src="<?= $data_add[$j]['icono']; ?>"> <?= $documento['nombre'];?></div>
													<div class="collapsible-body">
														<iframe width="50%" height="200" src="<?= $documento['enlace']; ?>" frameborder="0" allowfullscreen></iframe>
													</div>
												</li>
												<?php 
											}
											/*CUALQUIER RECURSO */
											else{
											?>
												<br>
												<a href="<?= $documento['enlace']; ?>" target="_blank">
													<img src="<?= $data_add[$j]['icono']; ?>"><em><?= $documento['nombre']; ?></em>
												</a>
												<br>
											<?php
											}
										}
									}
								}
								?>
								</ul>
							</div>
						</div>
						<?php
						}
						?>
					</div>
				<?php 	
				}
				?>
				<!-- FIN CONTENIDO -->

<!-- /////////////////////////////////////// -->

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
							usort($actividades, 'ordename');

							function ordename ($a, $b) {
								return $a['f_inicio'] - $b['f_inicio'];
							}

							$n_act = 1;
							
							foreach ($actividades as $row){
								$fecha_inicio =  strftime("%#d %B %Y", $row['f_inicio']);
								$fecha_final = strftime("%A - %#d %B %Y / %H:%M", $row['f_final']);
								?>
								<tr class="<?= $row['clase'] ?>">
									<td><b><?= $n_act ?></b></td>
									<td><p class="name"><?= $row['nombre'] ?></p></td>
									<td><p><?= $fecha_inicio ?></p></td>
									<td><p><?= $fecha_final ?></p></td>
									<td><p><?php echo $row['nota_final']."/".$row['nota']; ?></p></td>
									<td align="center">
										<a target="_blank" href="<?= $row['enlace'] ?>">
											<i class="material-icons link_acti">open_in_new</i>
										</a>
									</td>
								</tr>
								<?php
								$n_act++;
							}
							?>
						</tbody>
					</table>
				</div>
				<!-- FIN DE ACTIVIDADES -->

<!-- /////////////////////////////////////// -->

				<!-- COMUNICACION -->
				<div id="comunicacion" class="comunicacion">
					<p>
					Aquí encuentras tres formas de comunicación con el tutor, puedes aclarar las dudas utilizando los foros, chat o mensajes, recuerda consultar antes el foro en donde puedes verificar la disponibilidad del tutor en el chat. 
					</p>
					<center>
						<table class="tabla_com">
							<tr>
							<?php
							foreach ($comunicacion as $herramienta) {
								?>
								<td>
									<a target="_blank" href="<?= $herramienta['id']; ?>">
										<img id="desc_<?= $herramienta['tipo']; ?>" src="../interfaz/imgs/<?= $herramienta['tipo']; ?>.png" onmouseover="info_com(this.id)" />
									</a>
								</td>
								<?php
							}
							?>
							</tr>
						</table>
						<?php
						foreach ($comunicacion as $herramienta) {
							?>
							<div id="info_desc_<?= $herramienta['tipo']; ?>" class="info_com ocultar">
								<p>
								<?= $herramienta['desc']; ?>
								<p>
							</div>
							<?php
						}
						 ?>
					</center>
				</div>
				<!-- FIN DE COMUNICACION -->

<!-- /////////////////////////////////////// -->
<!-- FIN CONTENIDOS -->

			</div>
		</div>
	</div>
</div>