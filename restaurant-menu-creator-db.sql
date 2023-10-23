-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 23-10-2023 a las 17:39:42
-- Versión del servidor: 8.0.27
-- Versión de PHP: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurant-menu-creator-db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_local`
--

DROP TABLE IF EXISTS `horario_local`;
CREATE TABLE IF NOT EXISTS `horario_local` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dia_semana` varchar(255) NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  `informacion_id` int NOT NULL,
  `no_mostrar_hora` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_EE6704F97DD608D1` (`informacion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion`
--

DROP TABLE IF EXISTS `informacion`;
CREATE TABLE IF NOT EXISTS `informacion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `telefono` int NOT NULL,
  `descripcion` longtext NOT NULL,
  `direccion_completa` varchar(255) NOT NULL,
  `localidad` varchar(255) NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `twiter` varchar(255) NOT NULL,
  `youtube` varchar(255) NOT NULL,
  `maps` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `local_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_EAADE3A5C4C64DCC` (`local_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `informacion`
--

INSERT INTO `informacion` (`id`, `telefono`, `descripcion`, `direccion_completa`, `localidad`, `ciudad`, `email`, `facebook`, `instagram`, `twiter`, `youtube`, `maps`, `local_id`) VALUES
(1, 5553434, 'dasdasd', '', 'sdasd', 'dasdas', '3@3.com', '', '', '', '', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_photo`
--

DROP TABLE IF EXISTS `informacion_photo`;
CREATE TABLE IF NOT EXISTS `informacion_photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `informacion_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_53902D7A7DD608D1` (`informacion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local`
--

DROP TABLE IF EXISTS `local`;
CREATE TABLE IF NOT EXISTS `local` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_local` varchar(255) NOT NULL,
  `descripcion_local` varchar(255) NOT NULL,
  `usuario_id` int NOT NULL,
  `estilo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `bloquear_web` int DEFAULT NULL,
  `ocultar_formulario_contacto` int DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `color_web` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_4B8E81C7A76ED395` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `local`
--

INSERT INTO `local` (`id`, `nombre_local`, `descripcion_local`, `usuario_id`, `estilo`, `url`, `bloquear_web`, `ocultar_formulario_contacto`, `logo`, `color_web`) VALUES
(1, 'dasda', 'asdasdas', 1, NULL, '343', 1, 0, '6536afd479983.png', '#0000ff');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_menu` varchar(255) NOT NULL,
  `informacion_menu` longtext NOT NULL,
  `precio_menu` varchar(255) NOT NULL,
  `informacion_id` int NOT NULL,
  `estilo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_4F0B3F152A1239DE` (`informacion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `nombre_menu`, `informacion_menu`, `precio_menu`, `informacion_id`, `estilo`, `user_id`) VALUES
(1, 'otro menu', 'ffsdfs', '3', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_photo`
--

DROP TABLE IF EXISTS `menu_photo`;
CREATE TABLE IF NOT EXISTS `menu_photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `photo_path` varchar(255) NOT NULL,
  `menu_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_37AB59B2A76ED395` (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `menu_photo`
--

INSERT INTO `menu_photo` (`id`, `photo_path`, `menu_id`) VALUES
(1, '6536ac1a3922f.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_producto`
--

DROP TABLE IF EXISTS `menu_producto`;
CREATE TABLE IF NOT EXISTS `menu_producto` (
  `menu_id` int NOT NULL,
  `producto_id` int NOT NULL,
  PRIMARY KEY (`menu_id`,`producto_id`),
  KEY `FK_42E9D7C3BAE0B209` (`producto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `menu_producto`
--

INSERT INTO `menu_producto` (`menu_id`, `producto_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(255) NOT NULL,
  `informacion_producto` longtext NOT NULL,
  `precio_producto` varchar(255) NOT NULL,
  `estilo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `menus_id` int DEFAULT NULL,
  `informacion_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_2E2DB30D8F28E27` (`menus_id`),
  KEY `FK_2E2DB309A76ED395` (`informacion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre_producto`, `informacion_producto`, `precio_producto`, `estilo`, `menus_id`, `informacion_id`, `user_id`) VALUES
(1, 'df', 'sfsfsdfs', '3', NULL, 1, NULL, 1),
(2, 'df', 'sfsfsdfs', '3', NULL, 1, NULL, 1),
(3, 'sd', 'fsfsd', '3', NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_photo`
--

DROP TABLE IF EXISTS `producto_photo`;
CREATE TABLE IF NOT EXISTS `producto_photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `photo_path` varchar(1000) NOT NULL,
  `producto_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_BFF6921D98A51368` (`producto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto_photo`
--

INSERT INTO `producto_photo` (`id`, `photo_path`, `producto_id`) VALUES
(1, '6536ac57345e7.png', 2),
(2, '6536afcb7d7e5.png', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `email`, `roles`, `password`, `is_verified`) VALUES
(1, 'a@a.com', '[\"ROLE_USER\"]', '$2y$13$vMGFMJgJhVnZIQ30MQkaWeGysi9udTA6GvQIM9/sIXg7pqhd3sV.G', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_recovery`
--

DROP TABLE IF EXISTS `usuario_recovery`;
CREATE TABLE IF NOT EXISTS `usuario_recovery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha_expiracion` datetime NOT NULL,
  `user_id` int NOT NULL,
  `pin` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_D7152B7A76ED395` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
