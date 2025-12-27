-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-12-2025 a las 21:10:47
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
-- Base de datos: `buzonvue`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` varchar(20) NOT NULL,
  `carrera` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `carrera`) VALUES
('21011009', 'Ingeniería en Sistemas Computacionales'),
('21011010', 'Ingeniería Electrónica'),
('21011011', 'Ingeniería Química');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quejas`
--

CREATE TABLE `quejas` (
  `id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `id_estudiante` varchar(20) NOT NULL,
  `carrera` varchar(100) NOT NULL,
  `estado` enum('pendiente','en_proceso','resuelta','rechazada') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `quejas`
--

INSERT INTO `quejas` (`id`, `mensaje`, `id_estudiante`, `carrera`, `estado`) VALUES
(1, 'hola, quiero pedir que limpien el salon 57', '21011009', 'Ingeniería en Sistemas Computacionales', 'en_proceso'),
(2, 'Hay murcielagos en el auditorio', '21011010', 'Ingeniería Electrónica', 'resuelta'),
(4, 'No hay agua en el CC', '21011011', 'Ingeniería Química', 'pendiente'),
(5, 'La vitrina de extraescolares está rota', '21011011', 'Ingeniería Química', 'pendiente'),
(6, 'Hay líquido derramado en laboratorios de química', '21011011', 'Ingeniería Química', 'pendiente'),
(7, 'No está abierta la oficina de SS!', '21011010', 'Ingeniería Electrónica', 'pendiente'),
(8, 'Quiero enviar una queja... ', '21011010', 'Ingeniería Electrónica', 'resuelta'),
(9, 'prueba 1', '21011009', 'Ingeniería en Sistemas Computacionales', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `primApe` varchar(50) NOT NULL,
  `segApe` varchar(50) DEFAULT NULL,
  `contra` varchar(255) NOT NULL,
  `tipo` enum('admin','estudiante') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `primApe`, `segApe`, `contra`, `tipo`) VALUES
('12345678', 'Administrador', 'Gestion', 'Proyectos', '$2y$10$azXKaIrM17N9WGbjaoqqR.rhCAjJhDZp/MiW.Y0H89iu1FUr2saSG', 'admin'),
('21011009', 'Vania', 'Muñoz', 'Hernández', '$2y$10$xqcSE1ZGMNzb/nSSDNLt4uMulPYK8sgj.akJYyG04SvRfUTx8/Odq', 'estudiante'),
('21011010', 'Gabriel', 'Flores', 'Domínguez', '$2y$10$wsmWfEM/peDX9ZhpZf16zeB7JwYQfn6dA6NLGfXHQpSdhe13.nbaO', 'estudiante'),
('21011011', 'Karyme', 'Romero', 'Ovando', '$2y$10$L.cbh.E0ccci5S9ipzu9OOlbr9TkILLPZcp8ZWdZi93qocHTfNv8a', 'estudiante');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `quejas`
--
ALTER TABLE `quejas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `quejas`
--
ALTER TABLE `quejas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `quejas`
--
ALTER TABLE `quejas`
  ADD CONSTRAINT `quejas_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
