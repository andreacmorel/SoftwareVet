<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/ProfileController.php';

$controller = new ProfileController($conexion);
$controller->edit($_GET['id'] ?? 0);