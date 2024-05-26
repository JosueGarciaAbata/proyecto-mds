-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         11.4.0-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla my_creative_portfolio.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_estado_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `id_estado_categoria` (`id_estado_categoria`),
  CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`id_estado_categoria`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.categorias: ~2 rows (aproximadamente)
INSERT INTO `categorias` (`id_categoria`, `id_estado_categoria`, `nombre_categoria`) VALUES
	(1, 1, 'Programación'),
	(2, 1, 'Entretenimiento');

-- Volcando estructura para tabla my_creative_portfolio.comentarios
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_post_comentario` int(11) NOT NULL,
  `id_usuario_comentario` int(11) NOT NULL,
  `id_estado_comentario` int(11) NOT NULL DEFAULT 1,
  `contenido_comentario` text NOT NULL,
  PRIMARY KEY (`id_comentario`),
  KEY `id_usuario_comentario` (`id_usuario_comentario`),
  KEY `id_estado_comentario` (`id_estado_comentario`),
  KEY `id_post_comentario` (`id_post_comentario`),
  CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_post_comentario`) REFERENCES `posts` (`id_post`) ON UPDATE CASCADE,
  CONSTRAINT `comentarios_ibfk_3` FOREIGN KEY (`id_estado_comentario`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.comentarios: ~0 rows (aproximadamente)
INSERT INTO `comentarios` (`id_comentario`, `id_post_comentario`, `id_usuario_comentario`, `id_estado_comentario`, `contenido_comentario`) VALUES
	(6, 119, 79, 1, 'q bonito');

-- Volcando estructura para tabla my_creative_portfolio.contenidos
CREATE TABLE IF NOT EXISTS `contenidos` (
  `id_contenido` int(11) NOT NULL AUTO_INCREMENT,
  `id_post_contenido` int(11) NOT NULL,
  `ubicacion_contenido` text NOT NULL,
  PRIMARY KEY (`id_contenido`),
  KEY `id_post_contenido` (`id_post_contenido`),
  CONSTRAINT `contenidos_ibfk_1` FOREIGN KEY (`id_post_contenido`) REFERENCES `posts` (`id_post`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.contenidos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla my_creative_portfolio.estado
CREATE TABLE IF NOT EXISTS `estado` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(50) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.estado: ~2 rows (aproximadamente)
INSERT INTO `estado` (`id_estado`, `nombre_estado`) VALUES
	(1, 'visible'),
	(2, 'oculto');

-- Volcando estructura para tabla my_creative_portfolio.etiquetas
CREATE TABLE IF NOT EXISTS `etiquetas` (
  `id_etiqueta` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria_etiqueta` int(11) NOT NULL,
  `nombre_etiqueta` varchar(50) NOT NULL,
  PRIMARY KEY (`id_etiqueta`),
  KEY `id_categoria_etiqueta` (`id_categoria_etiqueta`),
  CONSTRAINT `etiquetas_ibfk_1` FOREIGN KEY (`id_categoria_etiqueta`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.etiquetas: ~10 rows (aproximadamente)
INSERT INTO `etiquetas` (`id_etiqueta`, `id_categoria_etiqueta`, `nombre_etiqueta`) VALUES
	(1, 1, 'Python'),
	(2, 1, 'Java'),
	(3, 1, 'C++'),
	(4, 1, 'JavaScript'),
	(5, 1, 'C#'),
	(6, 2, 'Películas'),
	(7, 2, 'Libros'),
	(8, 2, 'Juegos'),
	(9, 2, 'Podcasts'),
	(10, 2, 'Música');

-- Volcando estructura para tabla my_creative_portfolio.etiquetas_agrupadas
CREATE TABLE IF NOT EXISTS `etiquetas_agrupadas` (
  `id_etiqueta_agrupada` int(11) NOT NULL AUTO_INCREMENT,
  `id_etiqueta_etiquetas_agrupadas` int(11) NOT NULL,
  `id_post_etiquetas_agrupadas` int(11) NOT NULL,
  PRIMARY KEY (`id_etiqueta_agrupada`),
  KEY `id_etiqueta_etiquetas_agrupadas` (`id_etiqueta_etiquetas_agrupadas`),
  KEY `id_post_etiquetas_agrupadas` (`id_post_etiquetas_agrupadas`),
  CONSTRAINT `etiquetas_agrupadas_ibfk_1` FOREIGN KEY (`id_etiqueta_etiquetas_agrupadas`) REFERENCES `etiquetas` (`id_etiqueta`) ON UPDATE CASCADE,
  CONSTRAINT `etiquetas_agrupadas_ibfk_2` FOREIGN KEY (`id_post_etiquetas_agrupadas`) REFERENCES `posts` (`id_post`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.etiquetas_agrupadas: ~12 rows (aproximadamente)
INSERT INTO `etiquetas_agrupadas` (`id_etiqueta_agrupada`, `id_etiqueta_etiquetas_agrupadas`, `id_post_etiquetas_agrupadas`) VALUES
	(270, 8, 118),
	(271, 2, 118),
	(275, 2, 117),
	(276, 1, 117),
	(277, 2, 119),
	(278, 4, 119),
	(281, 6, 121),
	(282, 7, 121),
	(283, 8, 121),
	(284, 9, 121),
	(285, 10, 121),
	(286, 7, 120);

-- Volcando estructura para tabla my_creative_portfolio.etiquetas_agrupadas_proyectos
CREATE TABLE IF NOT EXISTS `etiquetas_agrupadas_proyectos` (
  `id_etiqueta_agrupada` int(11) NOT NULL AUTO_INCREMENT,
  `id_etiqueta_etiquetas_agrupadas` int(11) NOT NULL,
  `id_proyecto_etiquetas_agrupadas` int(11) NOT NULL,
  PRIMARY KEY (`id_etiqueta_agrupada`),
  KEY `id_etiqueta_etiquetas_agrupadas` (`id_etiqueta_etiquetas_agrupadas`),
  KEY `id_proyecto_etiquetas_agrupadas` (`id_proyecto_etiquetas_agrupadas`),
  CONSTRAINT `etiquetas_agrupadas_proyectos_ibfk_1` FOREIGN KEY (`id_proyecto_etiquetas_agrupadas`) REFERENCES `proyectos` (`id_proyecto`) ON UPDATE CASCADE,
  CONSTRAINT `etiquetas_agrupadas_proyectos_ibfk_2` FOREIGN KEY (`id_etiqueta_etiquetas_agrupadas`) REFERENCES `etiquetas` (`id_etiqueta`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.etiquetas_agrupadas_proyectos: ~6 rows (aproximadamente)
INSERT INTO `etiquetas_agrupadas_proyectos` (`id_etiqueta_agrupada`, `id_etiqueta_etiquetas_agrupadas`, `id_proyecto_etiquetas_agrupadas`) VALUES
	(39, 1, 16),
	(40, 2, 16),
	(41, 3, 16),
	(42, 6, 17),
	(43, 7, 17),
	(44, 8, 17);

-- Volcando estructura para tabla my_creative_portfolio.habilidades
CREATE TABLE IF NOT EXISTS `habilidades` (
  `id_habilidades` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_habilidades` varchar(50) NOT NULL,
  `tipo_habilidades` tinyint(3) unsigned DEFAULT 0 COMMENT 'Solo cambia a 1 si es una tecnica',
  PRIMARY KEY (`id_habilidades`) USING BTREE,
  KEY `tipo_habilidades` (`tipo_habilidades`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla my_creative_portfolio.habilidades: ~4 rows (aproximadamente)
INSERT INTO `habilidades` (`id_habilidades`, `nombre_habilidades`, `tipo_habilidades`) VALUES
	(1, 'JavaScript', 1),
	(2, 'Python', 1),
	(3, 'Saludar', 0),
	(4, 'Comunicación', 0);

-- Volcando estructura para tabla my_creative_portfolio.portafolios
CREATE TABLE IF NOT EXISTS `portafolios` (
  `id_portafolio` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_portafolio` int(11) NOT NULL,
  `titulo_portafolio` text DEFAULT NULL,
  `ubicacion_portafolio` text DEFAULT NULL,
  `educacion_portafolio` text DEFAULT NULL,
  `mensaje_bienvenida_portafolio` text DEFAULT NULL,
  `foto_portafolio` text DEFAULT NULL,
  `fondo_portafolio` text DEFAULT NULL,
  `cv_portafolio` text DEFAULT NULL,
  `sobre_mi_portafolio` text DEFAULT NULL,
  `fecha_modificacion_portafolio` timestamp NULL DEFAULT current_timestamp(),
  `id_estado_portafolio` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_portafolio`),
  KEY `id_usuario_portafolio` (`id_usuario_portafolio`),
  KEY `estado_portafolio` (`id_estado_portafolio`),
  CONSTRAINT `estado_portafolio` FOREIGN KEY (`id_estado_portafolio`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE,
  CONSTRAINT `portafolios_ibfk_1` FOREIGN KEY (`id_usuario_portafolio`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.portafolios: ~3 rows (aproximadamente)
INSERT INTO `portafolios` (`id_portafolio`, `id_usuario_portafolio`, `titulo_portafolio`, `ubicacion_portafolio`, `educacion_portafolio`, `mensaje_bienvenida_portafolio`, `foto_portafolio`, `fondo_portafolio`, `cv_portafolio`, `sobre_mi_portafolio`, `fecha_modificacion_portafolio`, `id_estado_portafolio`) VALUES
	(22, 79, 'ANOOOOOOOOOOOOOOOO', '664d5c697eff0', 'khfckhfckgfx', 'jtgkhhfckcfhc', 'lobo-con-luces-rojas-y-azules_3840x2160_xtrafondos.com.jpg', 'joker-guason-minimalist_3840x2160_xtrafondos.com.jpg', 'Spring boot persistence best practices.pdf', 'jfckhfckfckf', '2024-05-20 04:28:28', 1),
	(23, 79, 'el nuevo portafolio', '664ad18e3f1df', 'khfckhfckgfx', 'soy un portafolio', '1400616.jpg', '1400616.jpg', 'Spring boot persistence best practices.pdf', 'jfckhfckfckf', '2024-05-20 04:29:02', 1),
	(24, 79, 'kjhvlivlujghvljghvlkj', '664d5cbb638af', 'uuuuuuuuuuuuuuta', 'ñlujhbpñiyhvgbpiyvbpiuvbpiyhgbv', 'batman-minimalist_3840x2160_xtrafondos.com.jpg', 'batman-minimalist_3840x2160_xtrafondos.com.jpg', 'Spring boot persistence best practices.pdf', 'aaaaaaaaaaaaaaaaaaaaaaut', '2024-05-22 02:47:23', 1),
	(25, 79, 'el nuevo portafolio', '66512bfb1999f', 'espoch', 'soy un portafolio', 'paisaje-digital-en-atardecer_3840x2160_xtrafondos.com.jpg', 'montanas-nevadas-cielo-con-estrellas-y-cometa_3840x2160_xtrafondos.com.jpg', 'Spring boot persistence best practices.pdf', 'soy un ser humano', '2024-05-24 19:54:16', 1);

-- Volcando estructura para tabla my_creative_portfolio.portafolios_habilidades
CREATE TABLE IF NOT EXISTS `portafolios_habilidades` (
  `id_portafolios_habilidades` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_portafolio_portafolios_habilidades` int(11) NOT NULL DEFAULT 0,
  `id_habilidad_portafolios_habilidades` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_portafolios_habilidades`),
  KEY `idPortafolio_portafolios_habilidades` (`id_portafolio_portafolios_habilidades`) USING BTREE,
  KEY `idHabilidad_portafolios_habilidades` (`id_habilidad_portafolios_habilidades`) USING BTREE,
  CONSTRAINT `fk-idHabilidad_portafolios_habilidades` FOREIGN KEY (`id_habilidad_portafolios_habilidades`) REFERENCES `habilidades` (`id_habilidades`) ON UPDATE CASCADE,
  CONSTRAINT `fk-idPortafolio_portafolios_habilidades` FOREIGN KEY (`id_portafolio_portafolios_habilidades`) REFERENCES `portafolios` (`id_portafolio`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Tabla para unir las habilidades de un portafolio a un usuario';

-- Volcando datos para la tabla my_creative_portfolio.portafolios_habilidades: ~11 rows (aproximadamente)
INSERT INTO `portafolios_habilidades` (`id_portafolios_habilidades`, `id_portafolio_portafolios_habilidades`, `id_habilidad_portafolios_habilidades`) VALUES
	(64, 23, 1),
	(65, 23, 2),
	(66, 23, 3),
	(75, 22, 2),
	(76, 22, 4),
	(77, 24, 1),
	(78, 24, 4),
	(86, 25, 1),
	(87, 25, 2),
	(88, 25, 3),
	(89, 25, 4);

-- Volcando estructura para tabla my_creative_portfolio.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_post` int(11) NOT NULL,
  `id_categoria_post` int(11) NOT NULL,
  `id_estado_post` int(11) NOT NULL,
  `titulo_post` varchar(50) NOT NULL,
  `contenido_textual_post` text NOT NULL,
  `ubicacion_imagen_post` text DEFAULT NULL,
  `fecha_creacion_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion_post` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_post`),
  KEY `id_usuario_post` (`id_usuario_post`),
  KEY `id_categoria_post` (`id_categoria_post`),
  KEY `id_estado_post` (`id_estado_post`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_usuario_post`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`id_categoria_post`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE,
  CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`id_estado_post`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.posts: ~4 rows (aproximadamente)
INSERT INTO `posts` (`id_post`, `id_usuario_post`, `id_categoria_post`, `id_estado_post`, `titulo_post`, `contenido_textual_post`, `ubicacion_imagen_post`, `fecha_creacion_post`, `fecha_modificacion_post`) VALUES
	(117, 78, 1, 2, 'javaaaaaaaaaaa', 'javaaaaaaaaaaajavaaaaaaaaaaajavaaaaaaaaaaa', '../usersContent/Josue7884/posts/a9e3ad3543a4ddce.png', '2024-05-09 23:10:25', '2024-05-09 23:10:25'),
	(118, 78, 2, 2, 'juegosjuegos', 'juegosjuegosjuegosjuegos', '../usersContent/Josue7884/posts/d9418eeecffb7168.png', '2024-05-09 23:15:13', '2024-05-09 23:15:13'),
	(119, 79, 1, 1, 'ALFONSO QUE HA PASADO!!!', 'esto es una prueba', '../usersContent/DRTX_13436/posts/b205d038386deb8f.jpg', '2024-05-12 13:10:57', '2024-05-12 13:10:57'),
	(120, 79, 2, 1, 'soy guts :|', 'VOY A DESPEDAZAR A ESOS CABRONES', '../usersContent/DRTX_13436/posts/0445a6618c3e3ba3.jpg', '2024-05-12 13:12:03', '2024-05-12 13:12:03'),
	(121, 79, 2, 1, 'Soy batman!!!', 'mira el plan es que ........................... y por eso te la voy a meter.', '../usersContent/DRTX_13436/posts/6f9b017b9ad8c4ff.jpg', '2024-05-18 22:36:38', '2024-05-18 22:36:38');

-- Volcando estructura para tabla my_creative_portfolio.proyectos
CREATE TABLE IF NOT EXISTS `proyectos` (
  `id_proyecto` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_proyecto` int(11) NOT NULL,
  `id_categoria_proyecto` int(11) NOT NULL,
  `id_estado_proyecto` int(11) NOT NULL,
  `titulo_proyecto` text DEFAULT NULL,
  `descripcion_proyecto` text DEFAULT NULL,
  `fecha_inicio_proyecto` date NOT NULL,
  `fecha_finalizacion_proyecto` date NOT NULL,
  `ubicacion_imagen_proyecto` text DEFAULT NULL,
  `fecha_creacion_proyecto` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion_proyecto` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_proyecto`),
  KEY `id_categoria_proyecto` (`id_categoria_proyecto`),
  KEY `id_estado_proyecto` (`id_estado_proyecto`),
  KEY `id_usuario_proyecto` (`id_usuario_proyecto`),
  CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`id_categoria_proyecto`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE,
  CONSTRAINT `proyectos_ibfk_2` FOREIGN KEY (`id_usuario_proyecto`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  CONSTRAINT `proyectos_ibfk_3` FOREIGN KEY (`id_estado_proyecto`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.proyectos: ~0 rows (aproximadamente)
INSERT INTO `proyectos` (`id_proyecto`, `id_usuario_proyecto`, `id_categoria_proyecto`, `id_estado_proyecto`, `titulo_proyecto`, `descripcion_proyecto`, `fecha_inicio_proyecto`, `fecha_finalizacion_proyecto`, `ubicacion_imagen_proyecto`, `fecha_creacion_proyecto`, `fecha_modificacion_proyecto`) VALUES
	(16, 79, 1, 1, 'dime q si mano', 'prueba de que esto sirve y no es una pendejada', '2024-05-11', '2024-05-29', '../usersContent/DRTX_13436/proyectos/bb64f846d1a807c8.jpg', '2024-05-11 23:56:59', '2024-05-12 04:56:59'),
	(17, 79, 2, 1, 'affas asd as', 'asd asd szfa asfd sad asd as', '2024-05-12', '2024-05-13', '../usersContent/DRTX_13436/proyectos/3db395498eaba180.jpg', '2024-05-12 08:12:52', '2024-05-12 13:12:52');

-- Volcando estructura para tabla my_creative_portfolio.proyectos_agrupados_portafolio
CREATE TABLE IF NOT EXISTS `proyectos_agrupados_portafolio` (
  `id_proyecto_agrupado_portafolio` int(11) NOT NULL AUTO_INCREMENT,
  `id_portafolio_proyectos_agrupados_portafolio` int(11) NOT NULL,
  `id_proyecto_proyectos_agrupados_portafolio` int(11) NOT NULL,
  PRIMARY KEY (`id_proyecto_agrupado_portafolio`),
  KEY `id_proyecto_proyectos_agrupados_portafolio` (`id_proyecto_proyectos_agrupados_portafolio`),
  KEY `id_portafolio_proyectos_agrupados_portafolio` (`id_portafolio_proyectos_agrupados_portafolio`),
  CONSTRAINT `proyectos_agrupados_portafolio_ibfk_1` FOREIGN KEY (`id_proyecto_proyectos_agrupados_portafolio`) REFERENCES `proyectos` (`id_proyecto`) ON UPDATE CASCADE,
  CONSTRAINT `proyectos_agrupados_portafolio_ibfk_2` FOREIGN KEY (`id_portafolio_proyectos_agrupados_portafolio`) REFERENCES `portafolios` (`id_portafolio`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.proyectos_agrupados_portafolio: ~5 rows (aproximadamente)
INSERT INTO `proyectos_agrupados_portafolio` (`id_proyecto_agrupado_portafolio`, `id_portafolio_proyectos_agrupados_portafolio`, `id_proyecto_proyectos_agrupados_portafolio`) VALUES
	(25, 23, 17),
	(30, 22, 16),
	(31, 24, 16),
	(35, 25, 16),
	(36, 25, 17);

-- Volcando estructura para tabla my_creative_portfolio.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.roles: ~2 rows (aproximadamente)
INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
	(1, 'usuario_estandar'),
	(2, 'usuario_administrador');

-- Volcando estructura para tabla my_creative_portfolio.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol_usuario` int(11) NOT NULL DEFAULT 1,
  `nombre_usuario` varchar(50) NOT NULL,
  `correo_electronico_usuario` varchar(50) NOT NULL,
  `contrasenia_usuario` varchar(100) NOT NULL,
  `ubicacion_foto_perfil_usuario` text NOT NULL,
  `carpeta_usuario` text NOT NULL,
  `fecha_registro_usuario` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  KEY `id_rol_usuario` (`id_rol_usuario`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol_usuario`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.usuarios: ~2 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `id_rol_usuario`, `nombre_usuario`, `correo_electronico_usuario`, `contrasenia_usuario`, `ubicacion_foto_perfil_usuario`, `carpeta_usuario`, `fecha_registro_usuario`) VALUES
	(78, 1, 'Josue', 'pruebasjos06@gmail.com', 'e5b69b1f507a588022bd45f914a62fd0940b0095b53ed310fb3a9bde4f8eba26', '../usersContent/Josue7884/perfil/defaulAvatar.png', '../usersContent/Josue7884', '2024-05-08 21:41:17'),
	(79, 1, 'DRTX_1', 'davidmanjarres2004@gmail.com', 'd6c2be345d2fb7219a3329e15c6d030ec532ce1618b06900adbf8633e7ae57c1', '../usersContent/DRTX_13436/perfil/defaulAvatar.png', '../usersContent/DRTX_13436', '2024-05-11 17:31:12');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
