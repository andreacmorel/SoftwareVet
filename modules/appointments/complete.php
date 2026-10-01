<?php

session_start();

require_once '../../settings/conexion.php';
require_once __DIR__ . '/controllers/AppointmentController.php';

$controller = new AppointmentController($conexion);

$controller->complete();