$(document).ready(function()
{
	

	$(".menuicon").click(function()
	{
		$(".menu").slideToggle();
	});
	
	$(window).resize(function()
	{
		if($(this).width() < 767)
		{
			$(".menu").hide();
		} 
		else
		{
			$(".menu").show();
		}
	});
	
	
	//Llamado de los botones de los grupos
	$("#btnGrupos a").click(function(e)
	{
		$('#contenedorCambiante').append("<div id='loading'></div>");
		$('#loading').html('Cargando...');
		$('#loading').show();
		
		var idGrupo = $(this).attr('rel');
		var nombreGrupo = $(this).html();
		
		//Llamado Ajax		
		$.ajax({
			   type: "GET",
			   url: "cargaAlumnos.php",
			   data: "idGrupo="+idGrupo,
			   success: function(data){
				  // alert(data);
				$('#contenedorCambiante').html(data); //Respuesta del servidor
				$('#loading').fadeOut();			
   			}//cierro success
		});//cierro ajax
		e.preventDefault();
	});
	
	/************************ llamado al componente de roles ***********************/
	$('#cbRol').change(function(){
            var cadena = $(this).find(':selected').val();
			var variables = cadena.split("-");
			
			if(variables[0] == 1)
			{
            	location.replace('admin.php');
			}else{
				$('#cargaFichas').html('');
				$('#cargaFichas').html('<div class="loaderAjax"><img src="img/ajax_loader3.gif"/></div');
				$('.loaderAjax').show();
				
				//$("#cargaFichas").load('cargaFichas.php?idRol='+variables[0]+'&idUsuario='+variables[1]);
				
				//Llamado Ajax		
				$.ajax({
					   type: "GET",
					   url: "cargaFichas.php",
					   data: "idRol="+variables[0]+"&idUsuario="+variables[1],
					   success: function(data){
						  // alert(data);
						$('#cargaFichas').html(data); //Respuesta del servidor
						$('.loaderAjax').fadeOut();			
					}//cierro success
				});//cierro ajax
			}
        });
	
});

