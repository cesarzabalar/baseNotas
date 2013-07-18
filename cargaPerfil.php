<?php	
    include ("librerias/class_conectarPG.php");
	include_once("librerias/funciones-comunes.php");
	//instanciación de la clase conexión a postgresql.
	$conexion = new ConexionPGSQL();
	$conexion->conectar();

if($_GET['idUsuario'] == '')
{
	echo 'Esto está vacio';
}else{
	$idUsuario = $_GET['idUsuario'];
	
//Consulta que carga los datos de un usuario especifico	con los datos de su perfil
$queryUsuario = pg_query("SELECT * FROM usuarios WHERE id = '$idUsuario'");
$datosUsuario = pg_fetch_array($queryUsuario);
$conexion->liberar($queryUsuario);

//Calcular edad
$fechaNacim = $datosUsuario['fecha_nacimiento'];
$edad = pg_query("select extract(years from age(current_timestamp, '$fechaNacim'::timestamp))");
$edad2 = pg_fetch_array($edad);
$conexion->liberar($edad);	

//Consulta para obtener el nombre del genero
$queryGenero = pg_query("SELECT usuarios.id, genero.idgenero, genero.nombregenero
						FROM genero INNER JOIN usuarios ON genero.idgenero = usuarios.genero
						WHERE (((usuarios.id)=".$datosUsuario['id']."));");
$datosGenero = pg_fetch_array($queryGenero);
$conexion->liberar($queryGenero);

//Consulta los generos que hay
$queryTodosGeneros = pg_query("SELECT * FROM genero");


//Consulta todos los tipos de RH
$queryTodosRH = pg_query("SELECT * FROM tiporh");

//Consulta para obtener el nombre del RH
$queryRH = pg_query("SELECT usuarios.id, tiporh.idrh, tiporh.nombrerh
					FROM tiporh INNER JOIN usuarios ON tiporh.idrh = usuarios.tiporh
					WHERE (((usuarios.id)=".$datosUsuario['id']."));");
$datosRH = pg_fetch_array($queryRH);
$conexion->liberar($queryRH);
?>

<div class="row-fluid">
	<div class="span12">
    	<ul class="breadcrumb">
			<li class="active">
				Ver - Editar Mi Perfil
            </li>
		</ul>
	</div>
</div>

<div class="row-fluid">	
    <div class="span12">	
        <h4 class="text-info">
                MI PERFIL
        </h4>
    </div>
</div>

<div class="row-fluid">
    <div class="span3">
    	<img src="<?php echo $datosUsuario['foto'];?>" />
        <a id="idGrupo" class="btn btn-info btn-block">Cambiar Foto</a>
    </div>

     <div class="span9" id="datosPerfil">

				<legend>Información Personal</legend>
                	<input type="hidden" id="idUsuario" value="<?php echo $datosUsuario['id']?>" />
                <span><strong>Documento: </strong></span><span class="noeditar"><?php echo $datosUsuario['documento']?></span><br />
                	
                <span><strong>Nombre: </strong></span><span class="editar"><?php echo $datosUsuario['nombres']?> </span>
                <span id="oculto"><input type="text" value="<?php echo $datosUsuario['nombres']?>"  /><a class="grd_info" href="#"><i class="icon-ok"></i></a> <a href="#" class="cancel"><i class="icon-off"></i></a></span><br />
                
                <span><strong>Apellidos: </strong></span><span class="editar"><?php echo $datosUsuario['apellidos']?></span>
                <span id="oculto"><input type="text" value="<?php echo $datosUsuario['apellidos']?>"  /><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a></span><br />
				
                <span><strong>Fecha de Nacimiento: </strong></span><span class="editar"><?php echo fechaEspaniol($datosUsuario['fecha_nacimiento'])?></span>
                <span id="oculto"><input type="date" value="<?php echo $datosUsuario['fecha_nacimiento']?>"  /><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a></span><br />
                
                <span><strong>Edad: </strong></span><span class="noeditar"><?php echo $edad2[0]?></span><br />
                
                <span><strong>Género: </strong></span><span class="editar"><?php echo $datosGenero['nombregenero']?></span>
                <span id="oculto">
                	<select name="genero">
                    	<option value="<?php echo $datosGenero['idgenero']?>"><?php echo $datosGenero['nombregenero']?></option>
                        <?php while($fila = pg_fetch_array($queryTodosGeneros)){?>
                        <option value="<?php echo $fila['idgenero'];?>"><?php echo $fila['nombregenero']?></option>
                        <?php } $conexion->liberar($queryTodosGeneros);?>
                    </select><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a>
                </span><br />
                
                <span><strong>RH: </strong></span><span class="editar"><?php echo $datosRH['nombrerh']?></span>
                <span id="oculto">
                	<select name="rh">
                    	<option value="<?php echo $datosRH['idrh']?>"><?php echo $datosRH['nombrerh']?></option>
                        <?php while($fila = pg_fetch_array($queryTodosRH)){?>
                        <option value="<?php echo $fila['idrh'];?>"><?php echo $fila['nombrerh']?></option>
                        <?php } $conexion->liberar($queryTodosRH);?>
                    </select><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a>
                </span><br />
                
                <span><strong>Dirección: </strong></span><span class="editar"><?php echo $datosUsuario['direccion']?></span>
                <span id="oculto"><input type="text" value="<?php echo $datosUsuario['direccion']?>"  /><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a></span><br />
                
                <span><strong>Teléfono: </strong></span><span class="editar"><?php echo $datosUsuario['telefono']?> </span>
                <span id="oculto"><input type="text" value="<?php echo $datosUsuario['telefono']?>"  /><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a></span><br />
    			
                <span><strong>Celular: </strong></span><span class="editar"><?php echo $datosUsuario['celular']?></span>
                <span id="oculto"><input type="text" value="<?php echo $datosUsuario['celular']?>"  /><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a></span><br />
           		
                <span><strong>Correo Personal: </strong></span><span class="editar"><?php echo $datosUsuario['correo_personal']?></span>
                <span id="oculto"><input type="email" value="<?php echo $datosUsuario['correo_personal']?>"  /><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a></span><br />
                
                <span><strong>Correo Institucional: </strong></span><span class="editar"><?php echo $datosUsuario['correo_institucional']?></span>
                <span id="oculto"><input type="email" value="<?php echo $datosUsuario['correo_institucional']?>"  /><a class="grd_info" href="#">Ok</a> <a href="#" class="cancel">Cancelar</a></span><br />
    </div>
</div>


<?php } $conexion->destruir();?>

<script>
	 $('.textarea').wysihtml5();
</script>

<script>
$(document).ready(function(e) {
	
	//Habilita la opcion para editar la informacion de perfil
	$('.editar').on('click',function(){
		//alert('Me hicieron click');
    	$(this).closest('span').fadeOut('fast', function(){
        $(this).closest('span').next('span').fadeIn('fast');
     	});
        return false;
    });
	
	//Habilita la opcion para cancelar la edicion del perfil
	 $('.cancel').on('click', function(){
        $(this).closest('span').fadeOut('fast', function(){
        $(this).closest('span').prev('span').fadeIn('fast');
        });
        return false;
     });
	
	
});
</script>