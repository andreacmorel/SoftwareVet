<?php

class PetModel{

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function buildWhere($buscar, $id_especie, $sexo){

    $where = "WHERE m.activo = 1";

    if ($buscar !== '') {
        $buscarSeguro = $this->conexion->real_escape_string($buscar);

        $where .= " AND (
            m.nombre_mascota LIKE '%$buscarSeguro%' 
            OR CONCAT(p.nombre_persona, ' ', p.apellido_persona) LIKE '%$buscarSeguro%'
        )";
    }

    if ($id_especie > 0) {
        $where .= " AND m.id_especie = $id_especie";
    }

    if ($sexo !== '') {
        $sexoSeguro = $this->conexion->real_escape_string($sexo);
        $where .= " AND m.sexo = '$sexoSeguro'";
    }

    return $where;
    }

    public function countAll($where){

        $totalQuery = $this->conexion->query("
            SELECT COUNT(*) AS total
            FROM mascota m
            INNER JOIN especie e ON m.id_especie = e.id_especie
            INNER JOIN cliente c ON m.id_cliente = c.id_cliente
            INNER JOIN persona p ON c.id_persona = p.id_persona
            $where
        ");

        return $totalQuery->fetch_object()->total;
    }

    public function getAll($where){

    return $this->conexion->query("SELECT m.id_mascota,m.nombre_mascota,m.sexo,
            m.peso,m.edad,m.unidad_edad,m.color,
            e.nombre_especie,e.raza,
            CONCAT(
                p.nombre_persona,
                ' ',
                p.apellido_persona
            ) AS cliente

        FROM mascota m INNER JOIN especie e
            ON m.id_especie = e.id_especie
        INNER JOIN cliente c
            ON m.id_cliente = c.id_cliente
        INNER JOIN persona p
            ON c.id_persona = p.id_persona

        $where

        ORDER BY m.id_mascota DESC
    ");
    }

    public function getSpecies(){

        return $this->conexion->query("
            SELECT id_especie, nombre_especie
            FROM especie
            ORDER BY nombre_especie
        ");
    }

    public function getClients(){

    return mysqli_query($this->conexion, "SELECT c.id_cliente, p.nombre_persona, p.apellido_persona
        FROM cliente c
        INNER JOIN persona p ON c.id_persona = p.id_persona
        WHERE c.activo = 1
        ORDER BY 
            p.apellido_persona ASC,
            p.nombre_persona ASC
    ");
    }

    public function getSpeciesForSelect(){

    return mysqli_query($this->conexion, "
        SELECT id_especie, nombre_especie, raza
        FROM especie
        WHERE activo = 1
        ORDER BY nombre_especie ASC, raza ASC
    ");
    }

    public function existsPetForClient($nombre, $id_cliente){

    $nombreSeguro = $this->conexion->real_escape_string($nombre);

    $sqlExiste = "SELECT id_mascota
        FROM mascota
        WHERE nombre_mascota = '$nombreSeguro'
        AND id_cliente = $id_cliente
    ";

    $resExiste = mysqli_query($this->conexion, $sqlExiste);

    return $resExiste && mysqli_num_rows($resExiste) > 0;
    }

    public function create($nombre, $fecha_nacimiento, $sexo, $peso, $color, $edad, $unidad_edad, $id_especie, $id_cliente, $datosQr = []){
    $nombreSeguro = $this->conexion->real_escape_string($nombre);
    $sexoSeguro = $this->conexion->real_escape_string($sexo);
    $colorSeguro = $this->conexion->real_escape_string($color);
    $unidadSeguro = $this->conexion->real_escape_string($unidad_edad);

    $fechaSQL = empty($fecha_nacimiento)
        ? "NULL"
        : "'" . $this->conexion->real_escape_string($fecha_nacimiento) . "'";

    
    // Si la edad está vacía, guardamos NULL.
    // Si es 0, conservamos el valor porque es una edad válida.
    $edadSQL = ($edad === '')
        ? "NULL"
        : "'" . $this->conexion->real_escape_string($edad) . "'";

    $unidadSQL = empty($unidad_edad)
        ? "NULL"
        : "'$unidadSeguro'";

    // Únicamente preparar los campos agregados para la ficha QR.
    $condicionesSQL = isset($datosQr['condicion_especial'])
        ? "'" . $this->conexion->real_escape_string($datosQr['condicion_especial']) . "'" : 'NULL';
    $otraSQL = isset($datosQr['condicion_otra'])
        ? "'" . $this->conexion->real_escape_string($datosQr['condicion_otra']) . "'" : 'NULL';
    $cuidadosSQL = isset($datosQr['cuidados_especiales'])
        ? "'" . $this->conexion->real_escape_string($datosQr['cuidados_especiales']) . "'" : 'NULL';
    $publica = (int)($datosQr['ficha_publica'] ?? 0);
    $consentimiento = (int)($datosQr['consentimiento_qr'] ?? 0);
    $telefono = (int)($datosQr['mostrar_telefono'] ?? 0);
    $domicilio = (int)($datosQr['mostrar_domicilio'] ?? 0);

    $sqlInsert = "INSERT INTO mascota (nombre_mascota,fecha_nacimiento,sexo,
            peso,color,edad,unidad_edad,id_especie,id_cliente,
            condicion_especial,condicion_otra,cuidados_especiales,
            ficha_publica,consentimiento_qr,fecha_consentimiento_qr,
            mostrar_telefono,mostrar_domicilio)
        VALUES (
            '$nombreSeguro',
            $fechaSQL,
            '$sexoSeguro',
            '$peso',
            '$colorSeguro',
            $edadSQL,
            $unidadSQL,
            $id_especie,
            $id_cliente,
            $condicionesSQL,
            $otraSQL,
            $cuidadosSQL,
            $publica,
            $consentimiento,
            CASE WHEN $consentimiento = 1 THEN NOW() ELSE NULL END,
            $telefono,
            $domicilio
        )
    ";

    return mysqli_query($this->conexion, $sqlInsert);
}
    public function getById($id){

    $sqlMascota = "SELECT * FROM mascota WHERE id_mascota = $id";
    $resMascota = mysqli_query($this->conexion, $sqlMascota);

    return mysqli_fetch_assoc($resMascota);
    }

    public function existsPetForClientEdit($nombre, $id_cliente, $id){

    $nombreSeguro = $this->conexion->real_escape_string($nombre);

    $sqlExiste = "
        SELECT id_mascota
        FROM mascota
        WHERE nombre_mascota = '$nombreSeguro'
        AND id_cliente = $id_cliente
        AND id_mascota != $id
    ";

    $resExiste = mysqli_query($this->conexion, $sqlExiste);

    return $resExiste && mysqli_num_rows($resExiste) > 0;
    }

    public function update($id,$nombre,$fecha_nacimiento,$sexo,$peso,$color,$edad,$unidad_edad,
    $id_especie,$id_cliente,$id_usuario,$datosQr = []
    ){

    //Obtenemos como estaba la mascota ANTES de modificarla
    $sqlAntes = "SELECT * FROM mascota WHERE id_mascota = $id";

    $resultadoAntes = mysqli_query($this->conexion, $sqlAntes);
    // aca se ejecuta la consulta anterior y el resultado queda guardado en $resultadoAntes

    $datosAntes = mysqli_fetch_assoc($resultadoAntes);

    // Guarda los datos anteriores como texto
    $textoAntes =
        "Nombre: " . $datosAntes['nombre_mascota'] .
        " | Fecha nacimiento: " . $datosAntes['fecha_nacimiento'] .
        " | Sexo: " . $datosAntes['sexo'] .
        " | Peso: " . $datosAntes['peso'] .
        " | Color: " . $datosAntes['color'] .
        " | Edad: " . $datosAntes['edad'] .
        " | Unidad edad: " . $datosAntes['unidad_edad'] .
        " | Especie: " . $datosAntes['id_especie'] .
        " | Cliente: " . $datosAntes['id_cliente'];


    // Prepara los datos para modificar
    $nombreSeguro = $this->conexion->real_escape_string($nombre);
    $sexoSeguro = $this->conexion->real_escape_string($sexo);
    $colorSeguro = $this->conexion->real_escape_string($color);
    $unidadSeguro = $this->conexion->real_escape_string($unidad_edad);

    $fechaSQL = empty($fecha_nacimiento)
        ? "NULL"
        : "'" . $this->conexion->real_escape_string($fecha_nacimiento) . "'";

    
    // Si no se ingresó edad, guardamos NULL.
    // Si se ingresó 0, lo conservamos porque es válido.
    $edadSQL = ($edad === '')
        ? "NULL"
        : "'" . $this->conexion->real_escape_string($edad) . "'";


    $unidadSQL = empty($unidad_edad)
        ? "NULL"
        : "'$unidadSeguro'";


    // Únicamente preparar los campos agregados para la ficha QR.
    $condicionesSQL = isset($datosQr['condicion_especial'])
        ? "'" . $this->conexion->real_escape_string($datosQr['condicion_especial']) . "'" : 'NULL';
    $otraSQL = isset($datosQr['condicion_otra'])
        ? "'" . $this->conexion->real_escape_string($datosQr['condicion_otra']) . "'" : 'NULL';
    $cuidadosSQL = isset($datosQr['cuidados_especiales'])
        ? "'" . $this->conexion->real_escape_string($datosQr['cuidados_especiales']) . "'" : 'NULL';
    $publica = (int)($datosQr['ficha_publica'] ?? 0);
    $consentimiento = (int)($datosQr['consentimiento_qr'] ?? 0);
    $telefono = (int)($datosQr['mostrar_telefono'] ?? 0);
    $domicilio = (int)($datosQr['mostrar_domicilio'] ?? 0);

    // Hacer el UPDATE normal
    $sqlUpdate = "UPDATE mascota SET 
            nombre_mascota = '$nombreSeguro',
            fecha_nacimiento = $fechaSQL,
            sexo = '$sexoSeguro',
            peso = '$peso',
            color = '$colorSeguro',
            edad = $edadSQL,
            unidad_edad = $unidadSQL,
            id_especie = $id_especie,
            id_cliente = $id_cliente,
            condicion_especial = $condicionesSQL,
            condicion_otra = $otraSQL,
            cuidados_especiales = $cuidadosSQL,
            ficha_publica = $publica,
            consentimiento_qr = $consentimiento,
            fecha_consentimiento_qr = CASE WHEN $consentimiento = 1
                THEN COALESCE(fecha_consentimiento_qr, NOW()) ELSE NULL END,
            mostrar_telefono = $telefono,
            mostrar_domicilio = $domicilio
        WHERE id_mascota = $id
    ";

    $resultadoUpdate = mysqli_query($this->conexion, $sqlUpdate);


    // Si la modificación salió bien
    if ($resultadoUpdate) {

        // Obtener cómo quedó DESPUÉS
        $sqlDespues = "SELECT * FROM mascota WHERE id_mascota = $id";
        $resultadoDespues = mysqli_query($this->conexion, $sqlDespues);
        $datosDespues = mysqli_fetch_assoc($resultadoDespues);

        // Guardar los datos nuevos como texto
        $textoDespues =
            "Nombre: " . $datosDespues['nombre_mascota'] .
            " | Fecha nacimiento: " . $datosDespues['fecha_nacimiento'] .
            " | Sexo: " . $datosDespues['sexo'] .
            " | Peso: " . $datosDespues['peso'] .
            " | Color: " . $datosDespues['color'] .
            " | Edad: " . $datosDespues['edad'] .
            " | Unidad edad: " . $datosDespues['unidad_edad'] .
            " | Especie: " . $datosDespues['id_especie'] .
            " | Cliente: " . $datosDespues['id_cliente'];

        // Preparar los textos para guardarlos
        $antesSeguro = $this->conexion->real_escape_string($textoAntes);
        $despuesSeguro = $this->conexion->real_escape_string($textoDespues);

        // Registrar el cambio en auditoria
        $sqlAuditoria = "INSERT INTO auditoria
            (id_usuario, modulo, accion, id_registro, datos_anteriores, datos_nuevos)
            VALUES
            ($id_usuario, 'Mascotas', 'Modificación', $id, '$antesSeguro', '$despuesSeguro')
        ";

        mysqli_query($this->conexion, $sqlAuditoria);
    }

    return $resultadoUpdate;
}

    public function getPetRecord($id){
    $sql = "SELECT 
            m.*,
            e.nombre_especie,
            e.raza,
            p.nombre_persona,
            p.apellido_persona,
            p.telefono,
            p.email
        FROM mascota m
        INNER JOIN cliente c ON m.id_cliente = c.id_cliente
        INNER JOIN persona p ON c.id_persona = p.id_persona
        INNER JOIN especie e ON m.id_especie = e.id_especie
        WHERE m.id_mascota = $id
    ";

    $result = mysqli_query($this->conexion, $sql);

    return mysqli_fetch_assoc($result);
    }

    public function delete($id){
        
        $sqlDelete = "UPDATE mascota SET activo = 0 WHERE id_mascota = '$id'";

        return mysqli_query($this->conexion, $sqlDelete);
    }

    // =====================================================
    // OBTENER FICHA PÚBLICA DE MASCOTA POR TOKEN QR
    // =====================================================

    public function getPublicPetByToken($token) {

        $sql = "SELECT m.id_mascota,m.nombre_mascota,m.sexo,m.color,m.edad,m.unidad_edad,m.token_publico, m.foto,
            m.mostrar_telefono,m.mostrar_domicilio,
            m.condicion_especial,m.condicion_otra,m.cuidados_especiales,
            e.nombre_especie,e.raza,
            p.nombre_persona,p.apellido_persona,p.telefono,
            d.barrio,d.calle,d.numero_calle,d.manzana
        FROM mascota m
        INNER JOIN especie e ON m.id_especie = e.id_especie
        INNER JOIN cliente c ON m.id_cliente = c.id_cliente
        INNER JOIN persona p ON c.id_persona = p.id_persona
        LEFT JOIN domicilio d ON c.id_cliente = d.id_cliente AND d.activo = 1
        WHERE m.token_publico = ?
            AND m.activo = 1
            AND c.activo = 1
            AND m.ficha_publica = 1
            AND m.consentimiento_qr = 1 LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $mascota = $resultado->fetch_assoc();
        $stmt->close();
        return $mascota ?: null;

        }

        // =====================================================
        // GENERAR TOKEN PÚBLICO PARA EL QR
        // =====================================================

        public function generatePublicToken($id) {

            $id = (int) $id;

            if ($id <= 0) {
                return false;
            }

            // Buscar mascota activa
            $sql = "SELECT token_publico FROM mascota
                    WHERE id_mascota = ? AND activo = 1";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $mascota = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$mascota) {
                return false;
            }

            // Si ya tiene token, reutilizarlo
            if (!empty($mascota['token_publico'])) {
                return $mascota['token_publico'];
            }

            // Generar token aleatorio seguro
            $token = bin2hex(random_bytes(32));

            // Guardarlo en la base de datos
            $sql = "UPDATE mascota
                    SET token_publico = ?
                    WHERE id_mascota = ?
                    AND activo = 1
                    AND token_publico IS NULL";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("si", $token, $id);

            $guardado = $stmt->execute();
            $stmt->close();

            return $guardado ? $token : false;
        }

        
    // =====================================================
    // GUARDAR AUTORIZACIÓN PARA LA FICHA PÚBLICA QR
    // =====================================================

    public function updateQrConsent($id,$fichaPublica,$mostrarTelefono,$mostrarDomicilio) {
       
        $sql = "UPDATE mascota
                SET ficha_publica = ?,consentimiento_qr = ?,fecha_consentimiento_qr =
                        CASE WHEN ? = 1 THEN NOW() ELSE NULL END,
                    mostrar_telefono = ?,
                    mostrar_domicilio = ?
                WHERE id_mascota = ?
                AND activo = 1";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param("iiiiii",
            $fichaPublica,$fichaPublica,$fichaPublica,
            $mostrarTelefono,$mostrarDomicilio,
            $id
        );

        $resultado = $stmt->execute();
        $filas = $stmt->affected_rows;
        $stmt->close();

        return $resultado && $filas > 0;
    }

    public function updatePhoto($id, $foto) {
    $sql = "UPDATE mascota
            SET foto = ?
            WHERE id_mascota = ?
            AND activo = 1";

    $stmt = $this->conexion->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $id = (int) $id;
    $stmt->bind_param("si", $foto, $id);

    $resultado = $stmt->execute();
    $stmt->close();

    return $resultado;
}

}