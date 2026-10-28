<?php

class SpeciesModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getAll($buscar = '')
    {
        $where = "WHERE e.activo = 1";

        if (!empty($buscar)) {
            $buscarSeguro = $this->conexion->real_escape_string($buscar);

            $where = "WHERE e.activo = 1 AND (
                e.nombre_especie LIKE '%$buscarSeguro%' OR
                e.raza LIKE '%$buscarSeguro%'
            )";
        }

        return $this->conexion->query("
            SELECT *
            FROM especie e
            $where
            ORDER BY e.id_especie DESC
        ");
    }

    public function exists($nombre_especie, $raza)
    {
        $stmt = $this->conexion->prepare("
            SELECT id_especie 
            FROM especie 
            WHERE nombre_especie = ? 
            AND raza = ?
        ");

        $stmt->bind_param("ss", $nombre_especie, $raza);
        $stmt->execute();

        $res = $stmt->get_result();
        $existe = $res->num_rows > 0;

        $stmt->close();

        return $existe;
    }

    public function create($nombre_especie, $raza)
    {
        $stmt = $this->conexion->prepare("
            INSERT INTO especie (nombre_especie, raza) 
            VALUES (?, ?)
        ");

        $stmt->bind_param("ss", $nombre_especie, $raza);
        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    public function getById($id)
    {
        $stmt = $this->conexion->prepare("
            SELECT id_especie, nombre_especie, raza
            FROM especie
            WHERE id_especie = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();

        $stmt->close();

        return $row;
    }

    public function existsForEdit($nombre_especie, $raza, $id)
    {
        $stmt = $this->conexion->prepare("
            SELECT id_especie
            FROM especie
            WHERE nombre_especie = ?
            AND raza = ?
            AND id_especie != ?
        ");

        $stmt->bind_param("ssi", $nombre_especie, $raza, $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $existe = $resultado->num_rows > 0;

        $stmt->close();

        return $existe;
    }

    public function update($id, $nombre_especie, $raza)
    {
        $stmt = $this->conexion->prepare("
            UPDATE especie
            SET nombre_especie = ?, raza = ?
            WHERE id_especie = ?
        ");

        $stmt->bind_param("ssi", $nombre_especie, $raza, $id);
        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }

    public function delete($id)
    {
        $stmt = $this->conexion->prepare("
            UPDATE especie
            SET activo = 0
            WHERE id_especie = ?
        ");

        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();

        $stmt->close();

        return $resultado;
    }
}