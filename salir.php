<?php
session_start();  			
	if(!isset($_SESSION["idUsuario"]))
	{
		header("location:login.php");
	} else {
		session_unset();
		session_destroy();
		header("location:login.php");
	}
?>