<?php
include_once('../librerias/class_conectarPG.php');
$conexion = new ConexionPGSQL();
$conexion->conectar();

if($_GET){
	$fecha = date('Y-m-d');
	$hora = date('H:i:s').'.000';
	$descripcion = $_GET['descripcion'];
	$idGrupo = $_GET['idGrupo'];
	$idUsuario = $_GET['idUsuario'];
	
	$sql = "INSERT INTO anotacion_grupo (fecha, hora, descripcion, idgrupo, iddocente
            VALUES ('" . $fecha . "','" . $hora . "', '" . $descripcion . "','" . $idGrupo . "','" . $idUsuario . "')";
        
		if(mysql_query($sql)){
			echo '{"status":1,"mensaje":"Anotacion realizada exitosamente"}';	
		}else{
			echo '{"status":0,"mensaje":"Error!! la anotacion ha fallado'.$hora.'"}';
		}
}

?>