<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/appointmentController.php';

$controller = new AppointmentController($conexion);
$controller->changeStatus();