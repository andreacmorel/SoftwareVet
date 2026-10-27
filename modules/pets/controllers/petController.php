<?php

require_once __DIR__ . '/../models/PetModel.php';

class PetController{

    private $model;
    private $conexion; // Solo para obtener el ID luego del INSERT existente.

    public function __construct($conexion){

        $this->model = new PetModel($conexion);
        $this->conexion = $conexion;
    }

    public function index(){

        $buscar = trim($_GET['buscar'] ?? '');
        $id_especie = (int)($_GET['id_especie'] ?? 0);
        $sexo = trim($_GET['sexo'] ?? '');

        $where = $this->model->buildWhere(
            $buscar,
            $id_especie,
            $sexo
        );

        // Traemos todas las mascotas que cumplen los filtros
        $mascotas = $this->model->getAll($where);

        // Especies para el filtro
        $especies = $this->model->getSpecies();

        require_once __DIR__ . '/../views/index.php';
    }

    // Datos adicionales de identificación y autorización del QR.
    private function obtenerDatosQr(&$erroresCampos) {
        $permitidas = [
            'Ceguera', 'Sordera', 'No vocaliza / dificultad para vocalizar',
            'Movilidad reducida', 'Epilepsia', 'Requiere medicación',
            'Alergias', 'Se asusta con extraños', 'Otra'
        ];

        $condiciones = $_POST['condicion_especial'] ?? [];
        if (!is_array($condiciones)) {
            $erroresCampos['condicion_especial'] = 'Seleccioná condiciones válidas.';
            $condiciones = [];
        }
        $condiciones = array_values(array_unique($condiciones));
        if (array_diff($condiciones, $permitidas)) {
            $erroresCampos['condicion_especial'] = 'Seleccioná condiciones válidas.';
        }

        $otra = trim((string)($_POST['condicion_otra'] ?? ''));
        if (in_array('Otra', $condiciones, true)) {
            if ($otra === '') {
                $erroresCampos['condicion_otra'] = 'Especificá la otra condición.';
            } elseif (preg_match_all('/./us', $otra) > 150) {
                $erroresCampos['condicion_otra'] = 'Máximo 150 caracteres.';
            }
        } else {
            $otra = '';
        }

        $cuidados = trim((string)($_POST['cuidados_especiales'] ?? ''));
        if (preg_match_all('/./us', $cuidados) > 300) {
            $erroresCampos['cuidados_especiales'] = 'Máximo 300 caracteres.';
        }

        $publica = isset($_POST['ficha_publica']) ? 1 : 0;
        $consentimiento = $publica && isset($_POST['confirmar_consentimiento']) ? 1 : 0;
        if ($publica && !$consentimiento) {
            $erroresCampos['confirmar_consentimiento'] = 'Confirmá la autorización del propietario.';
        }

        return [
            'condicion_especial' => $condiciones ? json_encode($condiciones, JSON_UNESCAPED_UNICODE) : null,
            'condicion_otra' => $otra !== '' ? $otra : null,
            'cuidados_especiales' => $cuidados !== '' ? $cuidados : null,
            'ficha_publica' => $publica && $consentimiento ? 1 : 0,
            'consentimiento_qr' => $consentimiento,
            'mostrar_telefono' => $consentimiento && isset($_POST['mostrar_telefono']) ? 1 : 0,
            'mostrar_domicilio' => $consentimiento && isset($_POST['mostrar_domicilio']) ? 1 : 0
        ];
    }

