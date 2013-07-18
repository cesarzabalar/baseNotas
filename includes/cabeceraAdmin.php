
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Base de Notas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php parametro_plantilla("descripcion");?>">
    <meta name="keywords" content="<?php parametro_plantilla("keywords");?>">
    <meta name="author" content="">

    <!-- Le styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo $raiz;?>bootstrap/css/bootstrap.css" >
    <link rel="stylesheet" type="text/css" href="<?php echo $raiz;?>bootstrap/css/bootstrap-wysihtml5.css"></link>
    <style type="text/css">
      body {
		background-color:#EEEEEE;
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="<?php echo $raiz;?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="bootstrap/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $raiz;?>bootstrap/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $raiz;?>bootstrap/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $raiz;?>bootstrap/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $raiz;?>bootstrap/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="<?php echo $raiz;?>bootstrap/ico/favicon.png">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.php">Base de Notas</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">Inicio</a></li>
              <li><a href="#about">Nosotros</a></li>
              <li><a href="#contact">Contacto</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Servicios <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="login.php">Ingresar</a></li>
                  <li><a href="#">Registrarse</a></li>
                  <li><a href="#">Olvidó la contraseña?</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
            </ul>
            <div style="color:#ffffff; float:right; font-size:10px; padding-top:10px">
				<?php echo "Bienvenido ".$_SESSION["nombre"]." ".$_SESSION["apellido"]."!!"?>
                <a href="salir.php">Salir</a>
            </div>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    
   <div class="container">
   

