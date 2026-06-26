<?php

require_once '../../settings/conexion.php';
require_once '../../app/validateRoute.php';
require_once 'controllers/medicalRecordController.php';

$controller = new MedicalRecordController($conexion);
$controller->index();