<?php

// Inicia o recupera la sesión del usuario autenticado
session_start();

// Indica que la respuesta será en formato JSON
header('Content-Type: application/json');

// Devuelve el nombre y apellido almacenados en la sesión.
// Si alguno no existe, devuelve una cadena vacía.
echo json_encode([
    'nombre' => $_SESSION['nombre'] ?? '',
    'apellido' => $_SESSION['apellido'] ?? ''
]);