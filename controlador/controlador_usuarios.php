<?php
include_once '../modelo/usuarios.class.php';

$usuario = new Usuarios();

$opcion = $_GET['opcion'];

/**
 * Opcion 1: Colsultar un usuario.
 * Opcion 2: Agregar un nuevo usuario.
 * Opcion 3: Actualizar un usuario.
 * Opción 4: Eliminar usuario.
 */
switch ($type) {
    
    case 1:
        //echo json_encode($usuario->buscarUsuario($_GET['term']));
    break;

    case 2:
		$usuario->agregarUsuario($_GET);
    break;

    case 3:
        $usuario->editarUsuario($_GET, $_GET['id_usuario']);
    break;

    case 4:
        $usuario->eliminarUsuario($_GET['id_usuario']);
    break;

    default:
    break;
}