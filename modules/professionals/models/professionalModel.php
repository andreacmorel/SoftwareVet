<?php

class ProfessionalModel{
    
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function exists($id){
        $sql = "SELECT id_persona FROM profesional WHERE id_profesional = '$id'";
        $res = mysqli_query($this->conexion, $sql);

        return $res && mysqli_num_rows($res) > 0;
    }

    public function hasAppointments($id){
        $sql = "SELECT COUNT(*) AS total FROM turnos WHERE id_profesional = '$id'";
        $res = mysqli_query($this->conexion, $sql);

        if (!$res) {
            die("Error al verificar turnos: " . mysqli_error($this->conexion));
        }

        $turnos = mysqli_fetch_assoc($res);

        return $turnos['total'] > 0;
    }

    public function getAll($buscar = ''){
    $where = "WHERE c.activo = 1";

    if (!empty($buscar)) {

        $buscarSeguro = $this->conexion->real_escape_string($buscar);

        $where = "WHERE c.activo = 1 AND (
            p.nombre_persona LIKE '%$buscarSeguro%' OR
            p.apellido_persona LIKE '%$buscarSeguro%' OR
            p.telefono LIKE '%$buscarSeguro%' OR
            p.email LIKE '%$buscarSeguro%' OR
            d.barrio LIKE '%$buscarSeguro%'
        )";
    }

    return $this->conexion->query("SELECT c.id_profesional,p.nombre_persona,p.apellido_persona,p.telefono,
            p.email,d.calle,d.numero_calle,d.barrio,d.manzana
        FROM profesional c
        INNER JOIN persona p
            ON c.id_persona = p.id_persona
        LEFT JOIN domicilio d
            ON d.id_profesional = c.id_profesional
        $where ORDER BY c.id_profesional DESC");
    }

    public function existsProfessional($nombre, $apellido, $telefono)
{
    $nombreSeguro = $this->conexion->real_escape_string($nombre);
    $apellidoSeguro = $this->conexion->real_escape_string($apellido);
    $telefonoSeguro = $this->conexion->real_escape_string($telefono);

    $sqlExiste = "
        SELECT p.id_persona
        FROM persona p
        INNER JOIN profesional pr ON p.id_persona = pr.id_persona
        WHERE p.nombre_persona = '$nombreSeguro'
        AND p.apellido_persona = '$apellidoSeguro'
        AND p.telefono = '$telefonoSeguro'
    ";

    $resExiste = mysqli_query($this->conexion, $sqlExiste);

    return $resExiste && mysqli_num_rows($resExiste) > 0;
}

public function create($nombre, $apellido, $telefono, $email, $calle, $numero_calle, $barrio, $manzana){
    $nombre = $this->conexion->real_escape_string($nombre);
    $apellido = $this->conexion->real_escape_string($apellido);
    $telefono = $this->conexion->real_escape_string($telefono);
    $email = $this->conexion->real_escape_string($email);

    $calle = $this->conexion->real_escape_string($calle);
    $numero_calle = $this->conexion->real_escape_string($numero_calle);
    $barrio = $this->conexion->real_escape_string($barrio);
    $manzana = $this->conexion->real_escape_string($manzana);

    $sqlPersona = "
        INSERT INTO persona 
        (nombre_persona, apellido_persona, telefono, email)
        VALUES 
        ('$nombre', '$apellido', '$telefono', '$email')
    ";

    $resPersona = mysqli_query($this->conexion, $sqlPersona);

    if (!$resPersona) {
        return "Error al guardar persona.";
    }

    $id_persona = mysqli_insert_id($this->conexion);

    $sqlProfesional = "
        INSERT INTO profesional (id_persona)
        VALUES ('$id_persona')
    ";

    $resProfesional = mysqli_query($this->conexion, $sqlProfesional);

    if (!$resProfesional) {
        return "Error al guardar profesional.";
    }

    $id_profesional = mysqli_insert_id($this->conexion);

    $sqlDomicilio = "
        INSERT INTO domicilio 
        (calle, numero_calle, barrio, manzana, id_profesional)
        VALUES 
        ('$calle', '$numero_calle', '$barrio', '$manzana', '$id_profesional')
    ";

    if (mysqli_query($this->conexion, $sqlDomicilio)) {
        return true;
    }

    return "Error al guardar domicilio.";
    }

