<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/ProfessionalController.php';

$controller = new ProfessionalController($conexion);
$controller->delete($_GET['id'] ?? 0);