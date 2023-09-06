-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-09-2023 a las 17:15:09
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
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion`
--

DROP TABLE IF EXISTS `informacion`;
CREATE TABLE IF NOT EXISTS `informacion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `telefono` int NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_spanish2_ci NOT NULL,
  `calle` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `localidad` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `local_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E4B777A55D5A2101` (`local_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `informacion`
--

INSERT INTO `informacion` (`id`, `telefono`, `descripcion`, `calle`, `localidad`, `ciudad`, `email`, `local_id`) VALUES
(10, 111, 'cac', 'c', 'c', 'c', 'luishomerogonzales93@gmail.com', 3),
(12, 333, 'casa', 'c', 'c', 'c', 'Lydiaelorrio48@gmail.com', 13),
(13, 23232323, 'Le ofrecemos la mejor comida china de CÁCERES, de gran calidad y al mejor precio con total confianza en todo.\r\n', 'cordoba', 'caceres', 'caceres', 'luishomerogonzales93@gmail.com', 14),
(14, 44, 'd', 'd', 'd', 'd', 'luishomerogonzales93@gmail.com', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_photo`
--

DROP TABLE IF EXISTS `informacion_photo`;
CREATE TABLE IF NOT EXISTS `informacion_photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `informacion_id` int NOT NULL,
  `photo_path` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `orden` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B2E2DA598C899341` (`informacion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informacion_photo`
--

INSERT INTO `informacion_photo` (`id`, `informacion_id`, `photo_path`, `orden`) VALUES
(31, 13, 'http://res.cloudinary.com/dmo3iliks/image/upload/v1693937338/veomjg2qx8yjip9znnp2.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local`
--

DROP TABLE IF EXISTS `local`;
CREATE TABLE IF NOT EXISTS `local` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_local` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `descripcion_local` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `foto_local` varchar(1000) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estilo` int DEFAULT '0',
  `url` varchar(20) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `bloquear_web` int DEFAULT NULL,
  `ocultar_formulario_contacto` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `local`
--

INSERT INTO `local` (`id`, `nombre_local`, `descripcion_local`, `foto_local`, `usuario_id`, `estilo`, `url`, `bloquear_web`, `ocultar_formulario_contacto`) VALUES
(14, 'Restaurante Chino Pekin 2222', 'restaurante chino pekin, todo lo que necesitas.', NULL, 8, 1, 'chino-pekin', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `informacion_menu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci,
  `precio_menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `foto_menu` varchar(1000) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `informacion_id` int DEFAULT NULL,
  `estilo` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `informacion_id` (`informacion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `nombre_menu`, `informacion_menu`, `precio_menu`, `foto_menu`, `informacion_id`, `estilo`) VALUES
(35, 'menu pekin', 'dadsa', '2', NULL, 13, NULL),
(34, 'otro menussss', 'sds', '2', NULL, 13, NULL),
(33, 'menu caro', 'menu caarisimo', '30', NULL, 13, NULL),
(30, 'otro menu', 'das', '2', NULL, 13, NULL),
(29, 'menu barato', 'este es el menu barato', '30', NULL, 13, NULL),
(36, 'otro menu 3232', 'ds', '23', NULL, 13, NULL),
(37, 'zzzzzzzzzzzzzz', 'zzzz', '2', NULL, 13, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_photo`
--

DROP TABLE IF EXISTS `menu_photo`;
CREATE TABLE IF NOT EXISTS `menu_photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_851BA77FCCD7E912` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menu_photo`
--

INSERT INTO `menu_photo` (`id`, `menu_id`, `photo_path`) VALUES
(1, 30, 'http://res.cloudinary.com/dmo3iliks/image/upload/v1692098397/teom0vah7b2wcxjhppi3.png'),
(2, 31, 'http://res.cloudinary.com/dmo3iliks/image/upload/v1692098527/xlno2ykixt3vtvzlelmb.png'),
(3, 32, 'http://res.cloudinary.com/dmo3iliks/image/upload/v1692098583/mqxpak8pey8whssglod9.png'),
(4, 35, 'http://res.cloudinary.com/dmo3iliks/image/upload/v1692126928/nyhwhqbmj6wgvmgr6zoy.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_producto`
--

DROP TABLE IF EXISTS `menu_producto`;
CREATE TABLE IF NOT EXISTS `menu_producto` (
  `menu_id` int NOT NULL,
  `producto_id` int NOT NULL,
  PRIMARY KEY (`menu_id`,`producto_id`),
  KEY `IDX_8BEA4383CCD7E912` (`menu_id`),
  KEY `IDX_8BEA43837645698E` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menu_producto`
--

INSERT INTO `menu_producto` (`menu_id`, `producto_id`) VALUES
(30, 66),
(30, 67),
(30, 68),
(33, 69),
(33, 70),
(35, 74),
(37, 73);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `informacion_producto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci,
  `precio_producto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `foto_producto` varchar(1000) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `menus_id` int DEFAULT NULL,
  `estilo` int DEFAULT '0',
  `informacion_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menus_id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre_producto`, `informacion_producto`, `precio_producto`, `foto_producto`, `menus_id`, `estilo`, `informacion_id`) VALUES
(72, 'suelto', 'sss', '2', NULL, NULL, NULL, 13),
(73, 'ssss', 'sss', '2', NULL, 37, NULL, NULL),
(74, 'carne pekin', 'pekin', '3', NULL, 35, NULL, NULL),
(70, 'pan de china', 'casda', '', NULL, 33, NULL, NULL),
(69, 'botella vino champan', 'exquisito champam', '', NULL, 33, NULL, NULL),
(68, 'agua', 'agua barata', '1', NULL, 30, NULL, NULL),
(66, 'papas barataas', 'barato', '1', NULL, 29, NULL, NULL),
(67, 'otro mas', 'ss', '2', NULL, 30, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_photo`
--

DROP TABLE IF EXISTS `producto_photo`;
CREATE TABLE IF NOT EXISTS `producto_photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `producto_id` int NOT NULL,
  `photo_path` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_54908C727645698E` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto_photo`
--

INSERT INTO `producto_photo` (`id`, `producto_id`, `photo_path`) VALUES
(1, 40, 'C:\\wamp64\\tmp\\php8626.tmp'),
(2, 41, 'C:\\wamp64\\tmp\\phpC795.tmp'),
(3, 42, 'C:\\wamp64\\tmp\\phpCF08.tmp'),
(4, 65, 'http://res.cloudinary.com/dmo3iliks/image/upload/v1692098072/djfmptg0aptr5vtmztjj.png'),
(5, 74, 'http://res.cloudinary.com/dmo3iliks/image/upload/v1692530309/gthp8bejqsk9aitdyvdl.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `is_verified` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `email`, `roles`, `password`, `is_verified`) VALUES
(1, 'test@example.com', '[\"ROLE_USER\"]', '$2y$13$z4R1amsgPYI77fQLZ0EciuxQ6jPfr0G1V.l.9zj1AYUhDOCg8nt/G', NULL),
(2, 't2est@example.com', '[\"ROLE_USER\"]', '$2y$13$l0IwU9OuOc7LZchrOF4JjOmQb/iGmNVQivYVgQKgkTXsJURGPS.x2', NULL),
(3, 't3est@example.com', '[\"ROLE_USER\"]', '$2y$13$G/pUl8Rxmmct8wudzpQLMOYOfH5JkRYjJS9KbtBRJ99fcywoWmb.G', NULL),
(4, 't3e3st@example.com', '[\"ROLE_USER\"]', '$2y$13$77p4ARKI1456nlR/awYuqulylsp803AQe79mJl1dMkjvLfNWiL2mW', NULL),
(5, 't3e33st@example.com', '[\"ROLE_USER\"]', '$2y$13$P1m99nRGHTufq9geLWXqD.iWzaNiU7X4jF4uMAHtNR8x.iZTP/CAO', NULL),
(6, 't3e33sst@example.com', '[\"ROLE_USER\"]', '$2y$13$YN75qGypxhpbI.o8ykOuo.PwCCsxVL4l7YBVAfn3S6eHZX41LbRPC', NULL),
(7, 't3e33ssst@example.com', '[\"ROLE_USER\"]', '$2y$13$FVJwrL1rF9BCZLCBeQySDePOtP0vnSmWGC//P5ZutVgZKehTsJUUe', NULL),
(8, 'a@a.com', '[\"ROLE_USER\"]', '$2y$13$9dqzGm99oSD.8aLDHYhW9ui0y5/Rx14V/O0owdZrwWSyyEvllI5.y', NULL),
(9, 'c@b.com', '[]', '$2y$13$dJJCBb65Hv.uloNGEPkxxOQpYFud4rQUDsCDrPshlaHDzuJn4rd.S', NULL),
(18, '', '[\"ROLE_USER\"]', '$2y$13$qMLkowj0ndL9XDqZfGXWuuIxz/NtE8x1Ag1/agqozPdXr8bR2AVGC', NULL),
(17, 'francis@francisdiamond.com', '[\"ROLE_USER\"]', '$2y$13$EHbT464oWU.DfrJoNTmNxuGZwtVm9x2hbiVxHSw7pE5zb5egSw09S', NULL),
(16, 'z@z.com', '[\"ROLE_USER\"]', '$2y$13$hOZBhJrF9rQRr8WqwJBOuumujyi1aXIHOdwa9VNTgPWQlM3aEqajm', NULL),
(15, 'luishomerogonzales93@gmail.com', '[\"ROLE_USER\"]', '$2y$13$B1PSoWfzOdRVRMCwRHKAdODmueSfApIVXZBDVWif6nfPK38ExoTFu', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_local`
--

DROP TABLE IF EXISTS `usuario_local`;
CREATE TABLE IF NOT EXISTS `usuario_local` (
  `local_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`local_id`,`usuario_id`),
  KEY `IDX_9608E2F65D5A2101` (`local_id`),
  KEY `IDX_9608E2F6DB38439E` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
