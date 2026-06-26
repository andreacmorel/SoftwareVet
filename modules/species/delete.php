<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/speciesController.php';

$controller = new SpeciesController($conexion);
$controller->delete($_GET['id'] ?? 0);