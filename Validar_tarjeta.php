<?php

require 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el valor del campo 'card_id' directamente
    $card_id = intval($_POST['codigo']);

    $validacion = User::validarTarjeta($card_id);

    if (is_array($validacion) && !empty($validacion)) {
        // Card is registered, return details
        $response = array(
            "estado" => 1,
            "mensaje" => "Tarjeta válida",
            "registro" => $validacion
        );
    } else {
        // Card is not registered or there was a database error
        $response = array(
            "estado" => 2,
            "mensaje" => "Tarjeta no registrada"
        );
    }

    echo json_encode($response);
}