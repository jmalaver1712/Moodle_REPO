<html>
<head>
<title>Material Didactico</title>
</head>
<body>
	<center>
	<h1>Material Didactico</h1>
		<?php 
		$recurso = $_GET['recurso'];
		print("<iframe src='https://www.educaplay.com/es/recursoseducativos/$recurso' width='795' height='690' frameborder='0'></iframe>");
		?>
	</center>
</body>
</html>