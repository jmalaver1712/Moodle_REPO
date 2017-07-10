<?php 
print("
<style>

	.usuario{
		background-color: ".$config_interfaz['header'].";
	}

	.panel{
		background-color: ".$config_interfaz['menu'].";
	}

	.submenu_panel:hover{
		background-color: ".$config_interfaz['hover_menu'].";
	}

	.cuerpo_interfaz{
		font-family: ".$config_interfaz['fuente'].";
	}

	.panel{
		color: ".$config_interfaz['color_fuente']."; !important;
	}

	.submenu_panel a{
		color: ".$config_interfaz['color_fuente'].";
	}

</style>

");
?>