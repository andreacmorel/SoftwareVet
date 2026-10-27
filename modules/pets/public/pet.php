<?php

require_once __DIR__ . '/../../../settings/conexion.php';
require_once __DIR__ . '/../controllers/PetController.php';

$token = trim($_GET['token'] ?? '');

if ($token === '') {
    http_response_code(404);
    exit('Ficha pública no encontrada.');
}

$controller = new PetController($conexion);

$controller->publicRecord($token);