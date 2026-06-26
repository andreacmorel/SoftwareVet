<?php

class SystemModuleModel{
    
    private $conexion;

    public function __construct($conexion){
        
        $this->conexion = $conexion;
    }

    public function getAll($buscar = ''){

    $whereBuscar = "";

    if (!empty($buscar)) {

        $buscarSeguro = $this->conexion->real_escape_string($buscar);

        $whereBuscar = " AND (
            nombre_modulo LIKE '%$buscarSeguro%' OR
            ruta LIKE '%$buscarSeguro%'
        )";
    }

    return $this->conexion->query("SELECT id_modulo, nombre_modulo, ruta, icono, estado
        FROM modulo WHERE 1=1 $whereBuscar ORDER BY id_modulo DESC");
    }

    public function existsName($nombre_modulo){

    $nombreSeguro = $this->conexion->real_escape_string($nombre_modulo);

    $validarNombre = $this->conexion->query("
        SELECT id_modulo
        FROM modulo
        WHERE nombre_modulo = '$nombreSeguro'
        AND estado = 1
        LIMIT 1
    ");

    return $validarNombre && $validarNombre->num_rows > 0;
    }

    public function existsRoute($ruta){

    $rutaSeguro = $this->conexion->real_escape_string($ruta);

    $validarRuta = $this->conexion->query("
        SELECT id_modulo
        FROM modulo
        WHERE ruta = '$rutaSeguro'
        AND estado = 1
        LIMIT 1
    ");

    return $validarRuta && $validarRuta->num_rows > 0;
    }

    public function create($nombre_modulo, $ruta, $icono){

    $nombreSeguro = $this->conexion->real_escape_string($nombre_modulo);
    $rutaSeguro = $this->conexion->real_escape_string($ruta);
    $iconoSeguro = $this->conexion->real_escape_string($icono);

    return $this->conexion->query("
        INSERT INTO modulo (nombre_modulo, ruta, icono, estado)
        VALUES ('$nombreSeguro', '$rutaSeguro', '$iconoSeguro', 1)
    ");
    }

    public function getById($id){

    return $this->conexion->query("
        SELECT id_modulo, nombre_modulo, ruta, icono
        FROM modulo
        WHERE id_modulo = $id
    ")->fetch_object();
    }

    public function existsNameForEdit($nombre_modulo, $id){

    $nombreSeguro = $this->conexion->real_escape_string($nombre_modulo);

    $validarNombre = $this->conexion->query("
        SELECT id_modulo
        FROM modulo
        WHERE nombre_modulo = '$nombreSeguro'
        AND id_modulo != $id
        AND estado = 1
        LIMIT 1
    ");

    return $validarNombre && $validarNombre->num_rows > 0;
}

    public function existsRouteForEdit($ruta, $id){

    $rutaSeguro = $this->conexion->real_escape_string($ruta);

    $validarRuta = $this->conexion->query("
        SELECT id_modulo
        FROM modulo
        WHERE ruta = '$rutaSeguro'
        AND id_modulo != $id
        AND estado = 1
        LIMIT 1
    ");

    return $validarRuta && $validarRuta->num_rows > 0;
    }

    public function update($id, $nombre_modulo, $ruta, $icono){

    $nombreSeguro = $this->conexion->real_escape_string($nombre_modulo);
    $rutaSeguro = $this->conexion->real_escape_string($ruta);
    $iconoSeguro = $this->conexion->real_escape_string($icono);

    return $this->conexion->query("
        UPDATE modulo
        SET nombre_modulo = '$nombreSeguro',
            ruta = '$rutaSeguro',
            icono = '$iconoSeguro'
        WHERE id_modulo = $id
    ");
    }

    public function delete($id){
        
        return $this->conexion->query("
            UPDATE modulo
            SET estado = 0
            WHERE id_modulo = $id
        ");
    }
}