<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/profileController.php';

$controller = new ProfileController($conexion);
$controller->assignModules($_GET['id'] ?? 0);