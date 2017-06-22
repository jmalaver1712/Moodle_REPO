<?php
	$called = isset($_POST['called']);
	if($called == "noticias"){
		
		$hostname = "localhost";
		$username = "campusvi_campus";
		$password = "campus_urep.2016";
		$database = "campusvi_campus";

		$campus = mysqli_connect($hostname, $username, $password, $database);

		//$url_campus = "http://localhost/WEB/campus";

		$url_campus = "http://campusvirtualurepublicana.edu.co";
		$hoy = date("Y-m-d");
		$noticias = "SELECT * FROM noticias WHERE (fecha_publicacion <= '$hoy' AND fecha_final >= $hoy) AND importancia = '1' ORDER BY fecha_publicacion DESC LIMIT 0,3 ";
		$noticias_res = $campus->query($noticias) or die ("ERROR Noticias".$mysqli->error);
		$cont_noticias = mysqli_num_rows($noticias_res);
	
		while ($noticias_reg = $noticias_res ->fetch_array()){
			$resumen = substr($noticias_reg['resumen'], 0, 100)."...";
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
				</tr>");
		}
	} 	
?>