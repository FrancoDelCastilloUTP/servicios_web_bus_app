<?php
/**
 * Obtiene todas las metas de la base de datos
 */

require 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $raw_data = file_get_contents("php://input");
    $data = json_decode($raw_data, true); // Decodificar JSON como un array asociativo

    
    $user_id = intval($data['user_id']);
    $card_id = intval($data['card_id']);

    $registro = User::registrarTarjeta($user_id, $card_id);

    if ($registro) {

        $datos["estado"] = 1;
        $datos["mensaje"] = "tarjeta registrada exitosamente";
        $datos["registro"] = $registro;

        print json_encode($datos);
    } else {
        print json_encode(array(
            "estado" => 2,
            "mensaje" => "Ha ocurrido un error"
        ));
    }
}