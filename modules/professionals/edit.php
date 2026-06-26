<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/professionalController.php';

$controller = new ProfessionalController($conexion);
$controller->edit($_GET['id'] ?? 0);