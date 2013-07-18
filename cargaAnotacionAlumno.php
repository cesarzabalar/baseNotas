<?php	
    include ("librerias/class_conectarPG.php");
	include_once("librerias/funciones-comunes.php");
	//instanciación de la clase conexión a postgresql.
	$conexion = new ConexionPGSQL();
	$conexion->conectar();

if($_GET['idAlumno'] == '' or $_GET['idGrupo'] == '' or $_GET['idUsuario'] == '')
{
	echo 'Esto está vacio';
}else{
	$idAlumno = $_GET['idAlumno'];
	$idGrupo = $_GET['idGrupo'];
	$idUsuario = $_GET['idUsuario'];
	$anioActual = date('Y');
	
//Consulta que carga los datos de un grupo especifico	
$queryGrupos = pg_query("SELECT * FROM grupo WHERE id = '$idGrupo'");
$datosGrupos = pg_fetch_array($queryGrupos);
$conexion->liberar($queryGrupos);

//Consulta que carga los datos de un usuario especifico	
$queryAlumno = pg_query("SELECT * FROM usuarios WHERE id = '$idAlumno'");
$datosAlumno = pg_fetch_array($queryAlumno);
$conexion->liberar($queryAlumno);

//Consulta que carga las anotaciones de un grupo
$queryAnotaciones = pg_query("SELECT anotacion_usuario.fecha, anotacion_usuario.hora, anotacion_usuario.descripcion, usuarios_1.nombres, usuarios_1.apellidos
							FROM usuarios AS usuarios_1 INNER JOIN (usuarios INNER JOIN anotacion_usuario ON usuarios.id = anotacion_usuario.idusuario) ON usuarios_1.id = anotacion_usuario.id_usuariodocente
							WHERE (((usuarios.id)=".$idAlumno."));");


?>

<div class="row-fluid">
	<div class="span12">
    	<ul class="breadcrumb">
			<li>
				<a id="volverGrupo" rel="idGrupo=<?php echo $idGrupo?>&idUsuario=<?php echo $idUsuario?>">GRUPO <?php echo $datosGrupos['nombre']?></a> <span class="divider">/</span>
            </li>
            <li>
            	<a id="volverAlumno" rel="idGrupo=<?php echo $idGrupo?>&idUsuario=<?php echo $idUsuario?>&idAlumno=<?php echo $idAlumno?>"><?php echo $datosAlumno['nombres']." ".$datosAlumno['apellidos']?></a> <span class="divider">/</span>
            </li>
			<li class="active">
            	Anotaciones
            </li>	

		</ul>
	</div>
</div>

<div class="row-fluid">	
    <div class="span12">	
        <h4 class="text-info">
                ANOTACIONES: <?php echo strtoupper($datosAlumno['nombres']." ".$datosAlumno['apellidos'])?>
        </h4>
    </div>
</div>

<div class="row-fluid">
  <div class="span12">
    	<p>
         <?php while($fila = pg_fetch_array($queryAnotaciones)){?>
			<span class="label label-success">Publicado el:  <?php echo fechaEspaniol($fila['fecha']);?> - Hora: <?php echo $fila['hora'];?></span><br />
            <span class="label label-alert">Realizada por:  <?php echo $fila['nombres']." ".$fila['apellidos'];?></span><br />
    
    		<div class="span10 offset1">
				<p style="text-align:justify">
					<?php echo $fila['descripcion'];?>
				</p>  
			</div>
          <?php } $conexion->liberar($queryAnotaciones);?>
		 </p>
   </div>
</div>

<div class="row-fluid">
	<div class="span12">      
         <form method="post">
         	<textarea rows="5" class="textarea span9" placeholder="Escriba aqui la anotación"></textarea><br />
            <button class="btn btn-success" type="button">Publicar</button>
    </form>    
  </div>
</div>


<?php } $conexion->destruir();?>

<script>
	 $('.textarea').wysihtml5();
</script>

<script>
$(document).ready(function(e) {
	
	//Carga los datos del grupo al darle al link de la miga de pan
    $('#volverGrupo').click(function(e){
		var cadena = $(this).attr('rel');
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').load('cargaGrupo.php?'+cadena);
	});
	
	$('#volverAlumno').click(function(e){
		var idAlumno = <?php echo $idAlumno?>;
		var idGrupo = <?php echo $idGrupo?>;
		var idUsuario = <?php echo $idUsuario?>;
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').load('cargaAlumno.php?idAlumno='+idAlumno+'&idGrupo='+idGrupo+'&idUsuario='+idUsuario);
		//alert('idAlumno: '+idAlumno+' /  idGrupo: '+idGrupo+'  /  idUsuario: '+idUsuario);
	});
});
</script>