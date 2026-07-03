<?php

require_once __DIR__ . '/../models/PetModel.php';

class PetController{

    private $model;

    public function __construct($conexion){

        $this->model = new PetModel($conexion);
    }

    public function index(){
        
    $buscar = trim($_GET['buscar'] ?? '');
    $id_especie = (int)($_GET['id_especie'] ?? 0);
    $sexo = trim($_GET['sexo'] ?? '');

    $pagina = max(1, (int)($_GET['pagina'] ?? 1));
    $porPagina = 10;
    $desde = ($pagina - 1) * $porPagina;

    $where = $this->model->buildWhere($buscar, $id_especie, $sexo);

    $total = $this->model->countAll($where);
    $totalPaginas = ceil($total / $porPagina);

    $mascotas = $this->model->getAll($where, $desde, $porPagina);
    $especies = $this->model->getSpecies();

    require_once __DIR__ . '/../views/index.php';
    }   

    public function create(){
        
    $erroresCampos = [];

    $resClientes = $this->model->getClients();
    $resEspecies = $this->model->getSpeciesForSelect();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nombre = trim($_POST['nombre_mascota'] ?? '');
        $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
        $sexo = trim($_POST['sexo'] ?? '');
        $peso = trim($_POST['peso'] ?? '');
        $color = trim($_POST['color'] ?? '');
        $edad = trim($_POST['edad'] ?? '');
        $unidad_edad = trim($_POST['unidad_edad'] ?? '');

        $id_especie = (int)($_POST['id_especie'] ?? 0);
        $id_cliente = (int)($_POST['id_cliente'] ?? 0);

        if (empty($nombre)) {
            $erroresCampos['nombre_mascota'] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 2) {
            $erroresCampos['nombre_mascota'] = "Debe tener al menos 2 caracteres.";
        }

        if (empty($sexo)) {
            $erroresCampos['sexo'] = "Seleccione el sexo.";
        }

        if (empty($peso)) {
            $erroresCampos['peso'] = "El peso es obligatorio.";
        } elseif ($peso <= 0) {
            $erroresCampos['peso'] = "El peso debe ser mayor a 0.";
        }

        if (!empty($edad) && $edad < 0) {
        $erroresCampos['edad'] = "La edad no puede ser negativa.";
        }

        $unidadesValidas = ['dias', 'meses', 'años'];

        if (empty($edad) && !empty($unidad_edad)) {
            $erroresCampos['edad'] = "Debe ingresar la edad.";
        }

        if (!empty($edad) && empty($unidad_edad)) {
            $erroresCampos['unidad_edad'] = "Seleccione la unidad de edad.";
        }

        if (!empty($unidad_edad) && !in_array($unidad_edad, $unidadesValidas)) {
            $erroresCampos['unidad_edad'] = "La unidad de edad no es válida.";
        }

        if (!empty($fecha_nacimiento) && $fecha_nacimiento > date('Y-m-d')) {
            $erroresCampos['fecha_nacimiento'] = "La fecha no puede ser futura.";
        }

        if (empty($id_especie)) {
            $erroresCampos['id_especie'] = "Seleccione una especie.";
        }

        if (empty($id_cliente)) {
            $erroresCampos['id_cliente'] = "Seleccione un cliente.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsPetForClient($nombre, $id_cliente)) {
                $erroresCampos['nombre_mascota'] = "La mascota ya existe para este cliente.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->create(
                $nombre,$fecha_nacimiento,$sexo,$peso,$color,
                $edad,$unidad_edad,$id_especie,$id_cliente
            )) {
                header("Location: index.php?success=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al guardar mascota.";
            }
        }
    }

    require_once __DIR__ . '/../views/create.php';
    }

    public function edit($id){

    $erroresCampos = [];

    $id = (int)$id;

    if ($id <= 0) {
        die("ID de mascota no válido.");
    }

    $resClientes = $this->model->getClients();
    $resEspecies = $this->model->getSpeciesForSelect();

    $mascota = $this->model->getById($id);

    if (!$mascota) {
        die("Mascota no encontrada.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nombre = trim($_POST['nombre_mascota'] ?? '');
        $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
        $sexo = trim($_POST['sexo'] ?? '');
        $peso = trim($_POST['peso'] ?? '');
        $color = trim($_POST['color'] ?? '');
        $edad = trim($_POST['edad'] ?? '');
        $unidad_edad = trim($_POST['unidad_edad'] ?? '');
        $id_especie = (int)($_POST['id_especie'] ?? 0);
        $id_cliente = (int)($_POST['id_cliente'] ?? 0);

        if (empty($nombre)) {
            $erroresCampos['nombre_mascota'] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 2) {
            $erroresCampos['nombre_mascota'] = "Debe tener al menos 2 caracteres.";
        }

        if (empty($sexo)) {
            $erroresCampos['sexo'] = "Seleccione el sexo.";
        }

        if (empty($peso)) {
            $erroresCampos['peso'] = "El peso es obligatorio.";
        } elseif ($peso <= 0) {
            $erroresCampos['peso'] = "El peso debe ser mayor a 0.";
        }

        if (!empty($edad) && $edad < 0) {
            $erroresCampos['edad'] = "La edad no puede ser negativa.";
        }

        $unidadesValidas = ['dias', 'meses', 'años'];

        if (empty($edad) && !empty($unidad_edad)) {
            $erroresCampos['edad'] = "Debe ingresar la edad.";
        }

        if (!empty($edad) && empty($unidad_edad)) {
            $erroresCampos['unidad_edad'] = "Seleccione la unidad de edad.";
        }

        if (!empty($unidad_edad) && !in_array($unidad_edad, $unidadesValidas)) {
            $erroresCampos['unidad_edad'] = "La unidad de edad no es válida.";
        }

        if (!empty($fecha_nacimiento) && $fecha_nacimiento > date('Y-m-d')) {
            $erroresCampos['fecha_nacimiento'] = "La fecha no puede ser futura.";
        }

        if (empty($id_especie)) {
            $erroresCampos['id_especie'] = "Seleccione una especie.";
        }

        if (empty($id_cliente)) {
            $erroresCampos['id_cliente'] = "Seleccione un cliente.";
        }

        if (empty($erroresCampos)) {
            if ($this->model->existsPetForClientEdit($nombre, $id_cliente, $id)) {
                $erroresCampos['nombre_mascota'] = "Ya existe otra mascota con ese nombre para este cliente.";
            }
        }

        if (empty($erroresCampos)) {
            if ($this->model->update(
                $id,$nombre,$fecha_nacimiento,$sexo,
                $peso,$color,$edad,$unidad_edad,$id_especie,$id_cliente

            )) {
                header("Location: index.php?updated=1");
                exit;
            } else {
                $erroresCampos['general'] = "Error al modificar mascota.";
            }
        }

        $mascota = $_POST;
    }

    require_once __DIR__ . '/../views/edit.php';
    }

    public function petRecord($id){

    $id = (int)$id;

    if ($id <= 0) {
        die("ID de mascota no válido.");
    }

    $mascota = $this->model->getPetRecord($id);

    if (!$mascota) {
        die("Mascota no encontrada.");
    }

    $fechaNacimiento = (!empty($mascota['fecha_nacimiento']) && $mascota['fecha_nacimiento'] != '0000-00-00')
        ? date('d/m/Y', strtotime($mascota['fecha_nacimiento']))
        : 'No registrada';

    $color = !empty($mascota['color'])
        ? htmlspecialchars($mascota['color'])
        : 'Sin especificar';

    $edad = (!empty($mascota['edad']) && $mascota['edad'] > 0)
        ? htmlspecialchars($mascota['edad']) . ' ' . htmlspecialchars($mascota['unidad_edad'] ?? '')
    : 'No registrada';

    $peso = (!empty($mascota['peso']) && $mascota['peso'] > 0)
        ? htmlspecialchars($mascota['peso']) . ' kg'
        : 'No registrado';

    $telefono = !empty($mascota['telefono'])
        ? htmlspecialchars($mascota['telefono'])
        : 'Sin teléfono';

    $email = !empty($mascota['email'])
        ? htmlspecialchars($mascota['email'])
        : 'Sin email';

    require_once __DIR__ . '/../views/pet_record.php';
    }

    public function printPetRecord($id){
    $id = (int)$id;

    if ($id <= 0) {
        die("ID de mascota no válido.");
    }

    $mascota = $this->model->getPetRecord($id);

    if (!$mascota) {
        die("Mascota no encontrada.");
    }

    require_once __DIR__ . '/../views/print_pet_record.php';
    }

    public function delete($id){

        $id = (int)$id;

        if ($id <= 0) {
            die("ID de mascota no válido.");
        }

        if (!$this->model->delete($id)) {
            die("Error al eliminar mascota.");
        }

        header("Location: index.php?delete=1");
        exit;
    }
}