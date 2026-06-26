<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/clientController.php';

$controller = new ClientController($conexion);
$controller->create();