    public function getById($id)
{
    $sql = "
        SELECT c.id_profesional,
               c.id_persona,
               p.nombre_persona,
               p.apellido_persona,
               p.telefono,
               p.email,
               d.calle,
               d.numero_calle,
               d.barrio,
               d.manzana
        FROM profesional c
        INNER JOIN persona p ON c.id_persona = p.id_persona
        LEFT JOIN domicilio d ON d.id_profesional = c.id_profesional
        WHERE c.id_profesional = '$id'
    ";

    $res = mysqli_query($this->conexion, $sql);

    if (!$res || mysqli_num_rows($res) == 0) {
        return false;
    }

    return mysqli_fetch_assoc($res);
}

public function update($id, $nombre, $apellido, $telefono, $email, $calle, $numero_calle, $barrio, $manzana){
    
    $sqlBuscar = "SELECT id_persona FROM profesional WHERE id_profesional = '$id'";
    $resBuscar = mysqli_query($this->conexion, $sqlBuscar);

    if (!$resBuscar || mysqli_num_rows($resBuscar) == 0) {
        return "Profesional no encontrado.";
    }

    $profesional = mysqli_fetch_assoc($resBuscar);
    $id_persona = $profesional['id_persona'];

    $nombre = $this->conexion->real_escape_string($nombre);
    $apellido = $this->conexion->real_escape_string($apellido);
    $telefono = $this->conexion->real_escape_string($telefono);
    $email = $this->conexion->real_escape_string($email);
    $calle = $this->conexion->real_escape_string($calle);
    $numero_calle = $this->conexion->real_escape_string($numero_calle);
    $barrio = $this->conexion->real_escape_string($barrio);
    $manzana = $this->conexion->real_escape_string($manzana);

    $sqlPersona = "
        UPDATE persona
        SET nombre_persona = '$nombre',
            apellido_persona = '$apellido',
            telefono = '$telefono',
            email = '$email'
        WHERE id_persona = '$id_persona'
    ";

    if (!mysqli_query($this->conexion, $sqlPersona)) {
        return "Error al modificar persona.";
    }

    $sqlDomicilioExiste = "SELECT id_domicilio FROM domicilio WHERE id_profesional = '$id'";
    $resDomicilioExiste = mysqli_query($this->conexion, $sqlDomicilioExiste);

    if (!$resDomicilioExiste) {
        return "Error al buscar domicilio.";
    }

    if (mysqli_num_rows($resDomicilioExiste) > 0) {

        $domicilio = mysqli_fetch_assoc($resDomicilioExiste);
        $id_domicilio = $domicilio['id_domicilio'];

        $sqlDomicilio = "
            UPDATE domicilio
            SET calle = '$calle',
                numero_calle = '$numero_calle',
                barrio = '$barrio',
                manzana = '$manzana'
            WHERE id_domicilio = '$id_domicilio'
        ";

    } else {

        $sqlDomicilio = "
            INSERT INTO domicilio
            (calle, numero_calle, barrio, manzana, id_profesional)
            VALUES
            ('$calle', '$numero_calle', '$barrio', '$manzana', '$id')
        ";
    }

    if (!mysqli_query($this->conexion, $sqlDomicilio)) {
        return "Error al modificar domicilio.";
    }

    return true;
    }

    public function delete($id){

        $sql = "UPDATE profesional SET activo = 0 WHERE id_profesional = '$id'";
        return mysqli_query($this->conexion, $sql);
    }
}