<?php
include_once('../librerias/class_conectarPG.php');
$conexion = new ConexionPGSQL();
/**
 * Clase para manipular usuarios
 */
class Usuarios
{
	public function  __construct() {}
	
	/**
     * Seleccionar usuario a partir de un caracter en nombre o apellido
     *
     * @param string $nombreUsuario
     * @return array
     */
    public function buscarUsuario($nombreUsuario){
        $datos = array();

        $sql = "SELECT * FROM usuarios
                WHERE nombre_usuarios LIKE '%$nombreUsuario%'
                OR apellido_usuarios LIKE '%$nombreUsuario%'";

        $resultado = mysql_query($sql);

        while ($row = mysql_fetch_array($resultado, MYSQL_ASSOC)){
            $datos[] = array("value" => $row['nombre_usuarios'].' '.$row['apellido_usuarios'],
							 "nombre" => $row['nombre_usuarios'],
                             "apellido" => $row['apellido_usuarios'],
                             "descripcion" => $row['descripcion_usuarios'],
                             "foto" => $row['foto_usuarios'],
                             "id" => $row['id_usuarios']);
        }

        return $datos;
    }
    /**
     * Agregar usuarios en la base de datos
     *
     * @param array $datos
     */
    public function agregarUsuario($datos){
        $sql = "INSERT INTO usuarios (nombre_usuarios, apellido_usuarios,
                descripcion_usuarios, foto_usuarios) VALUES ('" . $datos['nombre_usuarios'] . "',
                '" . $datos['apellido_usuarios'] . "', '" . $datos['descripcion_usuarios'] . "',
                '" . $datos['foto_usuarios'] . "')";
        mysql_query($sql);
    }
    /**
     * Actualizar informacion de usuario
     *
     * @param array $datos
     * @param int $idUsuario
     */
    public function editarUsuario($datos, $idUsuario){
        if(isset($datos['nombre_usuario'])){

            $sql= "UPDATE usuarios SET nombres='" . $datos['nombre_usuario'] . "'
                   WHERE id = '" . $idUsuario . "'";

        } 
		
		elseif (isset($datos['apellido_usuarios'])){

            $sql = "UPDATE usuarios SET apellidos = '" . $datos['apellido_usuarios'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['fecha_nacimiento'])){

            $sql = "UPDATE usuarios SET fecha_nacimiento = '" . $datos['fecha_nacimiento'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['genero'])){

            $sql = "UPDATE usuarios SET genero = '" . $datos['genero'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['tiporh'])){

            $sql = "UPDATE usuarios SET tiporh = '" . $datos['tiporh'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['direccion'])){

            $sql = "UPDATE usuarios SET direccion = '" . $datos['direccion'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['telefono'])){

            $sql = "UPDATE usuarios SET telefono = '" . $datos['telefono'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['celular'])){

            $sql = "UPDATE usuarios SET celular = '" . $datos['celular'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['fecha_nacimiento'])){

            $sql = "UPDATE usuarios SET fecha_nacimiento = '" . $datos['fecha_nacimiento'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['correo_personal'])){

            $sql = "UPDATE usuarios SET correo_personal = '" . $datos['correo_personal'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['correo_institucional'])){

            $sql = "UPDATE usuarios SET correo_institucional = '" . $datos['correo_institucional'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
        mysql_query($sql);
    }
	
	public function eliminarUsuario($idUsuario)
	{
		
	}
}
?>