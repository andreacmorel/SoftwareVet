<?php

class ProfileModel{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function getAll($buscar = ''){
        $whereBuscar = "";

        if (!empty($buscar)) {
            $buscarSeguro = $this->conexion->real_escape_string($buscar);
            $whereBuscar = " AND nombre_perfil LIKE '%$buscarSeguro%'";
        }

        return $this->conexion->query("SELECT id_perfil, nombre_perfil
            FROM perfil WHERE estado = 1 $whereBuscar ORDER BY id_perfil DESC");
    }

    public function exists($nombre_perfil){
        $nombreSeguro = $this->conexion->real_escape_string($nombre_perfil);

        $existe = $this->conexion->query("SELECT id_perfil
            FROM perfil WHERE nombre_perfil = '$nombreSeguro' AND estado = 1 LIMIT 1");

        return $existe && $existe->num_rows > 0;
    }

    public function create($nombre_perfil){
        $nombreSeguro = $this->conexion->real_escape_string($nombre_perfil);

        return $this->conexion->query("
            INSERT INTO perfil (nombre_perfil, estado)
            VALUES ('$nombreSeguro', 1)");
    }

    public function hasActiveUsers($id){
        $sql = "SELECT COUNT(*) AS total FROM usuario WHERE id_perfil = ? AND estado = 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();

        $stmt->close();

        return $row['total'];
    }
    
    public function getById($id){

    return $this->conexion->query("
        SELECT id_perfil, nombre_perfil
        FROM perfil
        WHERE id_perfil = $id
    ")->fetch_object();
    }

    public function existsForEdit($nombre_perfil, $id){

    $nombreSeguro = $this->conexion->real_escape_string($nombre_perfil);

    $existe = $this->conexion->query("SELECT id_perfil
        FROM perfil WHERE nombre_perfil = '$nombreSeguro' AND id_perfil != $id LIMIT 1");

    return $existe && $existe->num_rows > 0;
    }

    public function update($id, $nombre_perfil){

    $nombreSeguro = $this->conexion->real_escape_string($nombre_perfil);

    return $this->conexion->query("UPDATE perfil SET nombre_perfil = '$nombreSeguro' WHERE id_perfil = $id");
    }

    public function getProfileById($id){

    return $this->conexion->query("SELECT id_perfil, nombre_perfil FROM perfil 
    WHERE id_perfil = $id")->fetch_object();
    }

    public function getModules(){

    return $this->conexion->query(" SELECT id_modulo, nombre_modulo, ruta FROM modulo WHERE estado = 1
        ORDER BY nombre_modulo");
    }

    public function getAssignedModules($id_perfil){

    $asignados = [];

    $resAsignados = $this->conexion->query("
        SELECT id_modulo
        FROM perfil_modulo
        WHERE id_perfil = $id_perfil
    ");

    while ($row = $resAsignados->fetch_object()) {
        $asignados[] = $row->id_modulo;
    }

    return $asignados;
}

    public function saveModules($id_perfil, $modulos){

    $this->conexion->query("
        DELETE FROM perfil_modulo
        WHERE id_perfil = $id_perfil
    ");

    foreach ($modulos as $id_modulo) {

        $id_modulo = (int)$id_modulo;

        $this->conexion->query("
            INSERT INTO perfil_modulo (id_perfil, id_modulo)
            VALUES ($id_perfil, $id_modulo)
        ");
    }

    return true;
    }

    public function delete($id){
        
        $stmt = $this->conexion->prepare("UPDATE perfil
            SET estado = 0 WHERE id_perfil = ?");

        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }
}