-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.0 - MySQL Community Server - GPL
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


-- Volcando estructura de base de datos para my_creative_portfolio
CREATE DATABASE IF NOT EXISTS `my_creative_portfolio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `my_creative_portfolio`;

-- Volcando estructura para tabla my_creative_portfolio.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `id_estado_categoria` int NOT NULL,
  `nombre_categoria` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `id_estado_categoria` (`id_estado_categoria`),
  CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`id_estado_categoria`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.categorias: ~2 rows (aproximadamente)
INSERT INTO `categorias` (`id_categoria`, `id_estado_categoria`, `nombre_categoria`) VALUES
	(1, 1, 'Programación'),
	(2, 1, 'Entretenimiento');

-- Volcando estructura para tabla my_creative_portfolio.comentarios
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id_comentario` int NOT NULL AUTO_INCREMENT,
  `id_post_comentario` int NOT NULL,
  `id_estado_comentario` int NOT NULL DEFAULT '1',
  `id_usuario_comentario` int DEFAULT NULL,
  `contenido_comentario` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`id_comentario`),
  KEY `id_estado_comentario` (`id_estado_comentario`),
  KEY `id_post_comentario` (`id_post_comentario`),
  KEY `id_usuario_comentario` (`id_usuario_comentario`),
  CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_post_comentario`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comentarios_ibfk_3` FOREIGN KEY (`id_estado_comentario`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_usuario_comentario` FOREIGN KEY (`id_usuario_comentario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.comentarios: ~6 rows (aproximadamente)
INSERT INTO `comentarios` (`id_comentario`, `id_post_comentario`, `id_estado_comentario`, `id_usuario_comentario`, `contenido_comentario`) VALUES
	(116, 132, 1, 80, 'Muy interesante tu post.'),
	(117, 131, 1, NULL, 'Buen post'),
	(118, 131, 1, 80, 'Me gusto mi post'),
	(119, 131, 1, 80, 'Este es un post que he escrito aasdfasdfa y me ha gustaod ajsdfjksadkfjdsajka el pepe el pepe lele lpasdfsadfsa'),
	(120, 132, 1, 80, 'jk'),
	(121, 133, 1, 80, 'khgjvhkhkj'),
	(122, 133, 1, 80, ''),
	(123, 132, 2, NULL, 'sfdsa'),
	(124, 133, 2, NULL, 'enrique'),
	(125, 133, 1, NULL, 'Este es un comentario con texto en'),
	(126, 133, 2, NULL, '\'); DROP TABLE comentarios; --'),
	(127, 133, 1, NULL, '\'); DROP TABLE comentarios; --');

-- Volcando estructura para tabla my_creative_portfolio.comentarios_proyectos
CREATE TABLE IF NOT EXISTS `comentarios_proyectos` (
  `id_comentario` int NOT NULL AUTO_INCREMENT,
  `id_proyecto_comentario` int NOT NULL,
  `id_estado_comentario` int NOT NULL DEFAULT '1',
  `id_usuario_comentario` int DEFAULT NULL,
  `contenido_comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_comentario`),
  KEY `comentario_fk1` (`id_proyecto_comentario`),
  KEY `comentario_fk2` (`id_estado_comentario`),
  KEY `comentario_fk3` (`id_usuario_comentario`),
  CONSTRAINT `comentario_fk1` FOREIGN KEY (`id_proyecto_comentario`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comentario_fk2` FOREIGN KEY (`id_estado_comentario`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comentario_fk3` FOREIGN KEY (`id_usuario_comentario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla my_creative_portfolio.comentarios_proyectos: ~0 rows (aproximadamente)
INSERT INTO `comentarios_proyectos` (`id_comentario`, `id_proyecto_comentario`, `id_estado_comentario`, `id_usuario_comentario`, `contenido_comentario`) VALUES
	(43, 24, 1, 80, 'Mi propio comentario'),
	(44, 24, 2, NULL, 'proyecto probando');

-- Volcando estructura para tabla my_creative_portfolio.estado
CREATE TABLE IF NOT EXISTS `estado` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.estado: ~2 rows (aproximadamente)
INSERT INTO `estado` (`id_estado`, `nombre_estado`) VALUES
	(1, 'visible'),
	(2, 'oculto');

-- Volcando estructura para tabla my_creative_portfolio.etiquetas
CREATE TABLE IF NOT EXISTS `etiquetas` (
  `id_etiqueta` int NOT NULL AUTO_INCREMENT,
  `id_categoria_etiqueta` int NOT NULL,
  `nombre_etiqueta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`id_etiqueta`),
  KEY `id_categoria_etiqueta` (`id_categoria_etiqueta`),
  CONSTRAINT `etiquetas_ibfk_1` FOREIGN KEY (`id_categoria_etiqueta`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `id_etiqueta_agrupada` int NOT NULL AUTO_INCREMENT,
  `id_etiqueta_etiquetas_agrupadas` int NOT NULL,
  `id_post_etiquetas_agrupadas` int NOT NULL,
  PRIMARY KEY (`id_etiqueta_agrupada`),
  KEY `id_etiqueta_etiquetas_agrupadas` (`id_etiqueta_etiquetas_agrupadas`),
  KEY `id_post_etiquetas_agrupadas` (`id_post_etiquetas_agrupadas`),
  CONSTRAINT `etiquetas_agrupadas_ibfk_1` FOREIGN KEY (`id_etiqueta_etiquetas_agrupadas`) REFERENCES `etiquetas` (`id_etiqueta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `etiquetas_agrupadas_ibfk_2` FOREIGN KEY (`id_post_etiquetas_agrupadas`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.etiquetas_agrupadas: ~5 rows (aproximadamente)
INSERT INTO `etiquetas_agrupadas` (`id_etiqueta_agrupada`, `id_etiqueta_etiquetas_agrupadas`, `id_post_etiquetas_agrupadas`) VALUES
	(317, 8, 130),
	(319, 7, 132),
	(330, 4, 135),
	(331, 8, 131),
	(332, 4, 136);

-- Volcando estructura para tabla my_creative_portfolio.etiquetas_agrupadas_proyectos
CREATE TABLE IF NOT EXISTS `etiquetas_agrupadas_proyectos` (
  `id_etiqueta_agrupada` int NOT NULL AUTO_INCREMENT,
  `id_etiqueta_etiquetas_agrupadas` int NOT NULL,
  `id_proyecto_etiquetas_agrupadas` int NOT NULL,
  PRIMARY KEY (`id_etiqueta_agrupada`),
  KEY `id_etiqueta_etiquetas_agrupadas` (`id_etiqueta_etiquetas_agrupadas`),
  KEY `id_proyecto_etiquetas_agrupadas` (`id_proyecto_etiquetas_agrupadas`),
  CONSTRAINT `etiquetas_agrupadas_proyectos_ibfk_1` FOREIGN KEY (`id_proyecto_etiquetas_agrupadas`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `etiquetas_agrupadas_proyectos_ibfk_2` FOREIGN KEY (`id_etiqueta_etiquetas_agrupadas`) REFERENCES `etiquetas` (`id_etiqueta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.etiquetas_agrupadas_proyectos: ~4 rows (aproximadamente)
INSERT INTO `etiquetas_agrupadas_proyectos` (`id_etiqueta_agrupada`, `id_etiqueta_etiquetas_agrupadas`, `id_proyecto_etiquetas_agrupadas`) VALUES
	(64, 2, 22),
	(65, 1, 23),
	(67, 10, 25),
	(70, 9, 24);

-- Volcando estructura para tabla my_creative_portfolio.habilidades
CREATE TABLE IF NOT EXISTS `habilidades` (
  `id_habilidades` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `nombre_habilidades` varchar(50) NOT NULL,
  `tipo_habilidades` tinyint unsigned DEFAULT '0' COMMENT 'Solo cambia a 1 si es una tecnica',
  PRIMARY KEY (`id_habilidades`) USING BTREE,
  KEY `tipo_habilidades` (`tipo_habilidades`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla my_creative_portfolio.habilidades: ~4 rows (aproximadamente)
INSERT INTO `habilidades` (`id_habilidades`, `nombre_habilidades`, `tipo_habilidades`) VALUES
	(1, 'JavaScript', 1),
	(2, 'Python', 1),
	(3, 'Saludar', 0),
	(4, 'Comunicación', 0);

-- Volcando estructura para tabla my_creative_portfolio.portafolios
CREATE TABLE IF NOT EXISTS `portafolios` (
  `id_portafolio` int NOT NULL AUTO_INCREMENT,
  `id_usuario_portafolio` int NOT NULL,
  `titulo_portafolio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `ubicacion_portafolio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `educacion_portafolio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `mensaje_bienvenida_portafolio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `foto_portafolio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `fondo_portafolio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `cv_portafolio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `sobre_mi_portafolio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `fecha_modificacion_portafolio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_estado_portafolio` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_portafolio`),
  KEY `id_usuario_portafolio` (`id_usuario_portafolio`),
  KEY `estado_portafolio` (`id_estado_portafolio`),
  CONSTRAINT `estado_portafolio` FOREIGN KEY (`id_estado_portafolio`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `portafolios_ibfk_1` FOREIGN KEY (`id_usuario_portafolio`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.portafolios: ~1 rows (aproximadamente)
INSERT INTO `portafolios` (`id_portafolio`, `id_usuario_portafolio`, `titulo_portafolio`, `ubicacion_portafolio`, `educacion_portafolio`, `mensaje_bienvenida_portafolio`, `foto_portafolio`, `fondo_portafolio`, `cv_portafolio`, `sobre_mi_portafolio`, `fecha_modificacion_portafolio`, `id_estado_portafolio`) VALUES
	(29, 80, 'sadfadsfadsf', '665f566f558bb', 'asdsadasdsaasdsad', 'asdfadsfasdfadsfads', 'Fondo.jpg', 'Java - Enrique.png', 'curiculum.pdf', 'sadsadsadas', '2024-06-04 18:01:19', 1);

-- Volcando estructura para tabla my_creative_portfolio.portafolios_habilidades
CREATE TABLE IF NOT EXISTS `portafolios_habilidades` (
  `id_portafolios_habilidades` int unsigned NOT NULL AUTO_INCREMENT,
  `id_portafolio_portafolios_habilidades` int NOT NULL DEFAULT '0',
  `id_habilidad_portafolios_habilidades` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_portafolios_habilidades`),
  KEY `idPortafolio_portafolios_habilidades` (`id_portafolio_portafolios_habilidades`) USING BTREE,
  KEY `idHabilidad_portafolios_habilidades` (`id_habilidad_portafolios_habilidades`) USING BTREE,
  CONSTRAINT `fk-idHabilidad_portafolios_habilidades` FOREIGN KEY (`id_habilidad_portafolios_habilidades`) REFERENCES `habilidades` (`id_habilidades`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk-idPortafolio_portafolios_habilidades` FOREIGN KEY (`id_portafolio_portafolios_habilidades`) REFERENCES `portafolios` (`id_portafolio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1 COMMENT='Tabla para unir las habilidades de un portafolio a un usuario';

-- Volcando datos para la tabla my_creative_portfolio.portafolios_habilidades: ~2 rows (aproximadamente)
INSERT INTO `portafolios_habilidades` (`id_portafolios_habilidades`, `id_portafolio_portafolios_habilidades`, `id_habilidad_portafolios_habilidades`) VALUES
	(87, 29, 1),
	(88, 29, 3);

-- Volcando estructura para tabla my_creative_portfolio.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id_post` int NOT NULL AUTO_INCREMENT,
  `id_usuario_post` int NOT NULL,
  `id_categoria_post` int NOT NULL,
  `id_estado_post` int NOT NULL,
  `titulo_post` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `contenido_textual_post` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `ubicacion_imagen_post` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `fecha_creacion_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_post`),
  KEY `id_usuario_post` (`id_usuario_post`),
  KEY `id_categoria_post` (`id_categoria_post`),
  KEY `id_estado_post` (`id_estado_post`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_usuario_post`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`id_categoria_post`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`id_estado_post`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.posts: ~6 rows (aproximadamente)
INSERT INTO `posts` (`id_post`, `id_usuario_post`, `id_categoria_post`, `id_estado_post`, `titulo_post`, `contenido_textual_post`, `ubicacion_imagen_post`, `fecha_creacion_post`, `fecha_modificacion_post`) VALUES
	(130, 80, 2, 1, 'Informacion sobre Master Yi', 'Informacion sobre Master Yi', '../usersContent/Josue7655/posts/1d72d18393e2f4af.jpg', '2024-06-01 15:38:11', '2024-06-01 15:38:11'),
	(131, 80, 2, 2, 'Informacion sobre Zed', 'Informacion sobre Zed', '../usersContent/Josue7655/posts/b754eaa681ba0649.jpg', '2024-06-01 15:38:34', '2024-06-01 15:38:34'),
	(132, 80, 2, 1, 'Un libro interesante: Parallel', 'Un libro interesante: Parallel', '../usersContent/Josue7655/posts/596580776da7ada2.jpg', '2024-06-01 15:39:22', '2024-06-01 15:39:22'),
	(133, 80, 2, 1, 'Musica interesante y podcast', ' sadfsad', '../usersContent/Josue7655/posts/236192f407f53667.jpg', '2024-06-01 15:39:57', '2024-06-01 15:39:57'),
	(135, 80, 1, 2, 'Informacion sobre JavaScript', 'Informacion sobre JavaScript', '../usersContent/Josue7655/posts/ec7e0859938da0dd.jpg', '2024-06-01 15:42:36', '2024-06-01 15:42:36'),
	(136, 81, 1, 2, 'Post de enrique Js', 'Post de enrique Js', NULL, '2024-06-09 19:05:36', '2024-06-09 19:05:36');

-- Volcando estructura para tabla my_creative_portfolio.proyectos
CREATE TABLE IF NOT EXISTS `proyectos` (
  `id_proyecto` int NOT NULL AUTO_INCREMENT,
  `id_usuario_proyecto` int NOT NULL,
  `id_categoria_proyecto` int NOT NULL,
  `id_estado_proyecto` int NOT NULL,
  `titulo_proyecto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `descripcion_proyecto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `fecha_inicio_proyecto` date NOT NULL,
  `fecha_finalizacion_proyecto` date NOT NULL,
  `ubicacion_imagen_proyecto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `fecha_creacion_proyecto` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion_proyecto` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_proyecto`),
  KEY `id_categoria_proyecto` (`id_categoria_proyecto`),
  KEY `id_estado_proyecto` (`id_estado_proyecto`),
  KEY `id_usuario_proyecto` (`id_usuario_proyecto`),
  CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`id_categoria_proyecto`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `proyectos_ibfk_2` FOREIGN KEY (`id_usuario_proyecto`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `proyectos_ibfk_3` FOREIGN KEY (`id_estado_proyecto`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.proyectos: ~4 rows (aproximadamente)
INSERT INTO `proyectos` (`id_proyecto`, `id_usuario_proyecto`, `id_categoria_proyecto`, `id_estado_proyecto`, `titulo_proyecto`, `descripcion_proyecto`, `fecha_inicio_proyecto`, `fecha_finalizacion_proyecto`, `ubicacion_imagen_proyecto`, `fecha_creacion_proyecto`, `fecha_modificacion_proyecto`) VALUES
	(22, 80, 1, 1, 'Proyecto realizado en Java', 'Proyecto realizado en Java', '2004-01-01', '2004-02-02', '../usersContent/Josue7655/proyectos/44248e8fa936286e.png', '2024-06-01 10:43:26', '2024-06-01 15:43:26'),
	(23, 80, 1, 1, 'Proyecto realizado en Python', 'Proyecto realizado en Python', '2004-02-02', '2004-03-03', '../usersContent/Josue7655/proyectos/0cb7195955bfcb09.png', '2024-06-01 10:43:51', '2024-06-01 15:43:51'),
	(24, 80, 2, 1, 'Proyecto de un podcast', 'Proyecto de un podcastProyecto de un podcastProyecto de un podcastProyecto de un podcastProyecto de un podcastProyecto de un podcastProyecto de un podcastProyecto de un podcastProyecto de un podcastProyecto de un podcast', '2004-06-06', '2004-07-07', '../usersContent/Josue7655/proyectos/b93d845fa884ebff.jpg', '2024-06-01 10:45:00', '2024-06-01 15:45:00'),
	(25, 80, 2, 1, 'Proyecto de musica', 'Proyecto de musica', '2006-05-05', '2006-06-06', '../usersContent/Josue7655/proyectos/c3ca4fae0974a85b.jpeg', '2024-06-01 10:45:29', '2024-06-01 15:45:29');

-- Volcando estructura para tabla my_creative_portfolio.proyectos_agrupados_portafolio
CREATE TABLE IF NOT EXISTS `proyectos_agrupados_portafolio` (
  `id_proyecto_agrupado_portafolio` int NOT NULL AUTO_INCREMENT,
  `id_portafolio_proyectos_agrupados_portafolio` int NOT NULL,
  `id_proyecto_proyectos_agrupados_portafolio` int DEFAULT NULL,
  PRIMARY KEY (`id_proyecto_agrupado_portafolio`),
  KEY `id_proyecto_proyectos_agrupados_portafolio` (`id_proyecto_proyectos_agrupados_portafolio`),
  KEY `id_portafolio_proyectos_agrupados_portafolio` (`id_portafolio_proyectos_agrupados_portafolio`),
  CONSTRAINT `proyectos_agrupados_portafolio_ibfk_1` FOREIGN KEY (`id_proyecto_proyectos_agrupados_portafolio`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `proyectos_agrupados_portafolio_ibfk_2` FOREIGN KEY (`id_portafolio_proyectos_agrupados_portafolio`) REFERENCES `portafolios` (`id_portafolio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.proyectos_agrupados_portafolio: ~2 rows (aproximadamente)
INSERT INTO `proyectos_agrupados_portafolio` (`id_proyecto_agrupado_portafolio`, `id_portafolio_proyectos_agrupados_portafolio`, `id_proyecto_proyectos_agrupados_portafolio`) VALUES
	(39, 29, 23),
	(40, 29, 24);

-- Volcando estructura para tabla my_creative_portfolio.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.roles: ~2 rows (aproximadamente)
INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
	(1, 'usuario_estandar'),
	(2, 'usuario_administrador');

-- Volcando estructura para tabla my_creative_portfolio.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_rol_usuario` int NOT NULL DEFAULT '1',
  `nombre_usuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `correo_electronico_usuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `contrasenia_usuario` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `ubicacion_foto_perfil_usuario` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `carpeta_usuario` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `fecha_registro_usuario` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  KEY `id_rol_usuario` (`id_rol_usuario`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol_usuario`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla my_creative_portfolio.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `id_rol_usuario`, `nombre_usuario`, `correo_electronico_usuario`, `contrasenia_usuario`, `ubicacion_foto_perfil_usuario`, `carpeta_usuario`, `fecha_registro_usuario`) VALUES
	(80, 1, 'Josue', 'pruebasjos04@gmail.com', 'e5b69b1f507a588022bd45f914a62fd0940b0095b53ed310fb3a9bde4f8eba26', '../usersContent/Josue7655/perfil/4660d4152406d0ed.jpg', '../usersContent/Josue7655', '2024-05-23 20:28:35'),
	(81, 1, 'Enrique', 'pruebasjos05@gmail.com', 'e5b69b1f507a588022bd45f914a62fd0940b0095b53ed310fb3a9bde4f8eba26', '../usersContent/Enrique9287/perfil/e7c922ce9510f310.jpg', '../usersContent/Enrique9287', '2024-05-24 15:01:06'),
	(82, 1, 'Mario', 'pruebasjos06@gmail.com', 'e5b69b1f507a588022bd45f914a62fd0940b0095b53ed310fb3a9bde4f8eba26', '../usersContent/Mario7085/perfil/28e06250a4a1f584.jpg', '../usersContent/Mario7085', '2024-05-25 11:34:48');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
