-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2026 a las 06:48:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vetsys`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `id_persona`) VALUES
(1, 1),
(6, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_historia_clinica`
--

CREATE TABLE `detalle_historia_clinica` (
  `id_detalle_historia_clinica` int(11) NOT NULL,
  `id_historia_clinica` int(11) NOT NULL,
  `id_tratamiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_historia_clinica`
--

INSERT INTO `detalle_historia_clinica` (`id_detalle_historia_clinica`, `id_historia_clinica`, `id_tratamiento`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `domicilio`
--

CREATE TABLE `domicilio` (
  `id_domicilio` int(11) NOT NULL,
  `barrio` varchar(100) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero_calle` varchar(20) DEFAULT NULL,
  `manzana` varchar(50) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_profesional` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `domicilio`
--

INSERT INTO `domicilio` (`id_domicilio`, `barrio`, `calle`, `numero_calle`, `manzana`, `id_cliente`, `id_profesional`) VALUES
(2, 'ejemplo', 'ejemplo', '1234', '12', 1, NULL),
(6, 'ejemplo', 'Rivadavia', '200', '', NULL, 7),
(9, 'independencia', 'Cordoba', '557', '12', 6, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especie`
--

CREATE TABLE `especie` (
  `id_especie` int(11) NOT NULL,
  `nombre_especie` varchar(100) NOT NULL,
  `raza` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especie`
--

INSERT INTO `especie` (`id_especie`, `nombre_especie`, `raza`) VALUES
(1, 'Canino', 'Labrador'),
(2, 'Canino', 'Caniche'),
(3, 'Felino', 'Siames');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia_clinica`
--

CREATE TABLE `historia_clinica` (
  `id_historia_clinica` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_mascota` int(11) NOT NULL,
  `observacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historia_clinica`
--

INSERT INTO `historia_clinica` (`id_historia_clinica`, `fecha`, `descripcion`, `id_mascota`, `observacion`) VALUES
(1, '2026-04-30', 'control de vomitos', 1, 'se encuentra bien'),
(2, '2026-05-05', 'control', 5, 'prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascota`
--

CREATE TABLE `mascota` (
  `id_mascota` int(11) NOT NULL,
  `id_especie` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `nombre_mascota` varchar(100) NOT NULL,
  `peso` decimal(6,2) DEFAULT NULL,
  `sexo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascota`
--

INSERT INTO `mascota` (`id_mascota`, `id_especie`, `id_cliente`, `fecha_nacimiento`, `edad`, `color`, `nombre_mascota`, `peso`, `sexo`) VALUES
(1, 1, 1, '0000-00-00', 3, 'Blanco', 'Bruni', 15.00, 'M'),
(5, 3, 1, '2021-11-10', 2, 'negroo', 'colinsss', 15.00, 'H'),
(6, 2, 1, '2021-10-10', 3, 'gris', 'pequeño', 10.00, 'H'),
(7, 2, 6, '1998-09-01', 4, 'Marron', 'Chimuelo', 15.00, 'M'),
(8, 1, 6, '0000-00-00', 0, '', 'pol', 0.00, 'M'),
(9, 2, 6, '0000-00-00', 0, '', 'prueba', 10.00, 'M'),
(10, 1, 1, '0000-00-00', 0, '', 'ejemplo', 10.00, 'M'),
(11, 1, 1, '0000-00-00', 0, '', 'Polo', 10.00, 'H'),
(13, 1, 6, '2026-05-05', 1, '', 'peque', 10.00, 'M');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_modulo` int(11) NOT NULL,
  `nombre_modulo` varchar(80) NOT NULL,
  `ruta` varchar(150) NOT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id_modulo`, `nombre_modulo`, `ruta`, `icono`, `estado`) VALUES
(1, 'Mascotas', 'modulos/mascotas/listadoMascota.php', NULL, 1),
(2, 'Clientes', 'modulos/clientes/listadoCliente.php', NULL, 1),
(3, 'Turnos', 'modulos/turnos/listadoTurno.php', NULL, 1),
(4, 'Usuarios', 'modulos/usuarios/listadoUsuario.php', NULL, 1),
(5, 'Perfiles', 'modulos/perfiles/listadoPerfil.php', NULL, 1),
(6, 'Historia Clinica', 'modulos/historia_clinica/listadoHistoriaClinica.php', NULL, 1),
(7, 'Profesionales', 'modulos/profesionales/listadoProfesional.php', NULL, 1),
(8, 'Especies', 'modulos/especie/listadoEspecie.php', NULL, 1),
(9, 'Modulos', 'modulos/modulo/listadoModulos.php', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id_perfil` int(11) NOT NULL,
  `nombre_perfil` varchar(50) NOT NULL,
  `estado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nombre_perfil`, `estado`) VALUES
(2, 'Administrador', 1),
(6, 'medicamentos', 0),
(7, 'Secretaria', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_modulo`
--

CREATE TABLE `perfil_modulo` (
  `id_perfil_modulo` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `nombre_persona` varchar(100) NOT NULL,
  `apellido_persona` varchar(100) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombre_persona`, `apellido_persona`, `telefono`, `email`) VALUES
(1, 'Andrea', 'Morel', '3704010101', 'andreacmorel@gmail.com'),
(9, 'Rocio', 'Morel', '3704101020', 'rociomorel@gmail.com'),
(13, 'Gabriela ', 'Vera', '3704716209', 'gabrielavera@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesional`
--

CREATE TABLE `profesional` (
  `id_profesional` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesional`
--

INSERT INTO `profesional` (`id_profesional`, `id_persona`) VALUES
(7, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_turno`
--

CREATE TABLE `tipos_turno` (
  `id_tipo` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `duracion_minutos` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_turno`
--

INSERT INTO `tipos_turno` (`id_tipo`, `nombre`, `color`, `duracion_minutos`) VALUES
(1, 'Consulta', '#4e73df', 30),
(2, 'Vacunación', '#1cc88a', 30),
(3, 'Control', '#36b9cc', 30),
(4, 'Cirugía', '#e74a3b', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamientos`
--

CREATE TABLE `tratamientos` (
  `id_tratamiento` int(11) NOT NULL,
  `duracion` varchar(100) DEFAULT NULL,
  `dosis` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tratamientos`
--

INSERT INTO `tratamientos` (`id_tratamiento`, `duracion`, `dosis`, `descripcion`) VALUES
(1, '2 dias', NULL, 'Medicamento de perro'),
(2, '1 dia', '1 comprimido cada 6 horas', 'medicamento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id_turno` int(11) NOT NULL,
  `id_profesional` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `hora` time NOT NULL,
  `fecha` date NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'pendiente',
  `id_tipo_turno` int(11) DEFAULT NULL,
  `duracion_minutos` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id_turno`, `id_profesional`, `id_mascota`, `hora`, `fecha`, `motivo`, `estado`, `id_tipo_turno`, `duracion_minutos`) VALUES
(1, 7, 5, '20:00:00', '2026-05-04', 'consultaa', 'completado', NULL, 30),
(2, 7, 6, '20:00:00', '2026-05-05', 'pruebaa', 'completado', NULL, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `usuario`, `clave`, `reset_token`, `email`, `estado`, `id_perfil`) VALUES
(1, 'andrea', '$2y$10$9PbBCprTOoOh/FwQvEGwcO7IZprjyvflisbAF/gJGumLD/w/ChVPG', NULL, 'andreamorel@gmail.com', 1, 2),
(6, 'gabriela', '$2y$10$WsV/hSScMED7AfbAZtJwT.MUcEW0Fgb0JEuE1Ziv4UgZMU5O2VW3C', NULL, 'gabrielavera@gmail.com', 1, 7);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `detalle_historia_clinica`
--
ALTER TABLE `detalle_historia_clinica`
  ADD PRIMARY KEY (`id_detalle_historia_clinica`),
  ADD KEY `id_historia_clinica` (`id_historia_clinica`),
  ADD KEY `id_tratamiento` (`id_tratamiento`);

--
-- Indices de la tabla `domicilio`
--
ALTER TABLE `domicilio`
  ADD PRIMARY KEY (`id_domicilio`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `fk_domicilio_profesional` (`id_profesional`);

--
-- Indices de la tabla `especie`
--
ALTER TABLE `especie`
  ADD PRIMARY KEY (`id_especie`);

--
-- Indices de la tabla `historia_clinica`
--
ALTER TABLE `historia_clinica`
  ADD PRIMARY KEY (`id_historia_clinica`),
  ADD KEY `id_mascota` (`id_mascota`);

--
-- Indices de la tabla `mascota`
--
ALTER TABLE `mascota`
  ADD PRIMARY KEY (`id_mascota`),
  ADD KEY `id_especie` (`id_especie`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  ADD PRIMARY KEY (`id_perfil_modulo`),
  ADD KEY `id_perfil` (`id_perfil`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `profesional`
--
ALTER TABLE `profesional`
  ADD PRIMARY KEY (`id_profesional`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `tipos_turno`
--
ALTER TABLE `tipos_turno`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  ADD PRIMARY KEY (`id_tratamiento`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id_turno`),
  ADD KEY `id_profesional` (`id_profesional`),
  ADD KEY `id_mascota` (`id_mascota`),
  ADD KEY `fk_turnos_tipo` (`id_tipo_turno`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_historia_clinica`
--
ALTER TABLE `detalle_historia_clinica`
  MODIFY `id_detalle_historia_clinica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `domicilio`
--
ALTER TABLE `domicilio`
  MODIFY `id_domicilio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `especie`
--
ALTER TABLE `especie`
  MODIFY `id_especie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `historia_clinica`
--
ALTER TABLE `historia_clinica`
  MODIFY `id_historia_clinica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mascota`
--
ALTER TABLE `mascota`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  MODIFY `id_perfil_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `profesional`
--
ALTER TABLE `profesional`
  MODIFY `id_profesional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipos_turno`
--
ALTER TABLE `tipos_turno`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  MODIFY `id_tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`);

--
-- Filtros para la tabla `detalle_historia_clinica`
--
ALTER TABLE `detalle_historia_clinica`
  ADD CONSTRAINT `detalle_historia_clinica_ibfk_1` FOREIGN KEY (`id_historia_clinica`) REFERENCES `historia_clinica` (`id_historia_clinica`),
  ADD CONSTRAINT `detalle_historia_clinica_ibfk_2` FOREIGN KEY (`id_tratamiento`) REFERENCES `tratamientos` (`id_tratamiento`);

--
-- Filtros para la tabla `domicilio`
--
ALTER TABLE `domicilio`
  ADD CONSTRAINT `domicilio_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `fk_domicilio_profesional` FOREIGN KEY (`id_profesional`) REFERENCES `profesional` (`id_profesional`);

--
-- Filtros para la tabla `historia_clinica`
--
ALTER TABLE `historia_clinica`
  ADD CONSTRAINT `historia_clinica_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`);

--
-- Filtros para la tabla `mascota`
--
ALTER TABLE `mascota`
  ADD CONSTRAINT `mascota_ibfk_1` FOREIGN KEY (`id_especie`) REFERENCES `especie` (`id_especie`),
  ADD CONSTRAINT `mascota_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Filtros para la tabla `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  ADD CONSTRAINT `perfil_modulo_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`),
  ADD CONSTRAINT `perfil_modulo_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`);

--
-- Filtros para la tabla `profesional`
--
ALTER TABLE `profesional`
  ADD CONSTRAINT `profesional_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`);

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `fk_turnos_tipo` FOREIGN KEY (`id_tipo_turno`) REFERENCES `tipos_turno` (`id_tipo`),
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`id_profesional`) REFERENCES `profesional` (`id_profesional`),
  ADD CONSTRAINT `turnos_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;