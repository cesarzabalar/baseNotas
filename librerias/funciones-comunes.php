<?php
function parametro_plantilla($variable){

    if (isset($GLOBALS[$variable])){
        echo $GLOBALS[$variable];
    }else{
        echo "Sin dato cargado: " . $variable;
    }
}


/*function conecta_base_datos(){
    $conexion = mysql_connect("mysql2.000webhost.com","a7069386_moto","1motoabc");
    mysql_select_db("a7069386_moto");
    return $conexion;
}*/


function fechaEspaniol($fecha)
{
	$data=explode('-',$fecha);
	return $data[2]."/".$data[1]."/".$data[0]; 


	//setlocale(LC_ALL,"es_ES");
	//echo strftime("%A %d de %B del %Y");
}

function fechaActual()
{
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	return $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
}


function doceHoras($hora)
{
	return date("g:i a",strtotime($hora));	
}

//Dar formato a los valores
function formatMoney($number, $fractional=false) { 
    if ($fractional) { 
        $number = sprintf('%.2f', $number); 
    } 
    while (true) { 
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1.$2', $number); 
        if ($replaced != $number) { 
            $number = $replaced; 
        } else { 
            break; 
        } 
    } 
    return $number; 
} 

function dameURL()
{
	$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	return $url;
}

function contadorVisitas()
{
	$archivo="admin/contador.txt";
	$abre=fopen($archivo,"r");
	$total=fread($abre,filesize($archivo));
	fclose($abre);
	$abre=fopen($archivo,"w");
	$total=$total+0.5;
	$grabar=fwrite($abre,$total);
	fclose($abre);
}

function devolverContador()
{
	$archivo="contador.txt";
	$abre=fopen($archivo,"r");
	$total=fread($abre,filesize($archivo));
	fclose($abre);
	
	return $total;
}


?>