<?php	
    include ("librerias/class_conectarPG.php");
	include_once("librerias/funciones-comunes.php");
	//instanciación de la clase conexión a postgresql.
	$conexion = new ConexionPGSQL();
	$conexion->conectar();

if($_GET['idUsuario'] == '' or $_GET['idRol'] == '')
{
	echo 'Esto está vacio';
}else{
	$idUsuario = $_GET['idUsuario'];
	$idRol = $_GET['idRol'];
	$anioActual = date('Y');
	
	switch($idRol){
	
	case 2:
		//Consulta parab extraer los grupos a los cuales se les enseña
		$queryGrupos = pg_query("SELECT usuarios.nombres, grupo.id, grupo.nombre
								FROM usuarios INNER JOIN (grupo INNER JOIN usuario_grupo ON grupo.id = usuario_grupo.idgrupo) ON usuarios.id = usuario_grupo.idusuario
								WHERE (((usuarios.id)=".$idUsuario.") AND ((grupo.anio)='".$anioActual."')) ORDER BY grupo.nombre;");
		
		//Consulta para extraer las asignaturas que dicta un docente
		$queryAsignaturas = pg_query("SELECT usuario_asignatura.idusuario, asignatura.nombre
								 FROM usuarios INNER JOIN (asignatura INNER JOIN usuario_asignatura ON asignatura.id = usuario_asignatura.idasignatura) ON usuarios.id = usuario_asignatura.idusuario
								 WHERE (((usuario_asignatura.idusuario)=".$idUsuario."))");
		?>
		<div style="margin-top:30px"></div>
		<h4 class="text-info">
			FICHAS
		</h4>
		
		<div class="accordion" id="accordion-806412">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-806412" href="#accordion-element-531232">GRUPOS O CURSOS</a>
				</div>
				<div id="accordion-element-531232" class="accordion-body collapse">
					<div id="listadoGrupos" class="accordion-inner">
						<?php while($datosGrupo = pg_fetch_array($queryGrupos)){?>
							<a id="idGrupo" rel="<?php echo $datosGrupo[1]."-".$idUsuario?>" class="btn btn-mini btn-success btn-block"><?php echo $datosGrupo[2]?></a>
						<?php } $conexion->liberar($queryGrupos) ?>
					</div>
				</div>
			</div>
			
			<div class="accordion-group">
				<div class="accordion-heading">
					 <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-806412" href="#accordion-element-628755">ASIGNATURAS</a>
				</div>
				<div id="accordion-element-628755" class="accordion-body collapse">
					<div class="accordion-inner">
						<?php while($datosAsignatura = pg_fetch_array($queryAsignaturas)){?>
							<a class="btn btn-mini btn-success btn-block"><?php echo $datosAsignatura[1]?></a>
						<?php } $conexion->liberar($queryAsignaturas) ?>
					</div>
				</div>
			</div>
		</div>
		
		<div style="margin-top:30px"></div>
		<h4 class="text-info">
			MENSAJES
		</h4>
 	<?php 
	break;
	
	
	case 3:
		//Consulta parab extraer los grupos a los cuales se les enseña
		$queryGrupos = pg_query("SELECT usuarios.nombres, grupo.id, grupo.nombre
								FROM usuarios INNER JOIN (grupo INNER JOIN usuario_grupo ON grupo.id = usuario_grupo.idgrupo) ON usuarios.id = usuario_grupo.idusuario
								WHERE (((usuarios.id)=".$idUsuario.") AND ((grupo.anio)='".$anioActual."'));");
		
		?>
		<div style="margin-top:30px"></div>
		<h4 class="text-info">
			FICHAS
		</h4>
		
		<div class="accordion" id="accordion-806412">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-806412" href="#accordion-element-531232">INFORMES</a>
				</div>
				<div id="accordion-element-531232" class="accordion-body collapse">
					<div id="listadoInformes" class="accordion-inner">
						<?php while($datosGrupo = pg_fetch_array($queryGrupos)){?>
							<a id="idGrupo" rel="<?php echo $datosGrupo[1]."-".$idUsuario?>" class="btn btn-mini btn-success btn-block">Reporte de Notas</a>
						<?php } $conexion->liberar($queryGrupos) ?>
					</div>
				</div>
			</div>
		</div>
		
		<div style="margin-top:30px"></div>
		<h4 class="text-info">
			MENSAJES
		</h4>
 	<?php 
	
	break;
	
	//VISTA ADMINISTRADOR
	case 4:
	
	break;
	
	//VISTA ACUDIENTE
	case 5;
		//Consulta parab extraer los grupos a los cuales se les enseña
		$queryAlumnos = pg_query("SELECT usuarios.id, usuarios.documento, usuarios.nombres, usuarios.apellidos
								FROM usuarios INNER JOIN (usuarios AS acudiente INNER JOIN usuario_acudiente ON acudiente.id = usuario_acudiente.idacudiente) ON usuarios.id = usuario_acudiente.idusuario
								WHERE (((acudiente.id)=".$idUsuario."));");
		
		?>
		<div style="margin-top:30px"></div>
		<h4 class="text-info">
			FICHAS
		</h4>
		
		<div class="accordion" id="accordion-806412">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-806412" href="#accordion-element-531232">ALUMNOS</a>
				</div>
				<div id="accordion-element-531232" class="accordion-body collapse">
					<div id="listadoAlumnos" class="accordion-inner">
						<?php while($datosAlumno = pg_fetch_array($queryAlumnos)){?>
							<a id="idAlumno" rel="<?php echo $datosAlumno[0]?>" class="btn btn-mini btn-success btn-block"><?php echo $datosAlumno[1]." - ".$datosAlumno[2]." ".$datosAlumno[3]?></a>
						<?php } $conexion->liberar($queryAlumnos) ?>
					</div>
				</div>
			</div>
		</div>
		
		<div style="margin-top:30px"></div>
		<h4 class="text-info">
			MENSAJES
		</h4>
 	<?php 
	break;
	?>
<?php } //cierro switch 
} $conexion->destruir();?>

<script>
$(document).ready(function(e) {
	/**********************************************************
	Carga la pagina con la informacion de un grupo que se le da
	clase por un docente
	**********************************************************/
    $('#listadoGrupos a').click(function(e){
		var cadena = $(this).attr('rel');
		var datos = cadena.split("-");
		
		$('#listadoGrupos a').each(function(index, element) {
        	
			if($(this).hasClass('btn-warning')){
				$(element).removeClass('btn-warning');
				$(element).addClass('btn-success');	
			} 
        });
		
		if($(this).hasClass('btn-success')){
			$(this).removeClass('btn-success');
			$(this).addClass('btn-warning');
		}
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').html('<div class="loaderAjax"><img src="img/ajax_loader3.gif"/></div');
		$('.loaderAjax').show();
		
		//Llamado Ajax		
		$.ajax({
			   type: "GET",
			   url: "cargaGrupo.php",
			   data: "idGrupo="+datos[0]+"&idUsuario="+datos[1],
			   success: function(data){
				  // alert(data);
				$('#contenedorCambiante').html(data); //Respuesta del servidor
				$('#contenedorCambiante').removeClass('fondoCarga');
				$('.loaderAjax').fadeOut();			
   			}//cierro success
		});//cierro ajax
	});
	
	
	/**********************************************************
	Carga la pagina con el reporte de notas de un Alumno
	**********************************************************/
    $('#listadoInformes a').click(function(e){
		var cadena = $(this).attr('rel');
		var datos = cadena.split("-");
		
		$('#listadoInformes a').each(function(index, element) {
        	
			if($(this).hasClass('btn-warning')){
				$(element).removeClass('btn-warning');
				$(element).addClass('btn-success');	
			} 
        });
		
		if($(this).hasClass('btn-success')){
			$(this).removeClass('btn-success');
			$(this).addClass('btn-warning');
		}
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').html('<div class="loaderAjax"><img src="img/ajax_loader3.gif"/></div');
		$('.loaderAjax').show();
		
		//Llamado Ajax		
		$.ajax({
			   type: "GET",
			   url: "cargaInformeAlumno.php",
			   data: "idGrupo="+datos[0]+"&idUsuario="+datos[1],
			   success: function(data){
				  // alert(data);
				$('#contenedorCambiante').html(data); //Respuesta del servidor
				$('.loaderAjax').fadeOut();			
   			}//cierro success
		});//cierro ajax
	});
	
	/**********************************************************
	Carga la pagina con el listado de alumnos representados por un acudiente
	**********************************************************/
    $('#listadoAlumnos a').click(function(e){
		var idAlumno = $(this).attr('rel');
		
		$('#listadoAlumnos a').each(function(index, element) {
        	
			if($(this).hasClass('btn-warning')){
				$(element).removeClass('btn-warning');
				$(element).addClass('btn-success');	
			} 
        });
		
		if($(this).hasClass('btn-success')){
			$(this).removeClass('btn-success');
			$(this).addClass('btn-warning');
		}
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').html('<div class="loaderAjax"><img src="img/ajax_loader3.gif"/></div');
		$('.loaderAjax').show();
		
		//Llamado Ajax		
		$.ajax({
			   type: "GET",
			   url: "cargaInformeAcudienteAlumno.php",
			   data: "idAlumno="+idAlumno,
			   success: function(data){
				  // alert(data);
				$('#contenedorCambiante').html(data); //Respuesta del servidor
				$('.loaderAjax').fadeOut();			
   			}//cierro success
		});//cierro ajax
	});
});

</script>