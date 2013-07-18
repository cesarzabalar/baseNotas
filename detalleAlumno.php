<?php

if(isset($_GET))
{
	$idAlumno = $_GET['idAlumno'];
	
	include ("librerias/class_conectarPG.php");
	include_once("librerias/funciones-comunes.php");
	//instanciación de la clase conexión a postgresql.
	$conexion = new ConexionPGSQL();
	$conexion->conectar();
	$contador=1;
	
	$queryAlumnos = pg_query("SELECT * FROM alumno WHERE id = '$idAlumno'");
	$infoAlumnos = pg_fetch_array($queryAlumnos);
	$conexion->liberar($queryAlumnos);
	
	$queryAnotaciones = pg_query("SELECT * FROM anotacionalumno WHERE idalumno = '$idAlumno'");
	
	$queryPeriodos = pg_query("SELECT * FROM periodo");
	
	//calculos
	$fechaNacim = $infoAlumnos['fechanacimiento'];
	$edad = pg_query("select extract(years from age(current_timestamp, '$fechaNacim'::timestamp))");
	$edad2 = pg_fetch_array($edad);
	$conexion->liberar($edad);
	
	
?>
  		
    
    <!--Lugar cambiante segun las opciones seleccionadas-->

        
                    
		<!--INICIO DE LOS TABS-->
					
            <div class="tabbable" id="tabs-628705">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#panel-63289" data-toggle="tab">Detalle Alumno</a>
					</li>
					<li>
						<a href="#panel-179017" data-toggle="tab">Anotaciones</a>
					</li>
				</ul>
				<div class="tab-content">
                <!--PRIMER TAB-->
					<div class="tab-pane active" id="panel-63289">
						<div class="row-fluid">
                            <div class="span2">
                            	<img src="<?php echo $infoAlumnos['foto'];?>" width="114px" class="img-polaroid"/>
                            </div>
                            <div class="span10">
                            	<h3 style=" margin-top:-10px" class="text-info text-left"><?php echo $infoAlumnos['nombre']." ".$infoAlumnos['apellido'];?></h3>
                                <span class="label label-success">Documento:</span>  <?php echo $infoAlumnos['documento']?><br />
                                <span class="label label-success">Edad:</span> <?php echo $edad2[0];?> años<br />
                                <span class="label label-success">Telefono:</span>  <?php echo $infoAlumnos['telefono']?><br />
                                <span class="label label-success">Dirección:</span>  <?php echo $infoAlumnos['direccion']?><br /><br />
                                <a id="modal-270673" href="#modal-container-270673" role="button" class="btn btn-small btn-info" data-toggle="modal"><i class="icon-th-list icon-white"></i>  Mas Información</a>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                            <h4 class="text-info text-left">CALIFICACIONES</h4>
                            	<div class="btn-group dropup">
                                	
                                     <button class="btn">Seleccionar Periodo</button> <button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
                                    <ul id="periodos" class="dropdown-menu">
                                    	<?php while($fila = pg_fetch_array($queryPeriodos)){?>
                                        <li>
                                            <a rel="<?php echo $fila['id'];?>" href="#"><?php echo $fila['nombre'];?></a>
                                        </li>
                                        <?php } $conexion->liberar($queryPeriodos);?>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div><!--FIN LISTA PERIODOS-->
                        
                        <div class="row-fluid">
                            <div id="cargaLogros" class="span4">

                            </div>
                            <div class="span4"></div><!--Se cargan los indicadores-->
                            <div class="span4"></div><!--Se cargan los desempeños-->
                        </div>
                        
                        
                        
					</div><!--FIN PRIMER TAB-->
                    
                    
                    <!--SEGUNDO TAB-->
					<div class="tab-pane" id="panel-179017">
						<p>
                        <?php while($fila = pg_fetch_array($queryAnotaciones)){
							$queryDocentes = pg_query("SELECT * FROM docente WHERE id = '".$fila['iddocente']."'");
							$infoDocente = pg_fetch_array($queryDocentes);
							$conexion->liberar($queryDocentes);
							
							?>
							<span class="label label-success">Publicado el:  <?php echo fechaEspaniol($fila['fecha']);?> - Hora: <?php echo $fila['hora'];?></span><br />
                            <span class="label label-alert">Realizada por:  <?php echo $infoDocente['nombre']." ".$infoDocente['apellido'];?></span><br />
                               <div class="span10 offset1">
									<p>
										<?php echo $fila['descripcion'];?>
									</p>  
							   </div>
                         <?php } $conexion->liberar($queryAnotaciones);?>
						</p>
                        <form method="post">
                            <textarea class="textarea span12" placeholder="Escriba aqui la anotación"></textarea>
                            <button class="btn btn-small btn-success" type="button">Agregar</button>
                        </form>
					</div>
				</div>
			</div>
            
            
            <!--VENTANA MODAL DE ALUMNO-->
			<div id="modal-container-270673" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel" class="text-info text-left">
						Alumno: <?php echo $infoAlumnos['nombre']." ".$infoAlumnos['apellido'];?>
					</h3>
				</div>
				<div class="modal-body">
					<p>
                            <div class="row-fluid">
                                <div class="span3">
                                	<img alt="peril_alumno" width="140" src="<?php echo $infoAlumnos['foto'];?>" class="img-polaroid"/>
                                    <button class="btn btn-block btn-mini btn-info" type="button">Cambiar Foto</button>
                                </div>
                                <div class="span9">
                                	<form>
                                        <fieldset>
                                             <legend>Información</legend> 
                                             <label>Campo 1</label>
                                             <input  type="text" />
                                             <label>Campo 2</label>
                                             <input  type="text" />
                                             <label>Campo 3</label>
                                             <input type="text" />
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
					</p>
				</div>
				<div class="modal-footer">
					 <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button> <button class="btn btn-primary">Guardar</button>
				</div>
             </div><!--FIN VENTANA MODAL-->
            
 
 <script>
	 $('.textarea').wysihtml5();
	 
	 //Ajax para cargar los logros de los periodos
	 $("#periodos a").click(function(e)
	{	
		$('#cargaLogros').html('');	
		$('#cargaLogros').append("<div id='loading'></div>");
		$('#loading').html('Cargando...');
		$('#loading').show();
		
		var idPeriodo = $(this).attr('rel');
		
		//Llamado Ajax		
		$.ajax({
			   type: "GET",
			   url: "cargaLogros.php",
			   data: "idPeriodo="+idPeriodo,
			   success: function(data){
				  // alert(data);
				$('#tituLogros').html(data);  
				$('#cargaLogros').html(data); //Respuesta del servidor
				$('#loading').fadeOut();			
   			}//cierro success
		});//cierro ajax
		e.preventDefault();
	});
 </script>
       
        


<?php }?>