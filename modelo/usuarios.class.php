<?php
include_once('../librerias/class_conectarPG.php');
/**
 * Clase para manipular usuarios
 */
class Usuarios extends ConexionPGSQL
{
	public function  __construct() {

	}
	
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
		$this->conectar();
        if(isset($datos['nombre_usuario'])){

            $sql= "UPDATE usuarios SET nombres='" . $datos['nombre_usuario'] . "'
                   WHERE id = '" . $idUsuario . "'";

        } 
		
		elseif (isset($datos['apellido_usuario'])){

            $sql = "UPDATE usuarios SET apellidos = '" . $datos['apellido_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['fecha_usuario'])){

            $sql = "UPDATE usuarios SET fecha_nacimiento = '" . $datos['fecha_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['genero_usuario'])){

            $sql = "UPDATE usuarios SET genero = '" . $datos['genero_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['rh_usuario'])){

            $sql = "UPDATE usuarios SET tiporh = '" . $datos['rh_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['direccion_usuario'])){

            $sql = "UPDATE usuarios SET direccion = '" . $datos['direccion_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['telefono_usuario'])){

            $sql = "UPDATE usuarios SET telefono = '" . $datos['telefono_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['celular_usuario'])){

            $sql = "UPDATE usuarios SET celular = '" . $datos['celular_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['correop_usuario'])){

            $sql = "UPDATE usuarios SET correo_personal = '" . $datos['correop_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
		
		elseif (isset($datos['correoi_usuario'])){

            $sql = "UPDATE usuarios SET correo_institucional = '" . $datos['correoi_usuario'] . "'
                    WHERE id = '" . $idUsuario . "'";
        }
        
		if(pg_query($sql)){
			echo '{"status":1,"mensaje":"Usuario modificado exitosamente"}';	
		}else{
			echo '{"status":0,"mensaje":"Error, no se ha podido completar la acción"}';
		}
		$this->destruir();
    }
	
	public function eliminarUsuario($idUsuario)
	{
		
	}
}
?>