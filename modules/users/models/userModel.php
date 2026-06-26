<?php

class UserModel{

    private $conexion;

    public function __construct($conexion){

        $this->conexion = $conexion;
    }

    public function getAll($buscar = ''){

    $whereBuscar = "";

    if (!empty($buscar)) {

        $buscarSeguro = $this->conexion->real_escape_string($buscar);

        $whereBuscar = " AND (
            u.usuario LIKE '%$buscarSeguro%' OR
            u.email LIKE '%$buscarSeguro%' OR
            p.nombre_perfil LIKE '%$buscarSeguro%'
        )";
    }

    return $this->conexion->query("SELECT u.id_usuario,u.usuario,u.email,u.estado,p.nombre_perfil
        FROM usuario u
        INNER JOIN perfil p
            ON u.id_perfil = p.id_perfil
        WHERE 1=1
        $whereBuscar
        ORDER BY u.id_usuario DESC");
    }

    public function getEstado($id){

        return $this->conexion->query("
            SELECT estado 
            FROM usuario 
            WHERE id_usuario = $id
        ");
    }

    public function getById($id){

    return $this->conexion->query("
        SELECT *
        FROM usuario
        WHERE id_usuario = $id
    ")->fetch_object();
    }

    public function getProfiles(){

    return $this->conexion->query("
        SELECT id_perfil, nombre_perfil
        FROM perfil
        WHERE estado = 1
        ORDER BY nombre_perfil
    ");
    }

    public function existsForEdit($usuario, $email, $id_usuario){

    $usuarioSeguro = $this->conexion->real_escape_string($usuario);
    $emailSeguro = $this->conexion->real_escape_string($email);

    $resultado = $this->conexion->query("
        SELECT id_usuario
        FROM usuario
        WHERE (usuario = '$usuarioSeguro'
        OR email = '$emailSeguro')
        AND id_usuario != $id_usuario
    ");

    return $resultado && $resultado->num_rows > 0;
    }

    public function update($id_usuario, $usuario, $email, $id_perfil, $clave = null){

    $usuarioSeguro = $this->conexion->real_escape_string($usuario);
    $emailSeguro = $this->conexion->real_escape_string($email);

    if (!empty($clave)) {

        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

        return $this->conexion->query("
            UPDATE usuario
            SET usuario = '$usuarioSeguro',
                email = '$emailSeguro',
                clave = '$clave_hash',
                id_perfil = $id_perfil
            WHERE id_usuario = $id_usuario
        ");

    } else {

        return $this->conexion->query("
            UPDATE usuario
            SET usuario = '$usuarioSeguro',
                email = '$emailSeguro',
                id_perfil = $id_perfil
            WHERE id_usuario = $id_usuario
        ");
    }
}

public function existsUser($usuario){

    $usuarioSeguro = $this->conexion->real_escape_string($usuario);

    $resultado = $this->conexion->query("
        SELECT id_usuario
        FROM usuario
        WHERE usuario = '$usuarioSeguro'
        LIMIT 1
    ");

    return $resultado->num_rows > 0;
}

public function existsEmail($email){
    
    $emailSeguro = $this->conexion->real_escape_string($email);

    $resultado = $this->conexion->query("
        SELECT id_usuario
        FROM usuario
        WHERE email = '$emailSeguro'
        LIMIT 1
    ");

    return $resultado->num_rows > 0;
}

public function create($usuario, $email, $clave, $id_perfil){

    $usuarioSeguro = $this->conexion->real_escape_string($usuario);
    $emailSeguro = $this->conexion->real_escape_string($email);

    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

    return $this->conexion->query("
        INSERT INTO usuario
        (usuario, clave, email, estado, id_perfil)
        VALUES
        ('$usuarioSeguro', '$clave_hash', '$emailSeguro', 1, $id_perfil)
    ");
    }

    public function changeStatus($id, $nuevoEstado){
        
        return $this->conexion->query("
            UPDATE usuario 
            SET estado = $nuevoEstado 
            WHERE id_usuario = $id
        ");
    }
}