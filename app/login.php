<?php

// Inicia la sesión para poder almacenar datos del usuario logueado.
session_start();

// Incluye la conexión a la base de datos.
require_once '../settings/conexion.php';

// Verifica que se hayan enviado los campos usuario y contraseña.
if (isset($_POST['usuario']) && isset($_POST['clave'])) {

    // Función para limpiar los datos recibidos del formulario.
    function validate($data){

        // Elimina espacios al inicio y final.
        $data = trim($data);

        // Elimina barras invertidas.
        $data = stripslashes($data);

        // Convierte caracteres especiales en entidades HTML.
        $data = htmlspecialchars($data);

        return $data;
    }

    // Obtiene y limpia el usuario ingresado.
    $usuario = validate($_POST['usuario']);

    // Obtiene y limpia la contraseña ingresada.
    $clave = validate($_POST['clave']);

    // Verifica que el usuario no esté vacío.
    if (empty($usuario)) {

        // Redirige al login mostrando mensaje de error.
        header("Location: index.php?error=El campo usuario es obligatorio.");
        exit();
    }

    // Verifica que la contraseña no esté vacía.
    if (empty($clave)) {

        // Redirige al login mostrando mensaje de error.
        header("Location: index.php?error=El campo contraseña es obligatorio.");
        exit();
    }

    // Consulta el usuario junto con el perfil asociado.
    $query = "
    SELECT u.*, p.nombre_perfil
    FROM usuario u
    LEFT JOIN perfil p ON u.id_perfil = p.id_perfil
    WHERE u.usuario = '$usuario'
";

    // Ejecuta la consulta.
    $result = mysqli_query($conexion, $query);

    // Verifica que exista exactamente un usuario con ese nombre.
    if (mysqli_num_rows($result) == 1) {

        // Obtiene los datos del usuario encontrado.
        $row = mysqli_fetch_assoc($result);

        // Verifica que la contraseña ingresada coincida con el hash almacenado.
        if (!password_verify($clave, $row['clave'])) {

            // Si no coincide, vuelve al login con error.
            header("Location: index.php?error=Usuario o contraseña incorrectos.");
            exit();
        }

        // Segunda validación de contraseña (misma lógica del código original).
        if (!password_verify($clave, $row['clave'])) {

            // Si no coincide, vuelve al login con error.
            header("Location: index.php?error=Usuario o contraseña incorrectos.");
            exit();
        }

        // Verifica que el usuario se encuentre activo.
        if ($row['estado'] == 0) {

            // Si está inactivo, no permite iniciar sesión.
            header("Location: index.php?error=Tu cuenta se encuentra inactiva.");
            exit();
        }

        // Guarda el ID del usuario en la sesión.
        $_SESSION['id_usuario'] = $row['id_usuario'];

        // Guarda el nombre de usuario en la sesión.
        $_SESSION['usuario'] = $row['usuario'];

        // Guarda el ID del perfil en la sesión.
        $_SESSION['id_perfil'] = $row['id_perfil'];

        // Guarda el nombre del perfil en la sesión.
        $_SESSION['nombre_perfil'] = $row['nombre_perfil'];
        
        // Guarda el nombre.
        $_SESSION['nombre'] = $row['nombre'];;
    
        // Guarda el apellido
        $_SESSION['apellido'] = $row['apellido'];;

        // Redirige al panel principal del sistema.
        header("Location: inicio.php");
        exit();

    } else {

        // Si no existe el usuario, vuelve al login con error.
        header("Location: index.php?error=Usuario o contraseña incorrectos.");
        exit();
    }
}