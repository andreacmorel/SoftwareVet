<?php

class MedicalRecordModel{
    
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getAll($buscar, $fecha_desde, $fecha_hasta){
        
    $where = "WHERE h.activo = 1";
    $params = [];
    $types = "";

    if (!empty($buscar)) {
        $where .= " AND (
            m.nombre_mascota LIKE ? OR
            h.descripcion LIKE ? OR
            h.observacion LIKE ? OR
            h.id_historia_clinica LIKE ?
        )";

        $busqueda = "%$buscar%";

        $params[] = $busqueda;
        $params[] = $busqueda;
        $params[] = $busqueda;
        $params[] = $busqueda;

        $types .= "ssss";
    }

    if (!empty($fecha_desde)) {
        $where .= " AND h.fecha >= ?";
        $params[] = $fecha_desde;
        $types .= "s";
    }

    if (!empty($fecha_hasta)) {
        $where .= " AND h.fecha <= ?";
        $params[] = $fecha_hasta;
        $types .= "s";
    }

    $sql = "
        SELECT 
            h.id_historia_clinica,
            h.descripcion,
            h.fecha,
            h.observacion,
            m.nombre_mascota
        FROM historia_clinica h
        INNER JOIN mascota m ON h.id_mascota = m.id_mascota
        $where
        ORDER BY h.fecha DESC
    ";

    if ($params) {
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    }

    return $this->conexion->query($sql);
    }

    public function create($fecha, $descripcion, $observacion, $idMascota, $tDuraciones, $tDosis, $tDescs){
    $stmt = $this->conexion->prepare("
        INSERT INTO historia_clinica 
        (fecha, descripcion, observacion, id_mascota)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("sssi", $fecha, $descripcion, $observacion, $idMascota);
    $stmt->execute();

    $idHC = $stmt->insert_id;

    $stmt->close();

    foreach ($tDuraciones as $i => $duracion) {

        $duracion = trim($duracion);
        $dosis = trim($tDosis[$i] ?? '');
        $desc = trim($tDescs[$i] ?? '');

        if ($duracion !== '' || $dosis !== '' || $desc !== '') {

            $stmtTrat = $this->conexion->prepare("
                INSERT INTO tratamientos 
                (duracion, dosis, descripcion)
                VALUES (?, ?, ?)
            ");

            $stmtTrat->bind_param("sss", $duracion, $dosis, $desc);
            $stmtTrat->execute();

            $idTrat = $stmtTrat->insert_id;

            $stmtTrat->close();

            $stmtDet = $this->conexion->prepare("
                INSERT INTO detalle_historia_clinica 
                (id_historia_clinica, id_tratamiento)
                VALUES (?, ?)
            ");

            $stmtDet->bind_param("ii", $idHC, $idTrat);
            $stmtDet->execute();
            $stmtDet->close();
        }
    }

    return true;
    }

    public function getPets(){

    $rMas = $this->conexion->query("
        SELECT m.id_mascota, m.nombre_mascota, p.apellido_persona, p.nombre_persona
        FROM mascota m
        INNER JOIN cliente c ON m.id_cliente = c.id_cliente
        INNER JOIN persona p ON c.id_persona = p.id_persona
        ORDER BY m.nombre_mascota ASC
    ");

    $mascotas = [];

    while ($rm = $rMas->fetch_assoc()) {
        $mascotas[] = $rm;
    }

    return $mascotas;
    }

    public function getById($id){

    $stmt = $this->conexion->prepare("
        SELECT id_historia_clinica, fecha, descripcion, observacion, id_mascota
        FROM historia_clinica
        WHERE id_historia_clinica = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $historia = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    return $historia;
    }

    public function update($id, $idMascota, $fecha, $descripcion, $observacion){   
        
    $stmt = $this->conexion->prepare("
        UPDATE historia_clinica
        SET fecha = ?, descripcion = ?, observacion = ?, id_mascota = ?
        WHERE id_historia_clinica = ?
    ");

    $stmt->bind_param("sssii", $fecha, $descripcion, $observacion, $idMascota, $id);

    $resultado = $stmt->execute();

    $stmt->close();

    return $resultado;
    }

    public function delete($id){
        $sqlDelete = "
            UPDATE historia_clinica
            SET activo = 0
            WHERE id_historia_clinica = '$id'
        ";

        return mysqli_query($this->conexion, $sqlDelete);
    }
}