<html>
<head>
<title>PDF</title>
<style>
body{
	text-align: center;
}

.fondo{
	padding-top: 50px;
	width: 100%;
	height: 740px;
	text-align: center;
}

.personaje{
	width: 15%;
	margin: 0px;
}

.imagen{
	position: absolute; 
	top: 40%;
	width: 20%;
	float: left;
}
.otorgado{
	width: 60%;
}
.borde{

}

.calificaciones{
	position: absolute; 
	top: 40%;
	left: 0px;
	width: 100%;
	height: 300px;
	text-align: center;
	margin-top: 100px;
	margin-bottom: 200px;
	color: black;

	background:url(images/borde.png) repeat-x center center;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;

}
.calificaciones table {
  border-collapse: collapse;
  width: 90%;
}
.calificaciones td{
	border-left: black 1px dotted;
	border-right: black 1px dotted;
	padding: 5px;
	width: 20%;
}
.logos{
	width: 70%;
}

.footer{
	position: absolute;
	text-align: center;
	margin-top: 100px;
	top: 90%;
	width: 100%;
}

.footer img{
	width: 50%;
}
.footer p{
	color: gray;
}

</style>
</head>
<body>
<div class="fondo">
	<img class="personaje" src="images/docente.png">
	<h1>Certificado</h1>
		<img class="otorgado" src="images/otorgado.png">
		<h2><?php print($nombre);?></h2>
		<h4>Quien curso el CAMPAMENTO PARA TUTORES</h4>
	<div class="borde"></div>
	<div class="calificaciones">
		<h3>Calificaciones</h3>
		<table align="center">
		<tr>
		<?php
		for($w = 0; $w < $n_notas; $w++){
		$px = $w+1; 
			print("
			<td>
			<img class='logos' src='../imgs/mundo_$px.png'>
			<h4>".${"mundo_".$w}."</h4>
			<b>".${"nota_".$w}."/5.00</b>
			</td>

			");
		}
		?>
		</tr>
		</table>
	</div>
	<div class="borde"></div>
	<div class="footer">
		<img src="images/marcas.png">
		<br><br><br><br>
		<p>Válido hasta <br><br> 01-Agosto-2017</p>
	</div>
</div>
</body>
</html>
