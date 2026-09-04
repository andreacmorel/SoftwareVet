<?php

class PetModel{

    private $conexion;

    public function __construct($conexion)
    {
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

    public function getAll($where, $desde, $porPagina){
        return $this->conexion->query("
            SELECT 
                m.id_mascota,
                m.nombre_mascota,
                m.sexo,
                m.peso,
                m.edad,
                m.unidad_edad,
                m.color,
                e.nombre_especie,
                e.raza,
                CONCAT(p.nombre_persona, ' ', p.apellido_persona) AS cliente
            FROM mascota m
            INNER JOIN especie e ON m.id_especie = e.id_especie
            INNER JOIN cliente c ON m.id_cliente = c.id_cliente
            INNER JOIN persona p ON c.id_persona = p.id_persona
            $where
            ORDER BY m.id_mascota DESC
            LIMIT $desde, $porPagina
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
    ");
    }

    public function getSpeciesForSelect(){

    return mysqli_query($this->conexion, "
        SELECT id_especie, nombre_especie, raza 
        FROM especie
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

    public function create($nombre, $fecha_nacimiento, $sexo, $peso, $color, $edad, $unidad_edad, $id_especie, $id_cliente){
    $nombreSeguro = $this->conexion->real_escape_string($nombre);
    $sexoSeguro = $this->conexion->real_escape_string($sexo);
    $colorSeguro = $this->conexion->real_escape_string($color);
    $unidadSeguro = $this->conexion->real_escape_string($unidad_edad);

    $fechaSQL = empty($fecha_nacimiento)
        ? "NULL"
        : "'" . $this->conexion->real_escape_string($fecha_nacimiento) . "'";

    $edadSQL = empty($edad)
        ? "NULL"
        : "'" . $this->conexion->real_escape_string($edad) . "'";

    $unidadSQL = empty($unidad_edad)
        ? "NULL"
        : "'$unidadSeguro'";

    $sqlInsert = "INSERT INTO mascota (nombre_mascota,fecha_nacimiento,sexo,
            peso,color,edad,unidad_edad,id_especie,id_cliente)
        VALUES (
            '$nombreSeguro',
            $fechaSQL,
            '$sexoSeguro',
            '$peso',
            '$colorSeguro',
            $edadSQL,
            $unidadSQL,
            $id_especie,
            $id_cliente
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
    $id_especie,$id_cliente,$id_usuario
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

    $edadSQL = empty($edad)
        ? "NULL"
        : "'" . $this->conexion->real_escape_string($edad) . "'";

    $unidadSQL = empty($unidad_edad)
        ? "NULL"
        : "'$unidadSeguro'";


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
            id_cliente = $id_cliente
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
//    public function update($id, $nombre, $fecha_nacimiento, $sexo, $peso, $color, $edad, $unidad_edad, $id_especie, $id_cliente){

//    $nombreSeguro = $this->conexion->real_escape_string($nombre);
//    $sexoSeguro = $this->conexion->real_escape_string($sexo);
//    $colorSeguro = $this->conexion->real_escape_string($color);
//    $unidadSeguro = $this->conexion->real_escape_string($unidad_edad);

//    $fechaSQL = empty($fecha_nacimiento)
//        ? "NULL"
//        : "'" . $this->conexion->real_escape_string($fecha_nacimiento) . "'";

//    $edadSQL = empty($edad)
//        ? "NULL"
//        : "'" . $this->conexion->real_escape_string($edad) . "'";

//    $unidadSQL = empty($unidad_edad)
//        ? "NULL"
//        : "'$unidadSeguro'";

//    $sqlUpdate = "UPDATE mascota SET nombre_mascota = '$nombreSeguro',
//            fecha_nacimiento = $fechaSQL,sexo = '$sexoSeguro',peso = '$peso',color = '$colorSeguro',
//            edad = $edadSQL,
//            unidad_edad = $unidadSQL,
//            id_especie = $id_especie,
//            id_cliente = $id_cliente
//        WHERE id_mascota = $id
//    ";

//    return mysqli_query($this->conexion, $sqlUpdate);
//}

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
}