<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/userController.php';

$controller = new UserController($conexion);
$controller->create();