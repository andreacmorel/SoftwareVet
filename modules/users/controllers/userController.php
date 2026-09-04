<?php

require_once __DIR__ . '/../models/UserModel.php';

class UserController{
    
    private $model;
    private $conexion;

    public function __construct($conexion){

        $this->conexion = $conexion;
        $this->model = new UserModel($conexion);
    }

    public function index(){

    $buscar = trim($_GET['buscar'] ?? '');

    $usuarios = $this->model->getAll($buscar);

    require_once __DIR__ . '/../views/index.php';
    }

    public function create(){

    $erroresCampos = [];

    $perfiles = $this->model->getProfiles();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $usuario = trim($_POST['usuario'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $clave = $_POST['clave'] ?? '';
        $confirmar_clave = $_POST['confirmar_clave'] ?? '';
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? ''); 
        $id_perfil = (int)($_POST['id_perfil'] ?? 0);

        if (empty($nombre)) {
            $erroresCampos['nombre'] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 3) {
        $erroresCampos['nombre'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($apellido)) {
            $erroresCampos['apellido'] = "El apellido es obligatorio.";
        } elseif (strlen($apellido) < 3) {
            $erroresCampos['apellido'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($usuario)) {
            $erroresCampos['usuario'] = "El usuario es obligatorio.";
        } elseif (strlen($usuario) < 3) {
            $erroresCampos['usuario'] = "Debe tener al menos 3 caracteres.";
        } elseif (strlen($usuario) > 30) {
            $erroresCampos['usuario'] = "No puede superar los 30 caracteres.";
        } elseif (!preg_match('/^[a-zA-Z0-9._]+$/', $usuario)) {
            $erroresCampos['usuario'] = "Solo se permiten letras, números, punto y guion bajo.";
        }

        if (empty($email)) {
            $erroresCampos['email'] = "El email es obligatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroresCampos['email'] = "Ingrese un email válido.";
        }

        if (empty($clave)) {
            $erroresCampos['clave'] = "La contraseña es obligatoria.";
        } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $clave)) {
            $erroresCampos['clave'] = "Debe contener al menos 8 caracteres, una mayuscula y un número.";
        }

        if (empty($confirmar_clave)) {
            $erroresCampos['confirmar_clave'] = "Debe confirmar la contraseña.";
        } elseif ($clave !== $confirmar_clave) {
            $erroresCampos['confirmar_clave'] = "Las contraseñas no coinciden.";
        }

        if (empty($id_perfil)) {
            $erroresCampos['id_perfil'] = "Seleccione un perfil.";
        }

        if (empty($erroresCampos)) {

            if ($this->model->existsUser($usuario)) {
                $erroresCampos['usuario'] = "El nombre de usuario ya está registrado.";

            } elseif ($this->model->existsEmail($email)) {
                $erroresCampos['email'] = "El email ya está registrado.";
            }
        }

        if (empty($erroresCampos)) {

            if ($this->model->create($nombre, $apellido, $usuario, $email, $clave, $id_perfil)) {
                header("Location: index.php?success=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al registrar usuario.";
            }
        }
    }

    require_once __DIR__ . '/../views/create.php';
}

    public function edit($id){

    $erroresCampos = [];

    $id_usuario = (int)$id;

    if ($id_usuario <= 0) {
        die("ID de usuario no válido.");
    }

    $usuarioEditar = $this->model->getById($id_usuario);

    if (!$usuarioEditar) {
        die("Usuario no encontrado.");
    }

    $esMiUsuario =
        isset($_SESSION['id_usuario']) &&
        ((int)$_SESSION['id_usuario'] === (int)$id_usuario);

    if (!empty($_POST['btnActualizar'])) {

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($esMiUsuario) {
            $id_perfil = (int)$usuarioEditar->id_perfil;
        } else {
            $id_perfil = (int)($_POST['id_perfil'] ?? 0);
        }

        $clave = $_POST['clave'] ?? '';
        $confirmar_clave = $_POST['confirmar_clave'] ?? '';

        if (empty($nombre)) {
            $erroresCampos['nombre'] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 3) {
            $erroresCampos['nombre'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($apellido)) {
            $erroresCampos['apellido'] = "El apellido es obligatorio.";
        } elseif (strlen($apellido) < 3) {
            $erroresCampos['apellido'] = "Debe tener al menos 3 caracteres.";
        }

        if (empty($usuario)) {
            $erroresCampos['usuario'] = "El usuario es obligatorio.";
        } elseif (strlen($usuario) < 3) {
            $erroresCampos['usuario'] = "Debe tener al menos 3 caracteres.";
        } elseif (strlen($usuario) > 30) {
            $erroresCampos['usuario'] = "No puede superar los 30 caracteres.";
        } elseif (!preg_match('/^[a-zA-Z0-9._]+$/', $usuario)) {
            $erroresCampos['usuario'] = "Solo se permiten letras, números, punto y guion bajo.";
        }

        if (empty($email)) {
            $erroresCampos['email'] = "El email es obligatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroresCampos['email'] = "Ingrese un email válido.";
        }

        if (!$esMiUsuario && empty($id_perfil)) {
            $erroresCampos['id_perfil'] = "Seleccione un perfil.";
        }

        if (!empty($clave) || !empty($confirmar_clave)) {

        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $clave)) {
            $erroresCampos['clave'] = "Debe contener al menos 8 caracteres, una letra y un número.";
        }

        if ($clave !== $confirmar_clave) {
            $erroresCampos['confirmar_clave'] = "Las contraseñas no coinciden.";
        }
    }

        if (empty($erroresCampos)) {
            if ($this->model->existsForEdit($usuario, $email, $id_usuario)) {
                $erroresCampos['usuario'] = "El usuario o email ya se encuentra registrado.";
            }
        }

        if (empty($erroresCampos)) {
            $claveActualizar = !empty($clave) ? $clave : null;

            $this->model->update(
            $id_usuario,
            $nombre,
            $apellido,
            $usuario,
            $email,
            $id_perfil,
            $claveActualizar
        );

            header("Location: index.php?updated=1");
            exit;
        }
        
        $usuarioEditar->nombre = $nombre;
        $usuarioEditar->apellido = $apellido;
        $usuarioEditar->usuario = $usuario;
        $usuarioEditar->email = $email;
        $usuarioEditar->id_perfil = $id_perfil;
    }

        $perfiles = $this->model->getProfiles();

        require_once __DIR__ . '/../views/edit.php';
    }

    public function changeStatus($id){
        $id = (int)$id;

        if ($id <= 0) {
            header("Location: index.php");
            exit;
        }

        $usuario = $this->model->getEstado($id);

        if (!$usuario || $usuario->num_rows == 0) {
            header("Location: index.php");
            exit;
        }

        $data = $usuario->fetch_assoc();

        $nuevoEstado = ($data['estado'] == 1) ? 0 : 1;

        $this->model->changeStatus($id, $nuevoEstado);

        if ($nuevoEstado == 1) {
            header("Location: index.php?activated=1");
        } else {
            header("Location: index.php?deactivated=1");
        }

        exit;
    }
}