<?php
/**
 * Obtiene todas las metas de la base de datos
 */

require 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// Obtener el valor del campo 'codigo' directamente
$card_id = intval($_POST['codigo']);

    $registro = User::registrarTarjeta($card_id);
    
    if ($registro) {
        $response = array(
            "estado" => 1,
            "mensaje" => "tarjeta registrada exitosamente",
            "registro" => $registro
        );
    } else {
        $response = array(
            "estado" => 2,
            "mensaje" => "Ha ocurrido un error"
        );
    }

    echo json_encode($response);
}