<?php

require_once '../../settings/conexion.php';
require_once 'models/clientModel.php';

$model = new ClientModel($conexion);

/*Recibimos los datos enviados desde el modal*/

$nombre = trim($_POST['nombre_persona'] ?? '');
$apellido = trim($_POST['apellido_persona'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');

$calle = trim($_POST['calle'] ?? '');
$numero_calle = trim($_POST['numero_calle'] ?? '');
$barrio = trim($_POST['barrio'] ?? '');
$manzana = trim($_POST['manzana'] ?? '');


/*Validaciones*/

if (empty($nombre)) {
    echo "ERROR|El nombre es obligatorio.";
    exit;
}

if (strlen($nombre) < 3) {
    echo "ERROR|El nombre debe tener al menos 3 caracteres.";
    exit;
}


if (empty($apellido)) {
    echo "ERROR|El apellido es obligatorio.";
    exit;
}

if (strlen($apellido) < 3) {
    echo "ERROR|El apellido debe tener al menos 3 caracteres.";
    exit;
}


if (empty($telefono)) {
    echo "ERROR|El teléfono es obligatorio.";
    exit;
}

if (!preg_match('/^[0-9]+$/', $telefono)) {
    echo "ERROR|El teléfono debe contener solo números.";
    exit;
}


if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "ERROR|Ingrese un correo válido.";
    exit;
}


if (!empty($numero_calle) && !preg_match('/^[0-9]+$/', $numero_calle)) {
    echo "ERROR|El número de calle debe contener solo números.";
    exit;
}


/* Verificamos que el cliente no exista */

if ($model->existsClient($nombre, $apellido, $telefono)) {

    echo "ERROR|Este cliente ya está registrado.";
    exit;
}


/* Guardamos el cliente*/
$resultado = $model->create($nombre,$apellido,$telefono,$email,$calle,$numero_calle,$barrio,$manzana);


/* Respondemos al JavaScript*/

if (is_numeric($resultado)) {

    // Enviamos:
    // OK | ID del cliente | Apellido, Nombre

    echo "OK|" . $resultado . "|" . $apellido . ", " . $nombre;

} else {

    echo "ERROR|" . $resultado;
}

exit;