<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/UserController.php';

$controller = new UserController($conexion);
$controller->changeStatus($_GET['id'] ?? 0);