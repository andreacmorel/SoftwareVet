<?php

require_once __DIR__ . '/../../settings/conexion.php';
require_once __DIR__ . '/../../app/validateRoute.php';
require_once __DIR__ . '/controllers/petController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    http_response_code(404);
    exit('Mascota no válida.');
}

$controller = new PetController($conexion);
$controller->saveQrConsent($id);
