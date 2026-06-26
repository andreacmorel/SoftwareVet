<?php

class ClientModel{

    private $conexion;

    public function __construct($conexion){
        
        $this->conexion = $conexion;
    }

    public function getPersonId($id){

        $sqlBuscar = "SELECT id_persona FROM cliente WHERE id_cliente = '$id'";
        $resBuscar = mysqli_query($this->conexion, $sqlBuscar);

        if (!$resBuscar || mysqli_num_rows($resBuscar) == 0) {
            return false;
        }

        $data = mysqli_fetch_assoc($resBuscar);

        return $data['id_persona'];
    }

    public function hasPets($id){

        $sqlMascotas = "SELECT COUNT(*) AS total FROM mascota WHERE id_cliente = '$id'";
        $resMascotas = mysqli_query($this->conexion, $sqlMascotas);
        $mascotas = mysqli_fetch_assoc($resMascotas);

        return $mascotas['total'] > 0;
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

    return $this->conexion->query("
        SELECT c.id_cliente,p.nombre_persona,p.apellido_persona,p.telefono,p.email,
            d.calle,d.numero_calle,d.barrio,d.manzana
        FROM cliente c
        INNER JOIN persona p ON c.id_persona = p.id_persona
        LEFT JOIN domicilio d ON d.id_cliente = c.id_cliente
        $where
        ORDER BY c.id_cliente DESC
    ");
    }

    public function getById($id){

    $sql = "
        SELECT c.id_cliente,c.id_persona,p.nombre_persona,p.apellido_persona,p.telefono,p.email,
            d.calle,d.numero_calle,d.barrio,d.manzana
        FROM cliente c
        INNER JOIN persona p ON c.id_persona = p.id_persona
        LEFT JOIN domicilio d ON d.id_cliente = c.id_cliente
        WHERE c.id_cliente = $id
    ";

    $res = mysqli_query($this->conexion, $sql);

    if (!$res || mysqli_num_rows($res) == 0) {
        return false;
    }

    return mysqli_fetch_assoc($res);
    }

    public function existsClient($nombre, $apellido, $telefono){

        $nombreSeguro = $this->conexion->real_escape_string($nombre);
        $apellidoSeguro = $this->conexion->real_escape_string($apellido);
        $telefonoSeguro = $this->conexion->real_escape_string($telefono);

        $sqlExiste = "
            SELECT id_persona
            FROM persona
            WHERE nombre_persona = '$nombreSeguro'
            AND apellido_persona = '$apellidoSeguro'
            AND telefono = '$telefonoSeguro'
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

        $sqlCliente = "
            INSERT INTO cliente (id_persona)
            VALUES ('$id_persona')
        ";

        $resCliente = mysqli_query($this->conexion, $sqlCliente);

        if (!$resCliente) {
            return "Error al guardar cliente.";
        }

        $id_cliente = mysqli_insert_id($this->conexion);

        if (!empty($calle) || !empty($numero_calle) || !empty($barrio) || !empty($manzana)) {

            $sqlDomicilio = "
                INSERT INTO domicilio
                (calle, numero_calle, barrio, manzana, id_cliente)
                VALUES
                ('$calle', '$numero_calle', '$barrio', '$manzana', '$id_cliente')
            ";

            mysqli_query($this->conexion, $sqlDomicilio);
        }

        return true;
    }

    public function existsForEdit($nombre, $apellido, $telefono, $id){

        $nombreSeguro = $this->conexion->real_escape_string($nombre);
        $apellidoSeguro = $this->conexion->real_escape_string($apellido);
        $telefonoSeguro = $this->conexion->real_escape_string($telefono);

        $sqlExiste = "
            SELECT p.id_persona
            FROM persona p
            INNER JOIN cliente c ON p.id_persona = c.id_persona
            WHERE p.nombre_persona = '$nombreSeguro'
            AND p.apellido_persona = '$apellidoSeguro'
            AND p.telefono = '$telefonoSeguro'
            AND c.id_cliente != $id
        ";

        $resExiste = mysqli_query($this->conexion, $sqlExiste);

        return $resExiste && mysqli_num_rows($resExiste) > 0;
    }

    public function update($id, $id_persona, $nombre, $apellido, $telefono, $email, $calle, $numero_calle, $barrio, $manzana){

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

    $sqlDomicilioExiste = "
        SELECT id_domicilio
        FROM domicilio
        WHERE id_cliente = '$id'
    ";

    $resDomicilioExiste = mysqli_query($this->conexion, $sqlDomicilioExiste);

    if ($resDomicilioExiste && mysqli_num_rows($resDomicilioExiste) > 0) {

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
            (calle, numero_calle, barrio, manzana, id_cliente)
            VALUES
            ('$calle', '$numero_calle', '$barrio', '$manzana', '$id')
        ";
    }

    if (!mysqli_query($this->conexion, $sqlDomicilio)) {
        return "Error al modificar domicilio.";
    }

    return true;
    }

    public function delete($id, $id_persona){

        mysqli_query($this->conexion, "UPDATE domicilio SET activo = 0 WHERE id_cliente = '$id'");

        if (!mysqli_query($this->conexion, "UPDATE cliente SET activo = 0 WHERE id_cliente = '$id'")) {
            return "Error al dar de baja cliente: " . mysqli_error($this->conexion);
        }

        if (!mysqli_query($this->conexion, "UPDATE persona SET activo = 0 WHERE id_persona = '$id_persona'")) {
            return "Error al dar de baja persona: " . mysqli_error($this->conexion);
        }

        return true;
    }
}