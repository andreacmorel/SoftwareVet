<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/ClientController.php';

$controller = new ClientController($conexion);
$controller->edit($_GET['id'] ?? 0);