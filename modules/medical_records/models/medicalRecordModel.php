<?php

class MedicalRecordModel{
    
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

  public function getAll($buscar = ''){

    $where = "WHERE m.activo = 1";

    $params = [];
    $types = "";


    /* =====================================================
       BUSCADOR
    ===================================================== */

    if (!empty($buscar)) {

        $where .= " AND (
            m.nombre_mascota LIKE ?
            OR p.nombre_persona LIKE ?
            OR p.apellido_persona LIKE ?
            OR e.nombre_especie LIKE ?
            OR e.raza LIKE ?
        )";

        $busqueda = "%$buscar%";

        $params[] = $busqueda;
        $params[] = $busqueda;
        $params[] = $busqueda;
        $params[] = $busqueda;
        $params[] = $busqueda;

        $types = "sssss";
    }


    /* =====================================================
       LISTADO DE MASCOTAS
       UNA SOLA FILA POR MASCOTA
    ===================================================== */

    $sql = "
        SELECT
            m.id_mascota,
            m.nombre_mascota,

            e.nombre_especie,
            e.raza,

            CONCAT(
                p.nombre_persona,
                ' ',
                p.apellido_persona
            ) AS propietario

        FROM mascota m

        INNER JOIN cliente c
            ON m.id_cliente = c.id_cliente

        INNER JOIN persona p
            ON c.id_persona = p.id_persona

        INNER JOIN especie e
            ON m.id_especie = e.id_especie

        $where

        ORDER BY m.nombre_mascota ASC
    ";


    /* =====================================================
       EJECUTAR
    ===================================================== */

    if (!empty($params)) {

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            $types,
            ...$params
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }


    return $this->conexion->query($sql);
}

    public function create($fecha, $descripcion, $observacion, $idMascota, $tDuraciones, $tDosis, $tDescs){
    // Inicia la transacción
    $this->conexion->begin_transaction();

    try {

        $stmt = $this->conexion->prepare("
            INSERT INTO historia_clinica
            (fecha, descripcion, observacion, id_mascota)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param("sssi", $fecha, $descripcion, $observacion, $idMascota);

        if (!$stmt->execute()) {
            throw new Exception("Error al registrar la historia clínica.");
            //Si algún INSERT falla, se lanza una excepción y se deja de ejecutar el resto del código.
        }

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

                if (!$stmtTrat->execute()) {
                    throw new Exception("Error al registrar el tratamiento.");
                }

                $idTrat = $stmtTrat->insert_id;
                $stmtTrat->close();

                $stmtDet = $this->conexion->prepare("
                    INSERT INTO detalle_historia_clinica
                    (id_historia_clinica, id_tratamiento)
                    VALUES (?, ?)
                ");

                $stmtDet->bind_param("ii", $idHC, $idTrat);

                if (!$stmtDet->execute()) {
                    throw new Exception("Error al registrar el detalle.");
                }

                $stmtDet->close();
            }
        }

        // Confirma todos los cambios
        $this->conexion->commit();

        return true;

    } catch (Exception $e) {

        // Si algo falló, deshace todos los INSERT realizados desde que comenzó la transacción.
        $this->conexion->rollback();

        return false;
    }
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


public function getPetClinicalData($id_mascota){

    $id_mascota = (int)$id_mascota;

    $stmt = $this->conexion->prepare("
        SELECT
            m.id_mascota,
            m.nombre_mascota,
            m.fecha_nacimiento,
            m.edad,
            m.unidad_edad,
            m.color,
            m.peso,
            m.sexo,

            e.nombre_especie,
            e.raza,

            p.nombre_persona,
            p.apellido_persona

        FROM mascota m

        INNER JOIN cliente c
            ON m.id_cliente = c.id_cliente

        INNER JOIN persona p
            ON c.id_persona = p.id_persona

        INNER JOIN especie e
            ON m.id_especie = e.id_especie

        WHERE m.id_mascota = ?

        LIMIT 1
    ");

    $stmt->bind_param("i", $id_mascota);

    $stmt->execute();

    $resultado = $stmt->get_result();

    $mascota = $resultado->fetch_assoc();

    $stmt->close();

    return $mascota;
}

public function getPetAppointments($id_mascota){

    $id_mascota = (int)$id_mascota;

    $stmt = $this->conexion->prepare("
        SELECT
            t.id_turno,
            t.fecha,
            t.hora,
            t.motivo,
            t.detalle_atencion,
            t.tratamiento,
            t.monto_total,

            CONCAT(
                p.nombre_persona,
                ' ',
                p.apellido_persona
            ) AS profesional

        FROM turnos t

        INNER JOIN profesional pr
            ON t.id_profesional = pr.id_profesional

        INNER JOIN persona p
            ON pr.id_persona = p.id_persona

        WHERE t.id_mascota = ?

        AND t.estado = 'completado'

        ORDER BY
            t.fecha DESC,
            t.hora DESC
    ");

    $stmt->bind_param(
        "i",
        $id_mascota
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    $turnos = [];

    while ($fila = $resultado->fetch_assoc()) {

        $turnos[] = $fila;

    }

    $stmt->close();

    return $turnos;
    }
}