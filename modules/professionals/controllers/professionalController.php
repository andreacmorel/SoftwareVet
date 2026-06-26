<?php

require_once __DIR__ . '/../models/professionalModel.php';

class ProfessionalController{

    private $model;

    public function __construct($conexion){
        $this->model = new ProfessionalModel($conexion);
    }

    public function delete($id){
        $id = (int)$id;

        if ($id <= 0) {
            die("ID de profesional no válido.");
        }

        if (!$this->model->exists($id)) {
            die("Profesional no encontrado.");
        }

        if ($this->model->hasAppointments($id)) {
            header("Location: index.php?error=turnos");
            exit;
        }

        if (!$this->model->delete($id)) {
            die("Error al eliminar profesional.");
        }

        header("Location: index.php?deleted=1");
        exit;
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

        if (empty($email)) {
            $erroresCampos['email'] = "El correo es obligatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroresCampos['email'] = "Ingrese un correo válido.";
        }

        if (empty($calle)) {
            $erroresCampos['calle'] = "La calle es obligatoria.";
        }

        if (empty($numero_calle)) {
            $erroresCampos['numero_calle'] = "El número es obligatorio.";
        } elseif (!preg_match('/^[0-9]+$/', $numero_calle)) {
            $erroresCampos['numero_calle'] = "Ingrese solo números.";
        }

        if (empty($barrio)) {
            $erroresCampos['barrio'] = "El barrio es obligatorio.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsProfessional($nombre, $apellido, $telefono)) {
                $erroresCampos['telefono'] = "Este profesional ya está registrado.";
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
        die("ID de profesional no válido.");
    }

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

        if (empty($email)) {
            $erroresCampos['email'] = "El correo es obligatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroresCampos['email'] = "Ingrese un correo válido.";
        }

        if (empty($calle)) {
            $erroresCampos['calle'] = "La calle es obligatoria.";
        }

        if (empty($numero_calle)) {
            $erroresCampos['numero_calle'] = "El número es obligatorio.";
        } elseif (!preg_match('/^[0-9]+$/', $numero_calle)) {
            $erroresCampos['numero_calle'] = "Ingrese solo números.";
        }

        if (empty($barrio)) {
            $erroresCampos['barrio'] = "El barrio es obligatorio.";
        }

        if (empty($erroresCampos)) {
            $resultado = $this->model->update(
                $id,
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

        $row = [
            'nombre_persona'   => $_POST['nombre_persona'],
            'apellido_persona' => $_POST['apellido_persona'],
            'telefono'         => $_POST['telefono'],
            'email'            => $_POST['email'],
            'calle'            => $_POST['calle'],
            'numero_calle'     => $_POST['numero_calle'],
            'barrio'           => $_POST['barrio'],
            'manzana'          => $_POST['manzana'],
        ];

    } else {

        $row = $this->model->getById($id);

        if (!$row) {
            die("Profesional no encontrado.");
        }
    }

    require_once __DIR__ . '/../views/edit.php';
    }

    public function index(){

    $buscar = trim($_GET['buscar'] ?? '');

    $profesionales = $this->model->getAll($buscar);

    require_once __DIR__.'/../views/index.php';
}
}