$(document).ready(function(){
	
	$('.alert').hide();
						   
	$('#frmLogin').submit(function(e){
		
		$('.alert alert-error strong').html('');
		
		$('.input-prepend input').css({"border":'solid #999 1px'});
		

		$('section').fadeOut();

		var datos = $(this).serialize()+'&fromAjax=1';
		//alert(datos);
		
		$.ajax({
			   type: "POST",
			   url: "procSesion.php",
			   dataType: 'json',
			   data: datos,	   
			   success: function(data){
	  			
					$('section').fadeIn();
				
				   if(!data.status)
				   {	
				   		//alert(data.status);
					   
            		   $( "#mensaje" ).dialog({
							  height: 100,
							  modal: true
							});
					   $( "#mensaje" ).show(); // Ponemos en visible en contenedor del mensaje	
				       $('#mensaje').html(data.mensaje);	
					   
						$('input[type!=button]').each(function()
						{
							var elem = $(this);
							
							if(elem.attr('value')=='')
							{
								showTooltip(elem);	
							}
						});//cierro each
					}//fin if data.status
					else{
							location.replace(data.redirectURL);
							//alert(data.redirectURL);
						}				
		      }//cierro success

        });//cierro funcion ajax
		e.preventDefault();
	});

});

function showTooltip(elem)
{
	elem.css({"border":'solid #F00 1px'});
	elem.blur();
}