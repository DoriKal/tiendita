<?php
	
	$conexion = mysql_connect("localhost","root","RAOR940203");
	mysql_select_db("digital",$conexion);
	date_default_timezone_set("America/Bogota");
    mysql_query("SET NAMES utf8");
	mysql_query("SET CHARACTER_SET utf");
	$s='$';
	
	function limpiar($tags){
		$tags = strip_tags($tags);
		return $tags;
	}

	
?>