<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/AppointmentController.php';

$controller = new AppointmentController($conexion);
$controller->index();