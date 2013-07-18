<?php	
    include ("librerias/class_conectarPG.php");
	include_once("librerias/funciones-comunes.php");
	//instanciación de la clase conexión a postgresql.
	$conexion = new ConexionPGSQL();
	$conexion->conectar();

if($_GET['idGrupo'] == '' or $_GET['idUsuario'] == '')
{
	echo 'Esto está vacio';
}else{
	$idGrupo = $_GET['idGrupo'];
	$idUsuario = $_GET['idUsuario'];
	$anioActual = date('Y');
	
//Consulta que carga los datos de un grupo especifico	
$queryGrupos = pg_query("SELECT * FROM grupo WHERE id = '$idGrupo'");
$datosGrupos = pg_fetch_array($queryGrupos);
$conexion->liberar($queryGrupos);

//Consulta que carga las anotaciones de un grupo
$queryAnotaciones = pg_query("SELECT anotacion_grupo.fecha, anotacion_grupo.hora, anotacion_grupo.descripcion, usuarios.nombres, usuarios.apellidos
							FROM usuarios INNER JOIN (grupo INNER JOIN anotacion_grupo ON grupo.id = anotacion_grupo.idgrupo) ON usuarios.id = anotacion_grupo.iddocente
							WHERE (((grupo.id)=".$idGrupo.") AND ((grupo.anio)='".$anioActual."'));");


?>

<div class="row-fluid">
	<div class="span12">
    	<ul class="breadcrumb">
			<li>
				<a id="volverGrupo" rel="idGrupo=<?php echo $idGrupo?>&idUsuario=<?php echo $idUsuario?>">GRUPO <?php echo $datosGrupos['nombre']?></a> <span class="divider">/</span>
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
                ANOTACIONES DEL GRUPO <?php echo $datosGrupos['nombre']?>
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
	
	//Caega los datos del grupo al darle al link de la miga de pan
    $('#volverGrupo').click(function(e){
		var cadena = $(this).attr('rel');
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').load('cargaGrupo.php?'+cadena);

	});
});
</script>