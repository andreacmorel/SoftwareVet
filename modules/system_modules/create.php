<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/SystemModuleController.php';

$controller = new SystemModuleController($conexion);
$controller->create();