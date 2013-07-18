<?php
	include_once("librerias/class_conectarPG.php");
	$conexion = new ConexionPGSQL();
	$con = $conexion->conectar();
	
	$redirectURL = 'admin.php';
	
	if($_POST['login'] != "" && $_POST['password'] != "")
	{	
		$nick = $_POST['login'];
		$pass = $_POST['password']; // encriptamos en MD5 para despues comprar (Modificado)
		
		$query = pg_query("SELECT * FROM usuarios WHERE documento = '$nick' AND password = '$pass'");
				  	  

		// nos devuelve 1 si encontro el usuario y el password
		if(pg_num_rows($query))
		{ 
			$array = pg_fetch_array($query);
			
			$queryColegio = pg_query("SELECT colegio.id
									FROM usuarios INNER JOIN (colegio INNER JOIN colegio_usuario ON colegio.id = colegio_usuario.idcolegio) ON usuarios.id = colegio_usuario.idusuario
									WHERE (((usuarios.id)=".$array['id']."));");
			$datosColegio = pg_fetch_array($queryColegio);
			$conexion->liberar($queryColegio);
			
			
			session_start();
			$_SESSION["nombre"] = $array["nombres"];
			$_SESSION["apellido"] = $array["apellidos"];
			$_SESSION["idUsuario"] = $array["id"];
			$_SESSION["foto"] = $array["foto"];
			$_SESSION["idColegio"] = $datosColegio['id'];
			
			echo '{"status":1, "redirectURL":"'.$redirectURL.'"}';
			exit;
		}else{
		die('{"status":0,"mensaje":"Nombre de usuario o contrase&ntilde;a incorrectos"}');}
	}else{
		die ('{"status":0,"mensaje":"Faltan campos por llenar"}');
	}
	
	$conexion->liberar($query);
	$conexion->destruir();

	
?> 