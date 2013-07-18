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
	
//Consulta que carga los datos de un grupo especifico	
$queryGrupos = pg_query("SELECT * FROM grupo WHERE id = '$idGrupo'");
$datosGrupos = pg_fetch_array($queryGrupos);
$conexion->liberar($queryGrupos);

//Consulta que carga los datos de un alumno especifico	
$queryAlumnos = pg_query("SELECT * FROM usuarios WHERE id = '$idAlumno'");
$datosAlumno = pg_fetch_array($queryAlumnos);
$conexion->liberar($queryAlumnos);

//Calcular edad
$fechaNacim = $datosAlumno['fecha_nacimiento'];
$edad = pg_query("select extract(years from age(current_timestamp, '$fechaNacim'::timestamp))");
$edad2 = pg_fetch_array($edad);
$conexion->liberar($edad);	

//Consulta para obtener el nombre del genero
$queryGenero = pg_query("SELECT usuarios.id, genero.nombregenero
						FROM genero INNER JOIN usuarios ON genero.idgenero = usuarios.genero
						WHERE (((usuarios.id)=".$datosAlumno['id']."));");
$datosGenero = pg_fetch_array($queryGenero);
$conexion->liberar($queryGenero);

//Consulta para obtener el nombre del RH
$queryRH = pg_query("SELECT usuarios.id, tiporh.nombrerh
					FROM tiporh INNER JOIN usuarios ON tiporh.idrh = usuarios.tiporh
					WHERE (((usuarios.id)=".$datosAlumno['id']."));");
$datosRH = pg_fetch_array($queryRH);
$conexion->liberar($queryRH);


/***************INFO ACUDIENTE *******************/

//Consulta que carga los datos del acudiente del alumno
$queryAcudiente = pg_query("SELECT acudiente.*
							FROM (usuarios AS acudiente INNER JOIN (usuarios INNER JOIN usuario_acudiente ON usuarios.id = usuario_acudiente.idusuario) ON acudiente.id = usuario_acudiente.idacudiente) INNER JOIN usuario_rol ON acudiente.id = usuario_rol.idusuario
							WHERE (((usuarios.id)=".$idAlumno.") AND ((usuario_acudiente.activo)='true') AND ((usuario_rol.idrol)=5));");
$datosAcudiente = pg_fetch_array($queryAcudiente);
$conexion->liberar($queryAcudiente);

//Calcular edad
$fechaNacimA = $datosAcudiente['fecha_nacimiento'];
$edadA = pg_query("select extract(years from age(current_timestamp, '$fechaNacimA'::timestamp))");
$edad3 = pg_fetch_array($edadA);
$conexion->liberar($edadA);	

//Consulta para obtener el nombre del genero
$queryGeneroA = pg_query("SELECT usuarios.id, genero.nombregenero
						FROM genero INNER JOIN usuarios ON genero.idgenero = usuarios.genero
						WHERE (((usuarios.id)=".$datosAcudiente['id']."));");
$datosGeneroA = pg_fetch_array($queryGeneroA);
$conexion->liberar($queryGeneroA);

//Consulta para obtener el nombre del RH
$queryRHA = pg_query("SELECT usuarios.id, tiporh.nombrerh
					FROM tiporh INNER JOIN usuarios ON tiporh.idrh = usuarios.tiporh
					WHERE (((usuarios.id)=".$datosAcudiente['id']."));");
$datosRHA = pg_fetch_array($queryRHA);
$conexion->liberar($queryRHA);
?>

<div class="row-fluid">
	<div class="span12">
    	<ul class="breadcrumb">
			<li>
				<a id="volverGrupo" rel="idGrupo=<?php echo $idGrupo?>&idUsuario=<?php echo $idUsuario?>">GRUPO <?php echo $datosGrupos['nombre']?></a> <span class="divider">/</span>
            </li>
            <li class="active">
            	<?php echo $datosAlumno['nombres'].' '.$datosAlumno['apellidos']?>
            </li>
		</ul>
	</div>
</div>
	
<div class="row-fluid">
    <div class="span12">	
        <h4 class="text-info">
                PERFIL - <?php echo strtoupper($datosAlumno['nombres'].' '.$datosAlumno['apellidos'])?>
        </h4>
    </div>
</div>

<div class="row-fluid">
    <div class="span3">
    	<img src="<?php echo $datosAlumno['foto'];?>" class="img-polaroid"/>
    </div>
  	
    <div class="span5">
        <p><strong>Documento: </strong><?php echo $datosAlumno['documento']?><br />
           <strong>Nombres: </strong><?php echo $datosAlumno['nombres']?><br />
           <strong>Apellidos: </strong><?php echo $datosAlumno['apellidos']?><br />
           <strong>Fecha de Nacimiento: </strong><?php echo fechaEspaniol($datosAlumno['fecha_nacimiento']);?><br />
           <strong>Edad: </strong><?php echo $edad2[0]?> años<br />
           <strong>Género: </strong><?php echo $datosGenero['nombregenero']?><br />
           <strong>RH: </strong><?php echo $datosRH['nombrerh']?><br />
           <strong>Dirección: </strong><?php echo $datosAlumno['direccion']?><br />
           <strong>Teléfono: </strong><?php echo $datosAlumno['telefono']?><br />
           <strong>Celular: </strong><?php echo $datosAlumno['celular']?><br />
           <strong>Correo Personal: </strong><a href="mailto:<?php echo $datosAlumno['correo_personal']?>"><?php echo $datosAlumno['correo_personal']?></a><br />
           <strong>Correo Institucional: </strong><a href="mailto:<?php echo $datosAlumno['correo_institucional']?>"><?php echo $datosAlumno['correo_institucional']?></a><br />
         </p>  
    </div>
  	
	<div class="span4">
        <p><a id="btnAnotacionesAlumno" class="btn btn-success">Ver / Hacer Anotaciones al Alumno</a></p>
     </div>   
</div>


     
</div>

<div class="row-fluid">    
  <div class="span12">
    	<h4 class="text-info">
        	ACUDIENTE - <?php echo strtoupper($datosAcudiente['nombres']." ".$datosAcudiente['apellidos'])?>
        </h4>
  </div>
</div>

<div class="row-fluid">
	<?php if(count($datosAcudiente) > 1){?>
	<div class="span3">
    	<img src="<?php echo $datosAcudiente['foto'];?>" class="img-polaroid"/>
    </div>
    
    <div class="span5">
        <p><strong>Documento: </strong><?php echo $datosAcudiente['documento']?><br />
           <strong>Nombres: </strong><?php echo $datosAcudiente['nombres']?><br />
           <strong>Apellidos: </strong><?php echo $datosAcudiente['apellidos']?><br />
           <strong>Fecha de Nacimiento: </strong><?php echo fechaEspaniol($datosAcudiente['fecha_nacimiento']);?><br />
           <strong>Edad: </strong><?php echo $edad3[0]?> años<br />
           <strong>Género: </strong><?php echo $datosGeneroA['nombregenero']?><br />
           <strong>RH: </strong><?php echo $datosRHA['nombrerh']?><br />
           <strong>Dirección: </strong><?php echo $datosAcudiente['direccion']?><br />
           <strong>Teléfono: </strong><?php echo $datosAcudiente['telefono']?><br />
           <strong>Celular: </strong><?php echo $datosAcudiente['celular']?><br />
           <strong>Correo Personal: </strong><a href="mailto:<?php echo $datosAcudiente['correo_personal']?>"><?php echo $datosAcudiente['correo_personal']?></a><br />
           <strong>Correo Institucional: </strong><a href="mailto:<?php echo $datosAcudiente['correo_institucional']?>"><?php echo $datosAcudiente['correo_institucional']?></a><br />
         </p>
      </div>
      <?php } else{?>
		  <div class="span3">
    			No presenta acudiente
    	  </div>
		  <?php }?>
</div>

<div class="row-fluid">
	<div class="span12">
    	<legend>Enviar Mensaje al Acudiente</legend>
        <form method="post">
            <textarea rows="5" class="textarea span9" placeholder="Escriba aqui el mensaje"></textarea><br />
            <button class="btn" type="button">Enviar</button>
        </form>
     </div>
</div>


<?php } $conexion->destruir();?>

<script>
	 $('.textarea').wysihtml5();
</script>

<script>
$(document).ready(function(e) {
	
	//Caega los datos del grupo al darle al link de la miga de pan
    $('#volverGrupo').click(function(e){
		var cadena = $(this).attr('rel');
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').load('cargaGrupo.php?'+cadena);

	});
	
	$('#btnAnotacionesAlumno').click(function(e){
		var idAlumno = <?php echo $idAlumno?>;
		var idGrupo = <?php echo $idGrupo?>;
		var idUsuario = <?php echo $idUsuario?>;
		
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').load('cargaAnotacionAlumno.php?idAlumno='+idAlumno+'&idGrupo='+idGrupo+'&idUsuario='+idUsuario);
		//alert('idGrupo: '+idGrupo+'  /  idUsuario: '+idUsuario);
	});
	
	
});
</script>