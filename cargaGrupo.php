<?php
	session_start();
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


//Consulta que carga el nombre de la asignatura del profesor dada a un grupo
$queryNombreAsignatura = pg_query("SELECT asignatura.id, asignatura.nombre
FROM usuarios INNER JOIN ((grupo INNER JOIN usuario_grupo ON grupo.id = usuario_grupo.idgrupo) INNER JOIN ((asignatura INNER JOIN usuario_asignatura ON asignatura.id = usuario_asignatura.idasignatura) INNER JOIN asignatura_grupo ON asignatura.id = asignatura_grupo.idasignatura) ON grupo.id = asignatura_grupo.idgrupo) ON (usuarios.id = usuario_grupo.idusuario) AND (usuarios.id = usuario_asignatura.idusuario)
WHERE (((usuarios.id)=".$idUsuario.") AND ((grupo.id)=".$idGrupo.") AND ((grupo.anio)='".$anioActual."'))");
$asignatura = pg_fetch_array($queryNombreAsignatura);
$conexion->liberar($queryNombreAsignatura);

//Consulta que carga el listado de usuarios de rol alumnos que pertenezcan a un grupo
$queryListaAlumnos = pg_query("SELECT usuarios.id, usuarios.nombres, usuarios.apellidos, usuario_rol.activo, grupo.anio
FROM (usuarios INNER JOIN (grupo INNER JOIN usuario_grupo ON grupo.id = usuario_grupo.idgrupo) ON usuarios.id = usuario_grupo.idusuario) INNER JOIN (roles INNER JOIN usuario_rol ON roles.idrol = usuario_rol.idrol) ON usuarios.id = usuario_rol.idusuario
WHERE (((roles.idrol)=3) AND ((usuario_rol.activo)=true) AND ((grupo.id)=".$idGrupo.") AND ((grupo.anio)='".$anioActual."')) ORDER BY usuarios.nombres ASC");

//Consulta para contar los alumnos de un grupo
$queryCantidadAlumnos = pg_query("SELECT usuarios.id, usuarios.nombres, usuarios.apellidos, usuario_rol.activo, grupo.anio
FROM (usuarios INNER JOIN (grupo INNER JOIN usuario_grupo ON grupo.id = usuario_grupo.idgrupo) ON usuarios.id = usuario_grupo.idusuario) INNER JOIN (roles INNER JOIN usuario_rol ON roles.idrol = usuario_rol.idrol) ON usuarios.id = usuario_rol.idusuario
WHERE (((roles.idrol)=3) AND ((usuario_rol.activo)=true) AND ((grupo.id)=".$idGrupo.") AND ((grupo.anio)='".$anioActual."')) ORDER BY usuarios.nombres ASC");
$cantidadAlumnos = pg_num_rows($queryCantidadAlumnos);
$conexion->liberar($queryCantidadAlumnos);

//Consulta para tare el director de grupo
$queryDirectorGrupo = pg_query("SELECT grupo.director, usuarios.nombres, usuarios.apellidos
FROM usuarios INNER JOIN grupo ON usuarios.id = grupo.director
WHERE (((grupo.director)='".$datosGrupos['director']."'));");
$datosDirectorGrupo = pg_fetch_array($queryDirectorGrupo);
$conexion->liberar($queryDirectorGrupo);

//Consulta para seleccionar el periodo que se este cursando actualmente
$queryPeriodoActual = pg_query("SELECT * FROM periodo WHERE current_date BETWEEN fechainicio AND fechafin AND idcolegio='".$_SESSION['idColegio']."'");
$datosPeriodoActual = pg_fetch_array($queryPeriodoActual);
$conexion->liberar($queryPeriodoActual);

$fechaFin = $datosPeriodoActual['fechafin'];
$fechaHoy = date('Y-m-d');
$semanasFaltantes = date("W",strtotime($fechaFin))-date("W",strtotime($fechaHoy));

//Consulta para extraer el numero de notas que se le ha dado a un grupo por asignatura
$queryCantNotas = pg_query("SELECT asignatura_grupo.cantidadnotas
FROM grupo INNER JOIN (asignatura INNER JOIN asignatura_grupo ON asignatura.id = asignatura_grupo.idasignatura) ON grupo.id = asignatura_grupo.idgrupo
WHERE (((grupo.id)=1) AND ((asignatura.id)=1));");
$datosCanNotas = pg_fetch_array($queryCantNotas);
$conexion->liberar($queryCantNotas);
$numNotas = $datosCanNotas['cantidadnotas'];

//Consulta para seleccionar los periodos anteriores al activo
$queryPeriodos = pg_query("SELECT periodo.id, periodo.nombre, periodo.fechafin
						  FROM periodo
						  WHERE (((periodo.fechafin)<current_date));");
					 
?>

<div class="row-fluid">
	<div class="span12">
    	<ul class="breadcrumb">
			<li class="active">
				GRUPO <?php echo $datosGrupos['nombre']?>
			</li>
		</ul>
	</div>
</div>

<div class="row-fluid">
	<div class="span6">
    	<h4 class="text-info">
        	INFORMACION DEL GRUPO <?php echo $datosGrupos['nombre']?>
        </h4>
        <p><strong>Asignatura: </strong><?php echo $asignatura['nombre']?><br />
           <strong>Director de Grupo: </strong><?php echo $datosDirectorGrupo['nombres']." ".$datosDirectorGrupo['apellidos']?><br />
           <strong>Jornada: </strong><br />
           <strong>No. de Alumnos: </strong><?php echo $cantidadAlumnos?></p>
    </div>
    <div class="span6">
    	<a id="btnAnotacionesGrupo" class="btn btn-success">Ver / Hacer Anotaciones al Grupo</a>
    </div>
</div>

<div class="row-fluid">
	<div class="span6">
    	<h4 class="text-info">
        	PERIODO ACTUAL: <?php echo $datosPeriodoActual['nombre'];?>
        </h4>
        Desde el <span class="text-warning"><?php echo fechaEspaniol($datosPeriodoActual['fechainicio'])?></span> al 
                 <span class="text-warning"><?php echo fechaEspaniol($datosPeriodoActual['fechafin'])?></span><br />
        
        <?php if($semanasFaltantes < 2){?>
        		<span class="label label-important">Falta 1 Semana para finalizar el periodo</span>
        <?php }else{ ?>
        	    Faltan <span class="label label-warning"><?php echo $semanasFaltantes?> Semanas</span> para finalizar el periodo
		<?php }?>
    </div>
    <div class="span6">
    	<h4 class="text-info">Ver notas periodos anteriores</h4>
    	<select id="periodos">
        	<?php while($datosPeriodos = pg_fetch_array($queryPeriodos)) {?>
                <option value="<?php echo $datosPeriodos['id']?>"><?php echo $datosPeriodos['nombre']?></option>
            <?php }?>
        </select>
    </div>
</div>

<div class="row-fluid">
	<div class="span12">
    	<h4 class="text-info">
        	PLANTILLA DE NOTAS <?php echo $datosGrupos['nombre']?>
        </h4>
    	<table class="datatable table table-striped table-bordered" id="example">
				<thead>
					<tr>
                    	<th>
						</th>
						<th>
							#
						</th>
						<th>
							Nombre y Apellidos
						</th>
                        <?php for($i=1; $i<=$numNotas;$i++){?>
						<th>
							Nota <?php echo $i;?>
						</th>
                        <?php }?>
					</tr>
				</thead>
				<tbody>
                <?php 
				$contador = 1;
				
				while($alumnos = pg_fetch_array($queryListaAlumnos)){
					//Consulta para extraer las notas de un alumno segun la asignatura y el periodo
					$queryNotas = pg_query("SELECT usuarios.nombres, usuarios.apellidos, asignatura.nombre, calificacion.calificacion, periodo.nombre
					FROM ((calificacion INNER JOIN usuarios ON calificacion.idusuario = usuarios.id) INNER JOIN periodo ON calificacion.idperiodo = periodo.id) INNER JOIN asignatura ON calificacion.idasignatura = asignatura.id
					WHERE (((usuarios.id)='".$alumnos['id']."') AND ((asignatura.id)='".$asignatura['id']."') AND ((periodo.id)='".$datosPeriodoActual['id']."'));");
					
					
					?>
					<tr>
                    	<td>
							<input type="checkbox" name="enviarMensaje[]" value="<?php echo $alumnos['id']?>">
						</td>
						<td>
							<?php echo $contador?>
						</td>
						<td id="selAlumno">
							<a rel="<?php echo $alumnos['id']?>"><?php echo $alumnos['nombres'].' '.$alumnos['apellidos']?></a>
						</td>
                        <?php while($datosNotas = pg_fetch_array($queryNotas)){?>
						<td>
							<?php echo $datosNotas['calificacion'];?>
						</td>
						<?php } $conexion->liberar($queryNotas);?>
                        
					</tr>
                <?php $contador++;  } $conexion->liberar($queryListaAlumnos);?>
				</tbody>
			</table>
            <button class="btn" type="button">Enviar Mensaje</button>
    </div>
</div>


<?php } $conexion->destruir();?>

<script>
$(document).ready(function(e) {
    $('#selAlumno a').click(function(e){
		//alert('me han hecho clik');
		var idAlumno = $(this).attr('rel');
		var idGrupo = <?php echo $idGrupo?>;
		var idUsuario = <?php echo $idUsuario?>;
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').html('<div class="loaderAjax"><img src="img/ajax_loader3.gif"/></div');
		$('.loaderAjax').show();
		
		//Llamado Ajax		
		$.ajax({
			   type: "GET",
			   url: "cargaAlumno.php",
			   data: 'idAlumno='+idAlumno+'&idGrupo='+idGrupo+'&idUsuario='+idUsuario,
			   success: function(data){
				  // alert(data);
				$('#contenedorCambiante').html(data); //Respuesta del servidor
				$('.loaderAjax').fadeOut();			
   			}//cierro success
		});//cierro ajax
	});
	
	
	$('#btnAnotacionesGrupo').click(function(e){
		var idGrupo = <?php echo $idGrupo?>;
		var idUsuario = <?php echo $idUsuario?>;
		
		$('#contenedorCambiante').html('');
		$('#contenedorCambiante').html('<div class="loaderAjax"><img src="img/ajax_loader3.gif"/></div');
		$('.loaderAjax').show();
		
		//Llamado Ajax		
		$.ajax({
			   type: "GET",
			   url: "cargaAnotacionGrupo.php",
			   data: 'idGrupo='+idGrupo+'&idUsuario='+idUsuario,
			   success: function(data){
				  // alert(data);
				$('#contenedorCambiante').html(data); //Respuesta del servidor
				$('.loaderAjax').fadeOut();			
   			}//cierro success
		});//cierro ajax
	});
});
</script>

<!-- jQuery DataTable -->
		<script src="js/jquery.datatables.min.js"></script>
		<script>
			/* Default class modification */
			$.extend( $.fn.dataTableExt.oStdClasses, {
				"sWrapper": "dataTables_wrapper form-inline"
			} );
			
			/* API method to get paging information */
			$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
			{
				return {
					"iStart":         oSettings._iDisplayStart,
					"iEnd":           oSettings.fnDisplayEnd(),
					"iLength":        oSettings._iDisplayLength,
					"iTotal":         oSettings.fnRecordsTotal(),
					"iFilteredTotal": oSettings.fnRecordsDisplay(),
					"iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
					"iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
				};
			}
			
			/* Bootstrap style pagination control */
			$.extend( $.fn.dataTableExt.oPagination, {
				"bootstrap": {
					"fnInit": function( oSettings, nPaging, fnDraw ) {
						var oLang = oSettings.oLanguage.oPaginate;
						var fnClickHandler = function ( e ) {
							e.preventDefault();
							if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
								fnDraw( oSettings );
							}
						};
			
						$(nPaging).addClass('pagination').append(
							'<ul>'+
								'<li class="prev disabled"><a href="#"><span class="icon-caret-left"></span> '+oLang.sPrevious+'</a></li>'+
								'<li class="next disabled"><a href="#">'+oLang.sNext+' <span class="icon-caret-right"></span></a></li>'+
							'</ul>'
						);
						var els = $('a', nPaging);
						$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
						$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
					},
			
					"fnUpdate": function ( oSettings, fnDraw ) {
						var iListLength = 0;
						var oPaging = oSettings.oInstance.fnPagingInfo();
						var an = oSettings.aanFeatures.p;
						var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);
			
						if ( oPaging.iTotalPages < iListLength) {
							iStart = 1;
							iEnd = oPaging.iTotalPages;
						}
						else if ( oPaging.iPage <= iHalf ) {
							iStart = 1;
							iEnd = iListLength;
						} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
							iStart = oPaging.iTotalPages - iListLength + 1;
							iEnd = oPaging.iTotalPages;
						} else {
							iStart = oPaging.iPage - iHalf + 1;
							iEnd = iStart + iListLength - 1;
						}
			
						for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
			
							// Add / remove disabled classes from the static elements
							if ( oPaging.iPage === 0 ) {
								$('li:first', an[i]).addClass('disabled');
							} else {
								$('li:first', an[i]).removeClass('disabled');
							}
			
							if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
								$('li:last', an[i]).addClass('disabled');
							} else {
								$('li:last', an[i]).removeClass('disabled');
							}
						}
					}
				}
			});
			
			/* Table #example */
			$(document).ready(function() {
				$('.datatable').dataTable( {
					"sDom": "<'row'<'span4'l><'span4'f>r>t<'row'<'span4'i><'span4'p>>",
					"sPaginationType": "bootstrap",
					"oLanguage": {
						"sLengthMenu": "_MENU_ registros por página"
					}
				});
			});
		</script>