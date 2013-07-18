<?php

if(isset($_GET))
{
	$idPeriodo = $_GET['idPeriodo'];
	
	include ("librerias/class_conectarPG.php");
	//instanciación de la clase conexión a postgresql.
	$conexion = new ConexionPGSQL();
	$conexion->conectar();
	$contador=1;
	
	$queryPeriodo = pg_query("SELECT * FROM periodo WHERE id = '$idPeriodo'");
	$infoPeriodo = pg_fetch_array($queryPeriodo);
	
	$queryLogros = pg_query("SELECT * FROM logros WHERE idperiodo = '$idPeriodo'");
	
?>
    
    <h4 id="tituLogros" class="text-info text-left">Periodo: <?php echo $infoPeriodo['nombre']?></h4>
    <ul class="nav nav-list">
       <li class="nav-header">
          Listado de Logros
       </li>
       <?php while($fila = pg_fetch_array($queryLogros)){?>
       <li>
          <a href="<?php echo $fila['id'];?>"><i class="icon-ok-sign"></i><?php echo $fila['descripcion'];?></a>
       </li>
       <?php } $conexion->liberar($queryLogros);?>
    </ul>
 
 <?php }?>