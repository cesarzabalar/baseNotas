<?php
//session_start();

$raiz ="./"; //distancia hasta la raiz, empieza en punto y termina en barra
include_once("librerias/funciones-comunes.php");

$descripcion ="Portal administrativo de notas de los colegios";
$keywords ="Notas, colegios";
$titulo_pagina="Base de Notas";

include("includes/cabecera.php");
if(!isset($_SESSION['nombre']))
{
	header("location:login.php");
} else {
	
include ("librerias/class_conectarPG.php");
//instanciación de la clase conexión a postgresql.
$conexion = new ConexionPGSQL();
$conexion->conectar();

//Se captura el id del usuario ingresado
$id = $_SESSION['idUsuario'];

//Consulta para extraer el nombre del colegio al cual pertenece el docente
$query = pg_query("SELECT colegio.id, colegio.nombre, colegio.logo, colegio.slogan, colegio_usuario.idusuario
                  FROM colegio INNER JOIN colegio_usuario ON (colegio.id = colegio_usuario.idcolegio) AND (colegio.id = colegio_usuario.idcolegio)
                  WHERE (((colegio_usuario.idusuario)=".$id."))");

$datos = pg_fetch_array($query);
$conexion->liberar($query);

$idColegio = $datos['id'];

//Consulta para extrarer los datos del docente
$queryUsuario = pg_query("SELECT * FROM usuarios WHERE id = '".$id."'");
$datosDocente = pg_fetch_array($queryUsuario);
$conexion->liberar($queryUsuario);


$queryRoles = pg_query("SELECT usuarios.nombres, roles.nombrerol, roles.idrol
						FROM roles INNER JOIN (usuarios INNER JOIN usuario_rol ON usuarios.id = usuario_rol.idusuario) ON roles.idrol = usuario_rol.idrol
						WHERE  (((usuarios.id)='$id') AND ((usuario_rol.activo)=true))");

//Consulta para extrarer los datos de los anuncios del colegio
$queryAnuncios = pg_query("SELECT * FROM anuncioscolegios WHERE idcolegio = '".$idColegio."' ORDER BY fecha DESC LIMIT 5");

                    
?>
<!--Encabezado-->
<div style="background-color:#ffffff; margin-bottom:20px" class="container-fluid redondeado">
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<div style="margin-top:20px" class="span2">
					<img alt="<?php echo $datos['nombre'] ;?>" src="<?php echo $datos['logo'] ;?>" width="130px" />
				</div>
				<div style="margin-top:20px" class="span10">
					<div class="page-header">
						<h1>
							<?php echo $datos['nombre'] ;?>  <br /><small><?php echo $datos['slogan'] ;?></small>
						</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--Fin Encabezado-->


<!--CONTENEDOR PRINCIPAL-->

<div style="background-color:#ffffff; margin-bottom:20px; padding:10px" class="container-fluid redondeado">
	
	<div class="row-fluid">
    	<!--COLUMNA LATERAL IZQUIERDA-->
		<div class="span3">
        	<h4 class="text-info">
				BIENVENID@
			</h4>
            <!--INFORMACION DEL PERFIL-->
			<div class="row-fluid">
				<div class="span3">
					<img alt="perfil_docente" width="110" src="<?php echo $datosDocente['foto'];?>" class="img-polaroid" />
				</div>
				<div class="span9">
					<p style="margin:10px 0px 0px 10px">
                        <?php echo $datosDocente['nombres'].' '.$datosDocente['apellidos'];?>
					</p>
                    <p style="margin:0px 0px 0px 10px">
                    	<a style="margin-top:10px" href="salir.php" class="btn btn-small btn-info"><i class="icon-remove-sign icon-white"></i>  Salir</a>
                    </p>
				</div>
			</div>
        	
            <div style="margin-top:30px"></div>
            <!--informacion de roles-->
            <h4 class="text-info">
				SELECCIONE UN ROL
			</h4>
			<div class="btn-group">
				 <select name="cbRol" id="cbRol">
                	<?php while($fila = pg_fetch_array($queryRoles)){?>
					<option value="<?php echo $fila['idrol'].'-'.$id;?>"><?php echo $fila['nombrerol'];?></option>
                    <?php }; $conexion->liberar($queryRoles);?>
				</select>
			</div>
            
            <div style="margin-top:30px"></div>
            
            <!--INFORMACION DE PERFIL-->
            <h4 class="text-info">
				INFORMACION
			</h4>
            <a id="editarPerfil" rel="<?php echo $datosDocente['id'];?>" class="btn btn-success btn-mini btn-block">Ver / Editar Perfil</a>
            
            <!--CARGA DINAMICA DE FICHAS-->
            <div id="cargaFichas">
            
            </div>
        </div>
        
    <!--CONTENEDOR CAMBIANTE-->
    <div id="contenedorCambiante" class="span9">
        
        <!--COLUMNA LATERAL CENTRAL-->
        <div class="span8">
        	<h4 id="tituloGrupo" class="text-info text-center">
                    ANUNCIOS GENERALES
                </h4>
                <?php while($fila = pg_fetch_array($queryAnuncios)){?>
                    <div id="masContenido" class="span12">
                        <span class="label label-warning">Publicado el: <?php echo fechaEspaniol($fila['fecha']);?></span>
                    </div>
                    <div class="span10 offset1">
                        <h4 class="text-info">
                            <?php echo $fila['titulo'];?>
                        </h4>
                        <p style="text-align:justify">
                            <?php echo $fila['contenido'];?>
                        </p>
                        
                    
                    </div>
                <?php }; $conexion->liberar($queryAnuncios);?>
                
        </div>
        
        <!--COLUMNA LATERAL DERECHA-->
        <div class="span4">
        	<!--LINKS DE CONSULTA-->
        	<h4 id="titulo" class="text-info text-center">
                    LINKS DE INTERES
                </h4>
                <div id="cargaListas">
                    <a href="#" class="btn btn-link btn-small btn-block" type="button">Matemáticas</a> 
                    <a href="#" class="btn btn-link btn-small btn-block" type="button">Lenguaje</a> 
                    <a href="#" class="btn btn-link btn-small btn-block" type="button">Proyectos de Área</a> 
                    <a href="#" class="btn btn-link btn-small btn-block" type="button">Lineamientos Curriculares</a>
                </div>
            
            <div style="margin-top:30px"></div>
                <!--LINKS INSTITUCIONALES-->
                <h4 id="titulo" class="text-info text-center">
                    INSTITUCIONALES
                </h4>
                <div id="cargaListas">
                    <a href="#" class="btn btn-info btn-small btn-block" type="button">Misión</a> 
                    <a href="#" class="btn btn-info btn-small btn-block" type="button">Visión</a> 
                    <a href="#" class="btn btn-info btn-small btn-block" type="button">Principios</a> 
                </div>
                
            <div style="margin-top:30px"></div>
                <!--BANNER PUBLICITARIO-->
                <h4 id="titulo" class="text-info text-center">
                    BANNER
                </h4>
                <img alt="banner" src="img/colegios/1_sanjose/otras/banner1.jpg" class="img-polaroid" />
                
        </div>
    </div>
    
    <!--CIERRO CONTENEDOR CAMBIANTE-->
    </div>
</div>

<?php
	include("includes/pie.php");
	$conexion->destruir();
	}
 ?>
 
 <script>
 	$(document).ready(function(e) {
        $('#editarPerfil').click(function(e){
			var idUsuario = $(this).attr('rel');	
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').html('<div class="loaderAjax"><img src="img/ajax_loader3.gif"/></div');
		$('.loaderAjax').show();
		
			
		//Llamado Ajax		
		$.ajax({
			   type: "GET",
			   url: "cargaPerfil.php",
			   data: "idUsuario="+idUsuario,
			   success: function(data){
				  // alert(data);
				$('#contenedorCambiante').html(data); //Respuesta del servidor
				$('.loaderAjax').fadeOut();			
   			}//cierro success
		});//cierro ajax
		});
    });
 
 </script>