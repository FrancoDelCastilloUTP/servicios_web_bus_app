<?php

/**
 * Representa el la estructura de los Usuarios
 * almacenadas en la base de datos
 */
require 'Database.php';

class User
{
    function __construct()
    {
    }

    /**
     * Retorna en la fila especificada de la tabla 'users'
     *
     * @param $idUsuario Identificador del registro
     * @return array Datos del registro
     */
    public static function getAll()
    {
        $consulta = "SELECT * FROM users";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            return  false;
        }
    }

    /**
     * Obtiene los campos de un usuario con un identificador
     * determinado
     *
     * @param $idUsuario Identificador del usuario
     * @return mixed
     */
    public static function registrarTarjeta($user_id, $card_id)
    {

        // Consulta de actualización
        $consulta = "UPDATE users SET card_id = ? WHERE user_id = ?";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($card_id, $user_id));

       
            // Consulta para obtener el registro actualizado
            $consulta_usuario = "SELECT * FROM users WHERE user_id = ?";
            $comando_usuario = Database::getInstance()->getDb()->prepare($consulta_usuario);
            $comando_usuario->execute(array($user_id));
            $row = $comando_usuario->fetch(PDO::FETCH_ASSOC);

            // Impresión de JSON con el registro actualizado
            
            return $row;
        
        } catch (PDOException $e) {
            // Aquí puedes clasificar el error dependiendo de la excepción
            // para presentarlo en la respuesta Json
            return $e;
        }
    }
}

?>