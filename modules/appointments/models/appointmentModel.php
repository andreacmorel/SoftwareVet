<?php

class AppointmentModel{

    private $conexion;

    public function __construct($conexion){

        $this->conexion = $conexion;
    }

    public function getById($id_turno){

    $sql = $this->conexion->query("
        SELECT 
            id_turno,
            fecha,
            hora,
            motivo,
            id_profesional,
            id_mascota,
            estado
        FROM turnos
        WHERE id_turno = $id_turno
    ");

    return $sql ? $sql->fetch_object() : null;
    }

    public function existsForEdit($fecha, $hora, $id_profesional, $id_turno){
        $validarTurno = $this->conexion->prepare("
            SELECT id_turno
            FROM turnos
            WHERE fecha = ?
            AND hora = ?
            AND id_profesional = ?
            AND id_turno != ?
            LIMIT 1
        ");

        $validarTurno->bind_param("ssii", $fecha, $hora, $id_profesional, $id_turno);
        $validarTurno->execute();

        $resTurno = $validarTurno->get_result();
        $existe = $resTurno->num_rows > 0;

        $validarTurno->close();

        return $existe;
    }

    public function update($id_turno, $fecha, $hora, $motivo, $id_profesional, $id_mascota){
        $stmt = $this->conexion->prepare("
            UPDATE turnos 
            SET fecha = ?,
                hora = ?,
                motivo = ?,
                id_profesional = ?,
                id_mascota = ?
            WHERE id_turno = ?
        ");

        $stmt->bind_param(
            "sssiii",
            $fecha,
            $hora,
            $motivo,
            $id_profesional,
            $id_mascota,
            $id_turno
        );

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    public function getProfessionals(){

        return $this->conexion->query("
            SELECT p.id_profesional, CONCAT(per.apellido_persona, ', ', per.nombre_persona) AS nombre
            FROM profesional p
            INNER JOIN persona per ON per.id_persona = p.id_persona
            ORDER BY per.apellido_persona ASC
        ");
    }

    public function getPets(){

        return $this->conexion->query("
            SELECT id_mascota, nombre_mascota 
            FROM mascota
            ORDER BY nombre_mascota ASC
        ");
    }

    public function getAll($filtro_profesional, $filtro_estado, $filtro_fecha_desde, $filtro_fecha_hasta)
{
    $estados_validos = ['pendiente', 'confirmado', 'en_atencion', 'completado', 'cancelado'];

    $where = ["t.activo = 1"];
    $params = [];
    $types = '';

    if ($filtro_profesional) {
        $where[] = "t.id_profesional = ?";
        $params[] = $filtro_profesional;
        $types .= 'i';
    }

    if ($filtro_estado && in_array($filtro_estado, $estados_validos)) {
        $where[] = "t.estado = ?";
        $params[] = $filtro_estado;
        $types .= 's';
    }

    if ($filtro_fecha_desde) {
        $where[] = "t.fecha >= ?";
        $params[] = $filtro_fecha_desde;
        $types .= 's';
    }

    if ($filtro_fecha_hasta) {
        $where[] = "t.fecha <= ?";
        $params[] = $filtro_fecha_hasta;
        $types .= 's';
    }

    $whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

    $sql = "
        SELECT 
            t.id_turno, 
            t.fecha, 
            t.hora, 
            t.motivo, 
            t.estado,
            CONCAT(per.apellido_persona, ', ', per.nombre_persona) AS profesional,
            m.nombre_mascota AS mascota,
            CONCAT(pc.apellido_persona, ', ', pc.nombre_persona) AS duenio
        FROM turnos t
        INNER JOIN profesional p ON t.id_profesional = p.id_profesional
        INNER JOIN persona per ON p.id_persona = per.id_persona
        INNER JOIN mascota m ON t.id_mascota = m.id_mascota
        INNER JOIN cliente c ON m.id_cliente = c.id_cliente
        INNER JOIN persona pc ON c.id_persona = pc.id_persona
        $whereSQL
        ORDER BY t.fecha DESC, t.hora DESC
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

    public function getProfessionalsFilter(){

    return $this->conexion->query("
        SELECT 
            p.id_profesional, 
            CONCAT(per.apellido_persona, ', ', per.nombre_persona) AS nombre
        FROM profesional p
        INNER JOIN persona per ON p.id_persona = per.id_persona
        ORDER BY per.apellido_persona
    ");
    }

    public function getProfessionalsForSelect(){

    return mysqli_query($this->conexion, "
        SELECT p.id_profesional, per.nombre_persona, per.apellido_persona
        FROM profesional p
        INNER JOIN persona per ON p.id_persona = per.id_persona
        ORDER BY per.apellido_persona ASC
    ");
    }

    public function getPetsForSelect(){

        return mysqli_query($this->conexion, "
            SELECT id_mascota, nombre_mascota
            FROM mascota
            ORDER BY nombre_mascota ASC
        ");
    }

    public function professionalExists($id_profesional){

        $validarProfesional = $this->conexion->query("
            SELECT id_profesional
            FROM profesional
            WHERE id_profesional = $id_profesional
            LIMIT 1
        ");

        return $validarProfesional && $validarProfesional->num_rows > 0;
    }

    public function petExists($id_mascota){

        $validarMascota = $this->conexion->query("
            SELECT id_mascota
            FROM mascota
            WHERE id_mascota = $id_mascota
            LIMIT 1
        ");

        return $validarMascota && $validarMascota->num_rows > 0;
    }

    public function existsAppointment($fecha, $hora, $id_profesional){

        $validarTurno = $this->conexion->prepare("
            SELECT id_turno
            FROM turnos
            WHERE fecha = ?
            AND hora = ?
            AND id_profesional = ?
            LIMIT 1
        ");

        $validarTurno->bind_param("ssi", $fecha, $hora, $id_profesional);
        $validarTurno->execute();

        $resTurno = $validarTurno->get_result();
        $existe = $resTurno->num_rows > 0;

        $validarTurno->close();

        return $existe;
    }

    public function create($fecha, $hora, $motivo, $id_profesional, $id_mascota){
        $stmt = $this->conexion->prepare("
            INSERT INTO turnos 
            (fecha, hora, motivo, id_profesional, id_mascota)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssii",
            $fecha,
            $hora,
            $motivo,
            $id_profesional,
            $id_mascota
        );

        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }
    
    public function updateStatus($id_turno, $estado){
    $stmt = $this->conexion->prepare("
        UPDATE turnos 
        SET estado = ? 
        WHERE id_turno = ? 
        AND estado NOT IN ('completado', 'cancelado')
    ");

    $stmt->bind_param("si", $estado, $id_turno);
    $resultado = $stmt->execute();

    $stmt->close();

    return $resultado;
    }

    public function getAllForExcel(){

    return $this->conexion->query("
        SELECT
            t.id_turno,
            t.fecha,
            t.hora,
            t.motivo,
            CONCAT(per.nombre_persona, ' ', per.apellido_persona) AS profesional,
            m.nombre_mascota AS mascota
        FROM turnos t
        INNER JOIN profesional p ON t.id_profesional = p.id_profesional
        INNER JOIN persona per ON p.id_persona = per.id_persona
        INNER JOIN mascota m ON t.id_mascota = m.id_mascota
        ORDER BY t.fecha DESC, t.hora DESC
    ");
    }

    public function delete($id){

        $stmt = $this->conexion->prepare("
            UPDATE turnos
            SET activo = 0
            WHERE id_turno = ?
        ");

        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }
}