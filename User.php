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



    public static function validarTarjeta($card_id)
    {
        $consulta = "SELECT c.*, u.* FROM cards c
                 JOIN users u ON c.card_id = u.card_id
                 WHERE c.card_id = ? AND c.registered = 1";
    
        try {
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute(array($card_id));
    
            $row = $comando->fetch(PDO::FETCH_ASSOC);
    
            return $row ; // Devuelve true si la tarjeta está registrada, false si no.
    
        } catch (PDOException $e) {
            return $e;
        }
    }


    public static function registrarTarjeta($card_id)
    {

        // Consulta de actualización
        $consulta = "SELECT * FROM cards WHERE card_id = ? AND registered = 0";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($card_id));

            // Obtener el resultado de la selección
            $row_seleccion = $comando->fetch(PDO::FETCH_ASSOC);

            // Verificar si la tarjeta existe y no está registrada
            if (!$row_seleccion) {
                // La tarjeta no existe o ya está registrada
                return $row_seleccion;
            } else {
                // Actualizar el campo registered a 1 en la tabla cards
                $actualizar_registered = "UPDATE cards SET registered = 1 WHERE card_id = ?";
                $comando_actualizar = Database::getInstance()->getDb()->prepare($actualizar_registered);
                $comando_actualizar->execute(array($card_id));

                // Consulta de inserción para crear un nuevo registro en la tabla users
                $consulta_insercion = "INSERT INTO users (card_id) VALUES (?)";

                // Preparar sentencia de inserción
                $comando_insercion = Database::getInstance()->getDb()->prepare($consulta_insercion);
                // Ejecutar sentencia de inserción
                $comando_insercion->execute(array($card_id));


                // Obtener el nuevo user_id generado automáticamente
                $nuevo_user_id = Database::getInstance()->getDb()->lastInsertId();

                // Consulta para obtener el nuevo registro creado
                $consulta_usuario = "SELECT * FROM users WHERE user_id = ?";
                $comando_usuario = Database::getInstance()->getDb()->prepare($consulta_usuario);
                $comando_usuario->execute(array($nuevo_user_id));
                $row = $comando_usuario->fetch(PDO::FETCH_ASSOC);

                // Impresión de JSON con el nuevo registro creado
                return $row;
            }

            
        
        } catch (PDOException $e) {
            // Aquí puedes clasificar el error dependiendo de la excepción
            // para presentarlo en la respuesta Json
            return $e;
        }
    }
}

?>