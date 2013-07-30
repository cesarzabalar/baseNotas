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
                <span id="oculto"><input type="text" id="nombre_usuario" value="<?php echo $datosUsuario['nombres']?>"  />  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
                
                <span><strong>Apellidos: </strong></span><span class="editar"><?php echo $datosUsuario['apellidos']?></span>
                <span id="oculto"><input type="text" id="apellido_usuario" value="<?php echo $datosUsuario['apellidos']?>"  />  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
				
                <span><strong>Fecha de Nacimiento: </strong></span><span class="editar"><?php echo fechaEspaniol($datosUsuario['fecha_nacimiento'])?></span>
                <span id="oculto"><input type="text" id="fecha_usuario" value="<?php echo $datosUsuario['fecha_nacimiento']?>"  />  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
                
                <span><strong>Edad: </strong></span><span class="noeditar"><?php echo $edad2[0]?></span><br />
                
                <span><strong>Género: </strong></span><span class="editar"><?php echo $datosGenero['nombregenero']?></span>
                <span id="oculto">
                	<select name="genero" id="genero_usuario">
                    	<option value="<?php echo $datosGenero['idgenero']?>"><?php echo $datosGenero['nombregenero']?></option>
                        <?php while($fila = pg_fetch_array($queryTodosGeneros)){?>
                        <option value="<?php echo $fila['idgenero'];?>"><?php echo $fila['nombregenero']?></option>
                        <?php } $conexion->liberar($queryTodosGeneros);?>
                    </select>  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
                
                <span><strong>RH: </strong></span><span class="editar"><?php echo $datosRH['nombrerh']?></span>
                <span id="oculto">
                	<select name="rh" id="rh_usuario">
                    	<option value="<?php echo $datosRH['idrh']?>"><?php echo $datosRH['nombrerh']?></option>
                        <?php while($fila = pg_fetch_array($queryTodosRH)){?>
                        <option value="<?php echo $fila['idrh'];?>"><?php echo $fila['nombrerh']?></option>
                        <?php } $conexion->liberar($queryTodosRH);?>
                    </select>  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
                
                <span><strong>Dirección: </strong></span><span class="editar"><?php echo $datosUsuario['direccion']?></span>
                <span id="oculto"><input type="text" id="direccion_usuario" value="<?php echo $datosUsuario['direccion']?>"  />  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
                
                <span><strong>Teléfono: </strong></span><span class="editar"><?php echo $datosUsuario['telefono']?> </span>
                <span id="oculto"><input type="text" id="telefono_usuario" value="<?php echo $datosUsuario['telefono']?>"  />  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
    			
                <span><strong>Celular: </strong></span><span class="editar"><?php echo $datosUsuario['celular']?></span>
                <span id="oculto"><input type="text" id="celular_usuario" value="<?php echo $datosUsuario['celular']?>"  />  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
           		
                <span><strong>Correo Personal: </strong></span><span class="editar"><?php echo $datosUsuario['correo_personal']?></span>
                <span id="oculto"><input type="email" id="correop_usuario" value="<?php echo $datosUsuario['correo_personal']?>"  />  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
                
                <span><strong>Correo Institucional: </strong></span><span class="editar"><?php echo $datosUsuario['correo_institucional']?></span>
                <span id="oculto"><input type="email" id="correoi_usuario"value="<?php echo $datosUsuario['correo_institucional']?>"  />  <a style="margin-top: -10px" class="grd_info btn btn-success"> <i class="icon-ok icon-white"></i>  </a> <a style="margin-top: -10px" class="cancel btn btn-danger"> <i class="icon-remove icon-white"></i></a></span><br/>
    </div>
</div>


<?php } $conexion->destruir();?>

<script>
	 $('.textarea').wysihtml5();
</script>

<script>
$(document).ready(function(e) {
	
	$( "#fecha_usuario" ).datepicker({ 
		changeMonth: true,
		changeYear: true,
		showAnim: 'bounce',
		dateFormat: "dd-mm-yy"
		});
	
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
	 
	 /**
     * Función para guardar los cambios de actualizacion
     */
    $('.grd_info').on('click', function(){
        var data;
        var element = $(this);
        var id_usuario = $('#idUsuario').val();
		var nombre_usuario = $('#nombre_usuario').val();
		var apellido_usuario = $('#apellido_usuario').val();
        var fecha_usuario = $('#fecha_usuario').val();
        var genero_usuario = $('#genero_usuario').find(':selected').val();
		var genero_usuariot = $('#genero_usuario').find(':selected').html();
        var rh_usuario = $('#rh_usuario').find(':selected').val();
		var rh_usuariot = $('#rh_usuario').find(':selected').html();
		var direccion_usuario = $('#direccion_usuario').val();
		var telefono_usuario = $('#telefono_usuario').val();
		var celular_usuario = $('#celular_usuario').val();
		var correop_usuario = $('#correop_usuario').val();
		var correoi_usuario = $('#correoi_usuario').val();
        var info_usuario = $(this).siblings('input').val();
        
		
        if ( $(this).siblings('input').attr('id') == 'nombre_usuario' )
        {
            data = 'opcion=3&nombre_usuario=' + nombre_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        nombre_usuario);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");
					
         } else if ( $(this).siblings('input').attr('id') == 'apellido_usuario' )
         {
         	data = 'opcion=3&apellido_usuario=' + apellido_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        apellido_usuario);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");   		
         } else if ( $(this).siblings('input').attr('id') == 'fecha_usuario' )
         {
         	data = 'opcion=3&fecha_usuario=' + fecha_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        fecha_usuario);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");   		
         } else if ( $(this).siblings('select').attr('id') == 'genero_usuario' )
         {
         	data = 'opcion=3&genero_usuario=' + genero_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        genero_usuariot);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");   		
         } else if ( $(this).siblings('select').attr('id') == 'rh_usuario' )
         {
         	data = 'opcion=3&rh_usuario=' + rh_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        rh_usuariot);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");   		
         } else if ( $(this).siblings('input').attr('id') == 'direccion_usuario' )
         {
         	data = 'opcion=3&direccion_usuario=' + direccion_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        direccion_usuario);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");   		
         } else if ( $(this).siblings('input').attr('id') == 'telefono_usuario' )
         {
         	data = 'opcion=3&telefono_usuario=' + telefono_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        telefono_usuario);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");   		
         } else if ( $(this).siblings('input').attr('id') == 'celular_usuario' )
         {
         	data = 'opcion=3&celular_usuario=' + celular_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        celular_usuario);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");   		
         } else if ( $(this).siblings('input').attr('id') == 'correop_usuario' )
         {
         	data = 'opcion=3&correop_usuario=' + correop_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        correop_usuario);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    height: 100,
                    modal: true
                });
            }, "json");   		
         } else if ( $(this).siblings('input').attr('id') == 'correoi_usuario' )
         {
         	data = 'opcion=3&correoi_usuario=' + correoi_usuario + '&id_usuario=' + id_usuario;
						
            $.get('controlador/controlador_usuarios.php', data, function(respuesta)
            {
                element.closest('span').fadeOut('fast', function(){
                    $(this).closest('span').prev('span').html(
                        correoi_usuario);
                    
                    $(this).closest('span').prev('span').fadeIn('fast');
                });
                
                $( "#mensaje2" ).text(respuesta.mensaje);
                $( "#mensaje2" ).show();
                $( "#mensaje2" ).dialog({
                    hide: "explode",
					height: 100,
                    modal: true
                });
            }, "json");   		
         } 
        return false;
    });
	
	
});
</script>