    // prepara una foto opcional, sin modificar la lógica de la mascota.
    // Devuelve null (sin foto), una ruta relativa, o false (error).
    private function prepararFoto(&$erroresCampos) {
        if (!isset($_FILES['foto']) ||
            ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $archivo = $_FILES['foto'];
        if ($archivo['error'] !== UPLOAD_ERR_OK ||
            !isset($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            $erroresCampos['foto'] = 'No se pudo recibir la fotografía.';
            return false;
        }

        if ($archivo['size'] <= 0 || $archivo['size'] > 3 * 1024 * 1024) {
            $erroresCampos['foto'] = 'La fotografía debe pesar hasta 3 MB.';
            return false;
        }

        $tipo = (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']);
        $extensiones = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        if (!isset($extensiones[$tipo]) || @getimagesize($archivo['tmp_name']) === false) {
            $erroresCampos['foto'] = 'Seleccioná una imagen JPG, PNG o WebP válida.';
            return false;
        }

        $carpeta = __DIR__ . '/../../../uploads/pets/';
        if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true)) {
            $erroresCampos['foto'] = 'No se pudo crear la carpeta de fotografías.';
            return false;
        }

        $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extensiones[$tipo];
        if (!move_uploaded_file($archivo['tmp_name'], $carpeta . $nombreArchivo)) {
            $erroresCampos['foto'] = 'No se pudo guardar la fotografía.';
            return false;
        }

        return 'uploads/pets/' . $nombreArchivo;
    }

    // asocia la foto al registro ya guardado.
   private function asociarFoto($id, $rutaFoto) {

    // Si no seleccionó una fotografía, no hacemos cambios.
    if ($rutaFoto === null) {
        return true;
    }

    // Guardamos la ruta de la foto en MySQL.
    if ($this->model->updatePhoto($id, $rutaFoto)) {
        return true;
    }

    // Si falla el guardado en MySQL, eliminamos el archivo subido.
    @unlink(__DIR__ . '/../../../' . $rutaFoto);

    return false;
}

    // quita un archivo temporal cuando falla el guardado de mascota.
    private function descartarFoto($rutaFoto) {
        if (is_string($rutaFoto)) {
            @unlink(__DIR__ . '/../../../' . $rutaFoto);
        }
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

        $datosQr = $this->obtenerDatosQr($erroresCampos);

        if (empty($nombre)) {
            $erroresCampos['nombre_mascota'] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 2) {
            $erroresCampos['nombre_mascota'] = "Debe tener al menos 2 caracteres.";
        }

        if (empty($sexo)) {
            $erroresCampos['sexo'] = "Seleccione el sexo.";
        }

        
        // Validamos que el peso sea obligatorio,
        // numérico y mayor a cero.
        if ($peso === '') {
            $erroresCampos['peso'] = "El peso es obligatorio.";
        } elseif (!is_numeric($peso)) {
            $erroresCampos['peso'] = "El peso debe ser un número válido.";
        } elseif (!is_finite((float)$peso) || (float)$peso <= 0) {
            $erroresCampos['peso'] = "El peso debe ser mayor a 0.";
        }
        
       // Validamos que la edad sea un número entero
        // y que no pueda ser negativa.
        if ($edad !== '') {
            if (!ctype_digit($edad)) {
                $erroresCampos['edad'] = "La edad debe ser un número entero no negativo.";
            }
        }
            
        // Unidades de edad permitidas.
        $unidadesValidas = ['dias', 'meses', 'años'];

        // Si selecciona una unidad, debe ingresar una edad.
        if ($edad === '' && $unidad_edad !== '') {
            $erroresCampos['edad'] = "Debe ingresar la edad.";
        }

        // Si ingresa una edad, debe seleccionar una unidad.
        if ($edad !== '' && $unidad_edad === '') {
            $erroresCampos['unidad_edad'] = "Seleccione la unidad de edad.";
        }

        // Verificamos que la unidad seleccionada sea válida.
        if ($unidad_edad !== '' && !in_array($unidad_edad, $unidadesValidas, true)) {
            $erroresCampos['unidad_edad'] = "Unidad de edad inválida.";
        }


        
        // Validamos la fecha de nacimiento.
        if ($fecha_nacimiento !== '') {

            // Comprobamos que tenga el formato correcto
            // y que sea una fecha real del calendario.
            $fecha = DateTime::createFromFormat('!Y-m-d', $fecha_nacimiento);

            if (!$fecha || $fecha->format('Y-m-d') !== $fecha_nacimiento) {

                $erroresCampos['fecha_nacimiento'] =
                    "Ingrese una fecha de nacimiento válida.";

            } elseif ($fecha_nacimiento > date('Y-m-d')) {

                // No permitimos fechas posteriores a hoy.
                $erroresCampos['fecha_nacimiento'] =
                    "La fecha de nacimiento no puede ser futura.";
            }
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
            // la fotografía es opcional; si está presente se valida.
            $rutaFoto = $this->prepararFoto($erroresCampos);
            if (empty($erroresCampos)) {
                if ($this->model->create(
                    $nombre,$fecha_nacimiento,$sexo,$peso,$color,
                    $edad,$unidad_edad,$id_especie,$id_cliente,$datosQr
                )) {
                    // El INSERT original no cambia. Obtenemos su ID para asociar la foto.
                    $idNuevaMascota = (int)$this->conexion->insert_id;
                    $fotoGuardada = $this->asociarFoto($idNuevaMascota, $rutaFoto);
                    header("Location: index.php?success=1" . ($fotoGuardada ? '' : '&foto_error=1'));
                    exit;
                } else {
                    $this->descartarFoto($rutaFoto);
                    $erroresCampos['general'] = "Error al guardar mascota.";
                }
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

        $datosQr = $this->obtenerDatosQr($erroresCampos);

        if (empty($nombre)) {
            $erroresCampos['nombre_mascota'] = "El nombre es obligatorio.";
        } elseif (strlen($nombre) < 2) {
            $erroresCampos['nombre_mascota'] = "Debe tener al menos 2 caracteres.";
        }

        if (empty($sexo)) {
            $erroresCampos['sexo'] = "Seleccione el sexo.";
        }

        
        // Validamos que el peso sea obligatorio,
        // numérico y mayor a cero.
        if ($peso === '') {
            $erroresCampos['peso'] = "El peso es obligatorio.";
        } elseif (!is_numeric($peso)) {
            $erroresCampos['peso'] = "El peso debe ser un número válido.";
        } elseif (!is_finite((float)$peso) || (float)$peso <= 0) {
            $erroresCampos['peso'] = "El peso debe ser mayor a 0.";
        }
        // Validamos que la edad sea un número entero
        // y que no pueda ser negativa.
        if ($edad !== '') {
            if (!ctype_digit($edad)) {
                $erroresCampos['edad'] = "La edad debe ser un número entero no negativo.";
            }
        }

        // Unidades de edad permitidas.
        $unidadesValidas = ['dias', 'meses', 'años'];

        // Si selecciona una unidad, debe ingresar una edad.
        if ($edad === '' && $unidad_edad !== '') {
            $erroresCampos['edad'] = "Debe ingresar la edad.";
        }

        // Si ingresa una edad, debe seleccionar una unidad.
        if ($edad !== '' && $unidad_edad === '') {
            $erroresCampos['unidad_edad'] = "Seleccione la unidad de edad.";
        }

        // Verificamos que la unidad seleccionada sea válida.
        if ($unidad_edad !== '' && !in_array($unidad_edad, $unidadesValidas, true)) {
            $erroresCampos['unidad_edad'] = "Unidad de edad inválida.";
        }


       
    // Validamos la fecha de nacimiento.
    if ($fecha_nacimiento !== '') {

        // Comprobamos que tenga el formato correcto
        // y que sea una fecha real del calendario.
        $fecha = DateTime::createFromFormat('!Y-m-d', $fecha_nacimiento);

        if (!$fecha || $fecha->format('Y-m-d') !== $fecha_nacimiento) {

            $erroresCampos['fecha_nacimiento'] =
                "Ingrese una fecha de nacimiento válida.";

        } elseif ($fecha_nacimiento > date('Y-m-d')) {

            // No permitimos fechas posteriores a hoy.
            $erroresCampos['fecha_nacimiento'] =
                "La fecha de nacimiento no puede ser futura.";
        }
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
            // si no se elige foto, no se modifica la foto anterior.
   $rutaFoto = $this->prepararFoto($erroresCampos);

if (empty($erroresCampos)) {
    if ($this->model->update(
        $id, $nombre, $fecha_nacimiento, $sexo, $peso, $color, $edad,
        $unidad_edad, $id_especie, $id_cliente,
        $_SESSION['id_usuario'], $datosQr
    )) {

        $fotoGuardada = $this->asociarFoto($id, $rutaFoto);

        // COMPROBACIÓN TEMPORAL
        if (!$fotoGuardada) {
            die("ERROR: No se pudo asociar la fotografía en MySQL.");
        }

        header("Location: index.php?updated=1");
        exit;

    } else {
        $this->descartarFoto($rutaFoto);
        $erroresCampos['general'] = "Error al modificar mascota.";
    }
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

    // =====================================================
// FICHA PÚBLICA DE MASCOTA - CÓDIGO QR
// =====================================================

public function publicRecord($token) {

    // Validar el token recibido
    $token = trim($token);

    if ($token === '' || !preg_match('/^[a-f0-9]{64}$/', $token)) {
        http_response_code(404);
        die("Ficha pública no encontrada.");
    }

    // Obtener los datos desde el modelo
    $mascota = $this->model->getPublicPetByToken($token);

    if (!$mascota) {
        http_response_code(404);
        die("Ficha pública no disponible.");
    }

    // Preparar la dirección para Google Maps
    $direccion = implode(', ', array_filter([
        trim(($mascota['calle'] ?? '') . ' ' . ($mascota['numero_calle'] ?? '')),
        !empty($mascota['manzana'])
            ? 'Manzana ' . $mascota['manzana']
            : '',
        $mascota['barrio'] ?? '',
        'Formosa, Argentina'
    ]));

    $urlMaps = ((int)$mascota['mostrar_domicilio'] === 1)
        ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($direccion)
        : null;

    if ($urlMaps === null) {
        $direccion = null;
    }
    // Ocultar teléfono si no está autorizado
    if ((int)$mascota['mostrar_telefono'] !== 1) {
        $mascota['telefono'] = null;
    }

    // Cargar la vista pública sin menú ni sesiones
    require __DIR__ . '/../views/public_record.php';
    }

    
    // =====================================================
    // CÓDIGO QR DE MASCOTA
    // =====================================================

    public function qr($id) {

        $id = (int) $id;

        if ($id <= 0) {
            http_response_code(404);
            exit("Mascota no válida.");
        }

        // Obtener los datos de la mascota
        $mascota = $this->model->getPetRecord($id);

        if (!$mascota || (int)$mascota['activo'] !== 1) {
            http_response_code(404);
            exit("Mascota no encontrada.");
        }

        // El método debe ejecutarse desde una ruta
        // administrativa con autenticación y permisos.

        // Generar o recuperar su token público
        $token = $this->model->generatePublicToken($id);

        if (!$token) {
            http_response_code(500);
            exit("No se pudo generar el token QR.");
        }

        // URL pública de la mascota
        $urlPublica = 'http://192.168.0.30/SoftwareVet/'
            . 'modules/pets/public/pet.php?token='
            . urlencode($token);

        // Generar la imagen QR utilizando Composer
        $imagenQR = (new \chillerlan\QRCode\QRCode())
                    ->render($urlPublica);

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['csrf_qr'])) {
            $_SESSION['csrf_qr'] = bin2hex(random_bytes(32));
        }

        // Cargar vista de administración del QR
        require __DIR__ . '/../views/qr.php';
    }

    
    // =====================================================
    // GUARDAR AUTORIZACIÓN DE FICHA PÚBLICA QR
    // =====================================================

    public function saveQrConsent($id) {

        $id = (int) $id;

        if ($id <= 0) {
            http_response_code(404);
            exit("Mascota no válida.");
        }

        // Solo permitir solicitudes POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Método no permitido.");
        }

        // Verificar token de seguridad del formulario
        $csrf = $_POST['csrf_token'] ?? '';

        if (
            !isset($_SESSION['csrf_qr']) ||
            !is_string($csrf) ||
            !hash_equals($_SESSION['csrf_qr'], $csrf)
        ) {
            http_response_code(403);
            exit("Solicitud no autorizada.");
        }

        // Obtener opciones del formulario
        $fichaPublica = isset($_POST['ficha_publica']) ? 1 : 0;

        $mostrarTelefono = (
            $fichaPublica &&
            isset($_POST['mostrar_telefono'])
        ) ? 1 : 0;

        $mostrarDomicilio = (
            $fichaPublica &&
            isset($_POST['mostrar_domicilio'])
        ) ? 1 : 0;

        // Exigir consentimiento para activar la ficha
        if (
            $fichaPublica &&
            !isset($_POST['confirmar_consentimiento'])
        ) {
            http_response_code(422);
            exit("Debe confirmar la autorización del propietario.");
        }

        // Guardar mediante el modelo
        $guardado = $this->model->updateQrConsent(
            $id,
            $fichaPublica,
            $mostrarTelefono,
            $mostrarDomicilio
        );

        if (!$guardado) {
            http_response_code(500);
            exit("No se pudieron guardar los cambios.");
        }

        // Evitar reutilizar el token CSRF
        unset($_SESSION['csrf_qr']);

        // Volver a la pantalla del QR
        header("Location: qr.php?id=" . $id . "&updated=1");
        exit;
    }


}