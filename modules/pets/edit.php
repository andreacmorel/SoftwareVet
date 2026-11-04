<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/PetController.php';

$controller = new PetController($conexion);
$controller->edit($_GET['id'] ?? 0);