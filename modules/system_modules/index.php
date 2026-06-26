<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/systemModuleController.php';

$controller = new SystemModuleController($conexion);
$controller->index();