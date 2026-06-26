<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/petController.php';

$controller = new PetController($conexion);
$controller->printPetRecord($_GET['id'] ?? 0);