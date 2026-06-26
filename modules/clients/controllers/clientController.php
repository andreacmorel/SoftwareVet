<?php

require_once __DIR__ . '/../models/clientModel.php';

class ClientController{

    private $model;

    public function __construct($conexion){

        $this->model = new ClientModel($conexion);
    }

    public function index(){

    $buscar = trim($_GET['buscar'] ?? '');

    $clientes = $this->model->getAll($buscar);

    require_once __DIR__ . '/../views/index.php';
    }

    public function create(){

    $erroresCampos = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nombre = trim($_POST['nombre_persona']);
        $apellido = trim($_POST['apellido_persona']);
        $telefono = trim($_POST['telefono']);
        $email = trim($_POST['email']);
        $calle = trim($_POST['calle']);
        $numero_calle = trim($_POST['numero_calle']);
        $barrio = trim($_POST['barrio']);
        $manzana = trim($_POST['manzana']);

        if (empty($nombre)) {
            $erroresCampos['nombre_persona'] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 3) {
            $erroresCampos['nombre_persona'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($apellido)) {
            $erroresCampos['apellido_persona'] = "El apellido es obligatorio.";
        } elseif (strlen($apellido) < 3) {
            $erroresCampos['apellido_persona'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($telefono)) {
            $erroresCampos['telefono'] = "El teléfono es obligatorio.";
        } elseif (!preg_match('/^[0-9]+$/', $telefono)) {
            $erroresCampos['telefono'] = "Ingrese solo números.";
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroresCampos['email'] = "Ingrese un correo válido.";
        }

        if (!empty($numero_calle) && !preg_match('/^[0-9]+$/', $numero_calle)) {
            $erroresCampos['numero_calle'] = "Ingrese solo números.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsClient($nombre, $apellido, $telefono)) {
                $erroresCampos['telefono'] = "Este cliente ya está registrado.";
            }
        }

        if (empty($erroresCampos)) {
            $resultado = $this->model->create(
                $nombre,
                $apellido,
                $telefono,
                $email,
                $calle,
                $numero_calle,
                $barrio,
                $manzana
            );

            if ($resultado === true) {
                header("Location: index.php?success=1");
                exit;
            } else {
                $erroresCampos['general'] = $resultado;
            }
        }
    }

    require_once __DIR__ . '/../views/create.php';
    }

    public function edit($id){
        
    $erroresCampos = [];

    $id = (int)$id;

    if ($id <= 0) {
        die("ID de cliente no válido.");
    }

    $row = $this->model->getById($id);

    if (!$row) {
        die("Cliente no encontrado.");
    }

    $id_persona = $row['id_persona'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nombre = trim($_POST['nombre_persona']);
        $apellido = trim($_POST['apellido_persona']);
        $telefono = trim($_POST['telefono']);
        $email = trim($_POST['email']);
        $calle = trim($_POST['calle']);
        $numero_calle = trim($_POST['numero_calle']);
        $barrio = trim($_POST['barrio']);
        $manzana = trim($_POST['manzana']);

        if (empty($nombre)) {
            $erroresCampos['nombre_persona'] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 3) {
            $erroresCampos['nombre_persona'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($apellido)) {
            $erroresCampos['apellido_persona'] = "El apellido es obligatorio.";
        } elseif (strlen($apellido) < 3) {
            $erroresCampos['apellido_persona'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($telefono)) {
            $erroresCampos['telefono'] = "El teléfono es obligatorio.";
        } elseif (!preg_match('/^[0-9]+$/', $telefono)) {
            $erroresCampos['telefono'] = "Ingrese solo números.";
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroresCampos['email'] = "Ingrese un correo válido.";
        }

        if (!empty($numero_calle) && !preg_match('/^[0-9]+$/', $numero_calle)) {
            $erroresCampos['numero_calle'] = "Ingrese solo números.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsForEdit($nombre, $apellido, $telefono, $id)) {
                $erroresCampos['telefono'] = "Ya existe otro cliente con esos datos.";
            }
        }

        if (empty($erroresCampos)) {
            $resultado = $this->model->update(
                $id,
                $id_persona,
                $nombre,
                $apellido,
                $telefono,
                $email,
                $calle,
                $numero_calle,
                $barrio,
                $manzana
            );

            if ($resultado === true) {
                header("Location: index.php?updated=1");
                exit;
            } else {
                $erroresCampos['general'] = $resultado;
            }
        }

        $row = $_POST;
    }

    require_once __DIR__ . '/../views/edit.php';
    }

    public function delete($id){

        $id = (int)$id;

        if ($id <= 0) {
            die("ID de cliente no válido.");
        }

        $id_persona = $this->model->getPersonId($id);

        if (!$id_persona) {
            die("Cliente no encontrado.");
        }

        if ($this->model->hasPets($id)) {
            header("Location: index.php?error=mascotas");
            exit;
        }

        $resultado = $this->model->delete($id, $id_persona);

        if ($resultado !== true) {
            die($resultado);
        }

        header("Location: index.php?deleted=1");
        exit;
    }
}