-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3007
-- Tiempo de generación: 01-06-2024 a las 18:16:47
-- Versión del servidor: 8.4.0
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `my_creative_portfolio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL,
  `id_estado_categoria` int NOT NULL,
  `nombre_categoria` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `id_estado_categoria`, `nombre_categoria`) VALUES
(1, 1, 'Programación'),
(2, 1, 'Entretenimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int NOT NULL,
  `id_post_comentario` int NOT NULL,
  `id_estado_comentario` int NOT NULL DEFAULT '1',
  `id_usuario_comentario` int DEFAULT NULL,
  `contenido_comentario` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios_proyectos`
--

CREATE TABLE `comentarios_proyectos` (
  `id_comentario` int NOT NULL,
  `id_proyecto_comentario` int NOT NULL,
  `id_estado_comentario` int NOT NULL DEFAULT '1',
  `id_usuario_comentario` int DEFAULT NULL,
  `contenido_comentario` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `comentarios_proyectos`
--

INSERT INTO `comentarios_proyectos` (`id_comentario`, `id_proyecto_comentario`, `id_estado_comentario`, `id_usuario_comentario`, `contenido_comentario`) VALUES
(43, 24, 1, 80, 'Mi propio comentario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int NOT NULL,
  `nombre_estado` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nombre_estado`) VALUES
(1, 'visible'),
(2, 'oculto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas`
--

CREATE TABLE `etiquetas` (
  `id_etiqueta` int NOT NULL,
  `id_categoria_etiqueta` int NOT NULL,
  `nombre_etiqueta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `etiquetas`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas_agrupadas`
--

CREATE TABLE `etiquetas_agrupadas` (
  `id_etiqueta_agrupada` int NOT NULL,
  `id_etiqueta_etiquetas_agrupadas` int NOT NULL,
  `id_post_etiquetas_agrupadas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `etiquetas_agrupadas`
--

INSERT INTO `etiquetas_agrupadas` (`id_etiqueta_agrupada`, `id_etiqueta_etiquetas_agrupadas`, `id_post_etiquetas_agrupadas`) VALUES
(316, 8, 129),
(317, 8, 130),
(318, 8, 131),
(319, 7, 132),
(320, 10, 133),
(321, 3, 134),
(323, 4, 135);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas_agrupadas_proyectos`
--

CREATE TABLE `etiquetas_agrupadas_proyectos` (
  `id_etiqueta_agrupada` int NOT NULL,
  `id_etiqueta_etiquetas_agrupadas` int NOT NULL,
  `id_proyecto_etiquetas_agrupadas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `etiquetas_agrupadas_proyectos`
--

INSERT INTO `etiquetas_agrupadas_proyectos` (`id_etiqueta_agrupada`, `id_etiqueta_etiquetas_agrupadas`, `id_proyecto_etiquetas_agrupadas`) VALUES
(64, 2, 22),
(65, 1, 23),
(67, 10, 25),
(69, 9, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `id_habilidades` tinyint UNSIGNED NOT NULL,
  `nombre_habilidades` varchar(50) NOT NULL,
  `tipo_habilidades` tinyint UNSIGNED DEFAULT '0' COMMENT 'Solo cambia a 1 si es una tecnica'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `habilidades`
--

INSERT INTO `habilidades` (`id_habilidades`, `nombre_habilidades`, `tipo_habilidades`) VALUES
(1, 'JavaScript', 1),
(2, 'Python', 1),
(3, 'Saludar', 0),
(4, 'Comunicación', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portafolios`
--

CREATE TABLE `portafolios` (
  `id_portafolio` int NOT NULL,
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
  `id_estado_portafolio` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portafolios_habilidades`
--

CREATE TABLE `portafolios_habilidades` (
  `id_portafolios_habilidades` int UNSIGNED NOT NULL,
  `id_portafolio_portafolios_habilidades` int NOT NULL DEFAULT '0',
  `id_habilidad_portafolios_habilidades` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla para unir las habilidades de un portafolio a un usuario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id_post` int NOT NULL,
  `id_usuario_post` int NOT NULL,
  `id_categoria_post` int NOT NULL,
  `id_estado_post` int NOT NULL,
  `titulo_post` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `contenido_textual_post` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `ubicacion_imagen_post` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `fecha_creacion_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion_post` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`id_post`, `id_usuario_post`, `id_categoria_post`, `id_estado_post`, `titulo_post`, `contenido_textual_post`, `ubicacion_imagen_post`, `fecha_creacion_post`, `fecha_modificacion_post`) VALUES
(129, 80, 2, 1, 'Liga de leyendas', 'Bienvenido a la liga de leyends.', '../usersContent/Josue7655/posts/9a8cff1a1c8ac91f.jpg', '2024-06-01 15:37:25', '2024-06-01 15:37:25'),
(130, 80, 2, 1, 'Informacion sobre Master Yi', 'Informacion sobre Master Yi', '../usersContent/Josue7655/posts/1d72d18393e2f4af.jpg', '2024-06-01 15:38:11', '2024-06-01 15:38:11'),
(131, 80, 2, 1, 'Informacion sobre Zed', 'Informacion sobre Zed', '../usersContent/Josue7655/posts/b754eaa681ba0649.jpg', '2024-06-01 15:38:34', '2024-06-01 15:38:34'),
(132, 80, 2, 1, 'Un libro interesante: Parallel', 'Un libro interesante: Parallel', '../usersContent/Josue7655/posts/596580776da7ada2.jpg', '2024-06-01 15:39:22', '2024-06-01 15:39:22'),
(133, 80, 2, 1, 'Musica interesante', 'Musica interesante', '../usersContent/Josue7655/posts/236192f407f53667.jpg', '2024-06-01 15:39:57', '2024-06-01 15:39:57'),
(134, 80, 1, 1, 'Informacion sobre C++', 'Informacion sobre C++', '../usersContent/Josue7655/posts/93fa3a1140594795.jpg', '2024-06-01 15:40:43', '2024-06-01 15:40:43'),
(135, 80, 1, 2, 'Informacion sobre JavaScript', 'Informacion sobre JavaScript', '../usersContent/Josue7655/posts/ec7e0859938da0dd.jpg', '2024-06-01 15:42:36', '2024-06-01 15:42:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id_proyecto` int NOT NULL,
  `id_usuario_proyecto` int NOT NULL,
  `id_categoria_proyecto` int NOT NULL,
  `id_estado_proyecto` int NOT NULL,
  `titulo_proyecto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `descripcion_proyecto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `fecha_inicio_proyecto` date NOT NULL,
  `fecha_finalizacion_proyecto` date NOT NULL,
  `ubicacion_imagen_proyecto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci,
  `fecha_creacion_proyecto` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion_proyecto` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id_proyecto`, `id_usuario_proyecto`, `id_categoria_proyecto`, `id_estado_proyecto`, `titulo_proyecto`, `descripcion_proyecto`, `fecha_inicio_proyecto`, `fecha_finalizacion_proyecto`, `ubicacion_imagen_proyecto`, `fecha_creacion_proyecto`, `fecha_modificacion_proyecto`) VALUES
(22, 80, 1, 1, 'Proyecto realizado en Java', 'Proyecto realizado en Java', '2004-01-01', '2004-02-02', '../usersContent/Josue7655/proyectos/44248e8fa936286e.png', '2024-06-01 10:43:26', '2024-06-01 15:43:26'),
(23, 80, 1, 1, 'Proyecto realizado en Python', 'Proyecto realizado en Python', '2004-02-02', '2004-03-03', '../usersContent/Josue7655/proyectos/0cb7195955bfcb09.png', '2024-06-01 10:43:51', '2024-06-01 15:43:51'),
(24, 80, 2, 1, 'Proyecto de un podcast', 'Proyecto de un podcast', '2004-06-06', '2004-07-07', '../usersContent/Josue7655/proyectos/b93d845fa884ebff.jpg', '2024-06-01 10:45:00', '2024-06-01 15:45:00'),
(25, 80, 2, 1, 'Proyecto de musica', 'Proyecto de musica', '2006-05-05', '2006-06-06', '../usersContent/Josue7655/proyectos/c3ca4fae0974a85b.jpeg', '2024-06-01 10:45:29', '2024-06-01 15:45:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos_agrupados_portafolio`
--

CREATE TABLE `proyectos_agrupados_portafolio` (
  `id_proyecto_agrupado_portafolio` int NOT NULL,
  `id_portafolio_proyectos_agrupados_portafolio` int NOT NULL,
  `id_proyecto_proyectos_agrupados_portafolio` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int NOT NULL,
  `nombre_rol` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'usuario_estandar'),
(2, 'usuario_administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `id_rol_usuario` int NOT NULL DEFAULT '1',
  `nombre_usuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `correo_electronico_usuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `contrasenia_usuario` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `ubicacion_foto_perfil_usuario` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `carpeta_usuario` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `fecha_registro_usuario` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol_usuario`, `nombre_usuario`, `correo_electronico_usuario`, `contrasenia_usuario`, `ubicacion_foto_perfil_usuario`, `carpeta_usuario`, `fecha_registro_usuario`) VALUES
(80, 1, 'Josue', 'pruebasjos04@gmail.com', 'e5b69b1f507a588022bd45f914a62fd0940b0095b53ed310fb3a9bde4f8eba26', '../usersContent/Josue7655/perfil/4660d4152406d0ed.jpg', '../usersContent/Josue7655', '2024-05-23 20:28:35'),
(81, 1, 'Enrique', 'pruebasjos05@gmail.com', 'e5b69b1f507a588022bd45f914a62fd0940b0095b53ed310fb3a9bde4f8eba26', '../usersContent/Enrique9287/perfil/e7c922ce9510f310.jpg', '../usersContent/Enrique9287', '2024-05-24 15:01:06'),
(82, 1, 'Mario', 'pruebasjos06@gmail.com', 'e5b69b1f507a588022bd45f914a62fd0940b0095b53ed310fb3a9bde4f8eba26', '../usersContent/Mario7085/perfil/28e06250a4a1f584.jpg', '../usersContent/Mario7085', '2024-05-25 11:34:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `id_estado_categoria` (`id_estado_categoria`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_estado_comentario` (`id_estado_comentario`),
  ADD KEY `id_post_comentario` (`id_post_comentario`),
  ADD KEY `id_usuario_comentario` (`id_usuario_comentario`);

--
-- Indices de la tabla `comentarios_proyectos`
--
ALTER TABLE `comentarios_proyectos`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `comentario_fk1` (`id_proyecto_comentario`),
  ADD KEY `comentario_fk2` (`id_estado_comentario`),
  ADD KEY `comentario_fk3` (`id_usuario_comentario`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id_etiqueta`),
  ADD KEY `id_categoria_etiqueta` (`id_categoria_etiqueta`);

--
-- Indices de la tabla `etiquetas_agrupadas`
--
ALTER TABLE `etiquetas_agrupadas`
  ADD PRIMARY KEY (`id_etiqueta_agrupada`),
  ADD KEY `id_etiqueta_etiquetas_agrupadas` (`id_etiqueta_etiquetas_agrupadas`),
  ADD KEY `id_post_etiquetas_agrupadas` (`id_post_etiquetas_agrupadas`);

--
-- Indices de la tabla `etiquetas_agrupadas_proyectos`
--
ALTER TABLE `etiquetas_agrupadas_proyectos`
  ADD PRIMARY KEY (`id_etiqueta_agrupada`),
  ADD KEY `id_etiqueta_etiquetas_agrupadas` (`id_etiqueta_etiquetas_agrupadas`),
  ADD KEY `id_proyecto_etiquetas_agrupadas` (`id_proyecto_etiquetas_agrupadas`);

--
-- Indices de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`id_habilidades`) USING BTREE,
  ADD KEY `tipo_habilidades` (`tipo_habilidades`);

--
-- Indices de la tabla `portafolios`
--
ALTER TABLE `portafolios`
  ADD PRIMARY KEY (`id_portafolio`),
  ADD KEY `id_usuario_portafolio` (`id_usuario_portafolio`),
  ADD KEY `estado_portafolio` (`id_estado_portafolio`);

--
-- Indices de la tabla `portafolios_habilidades`
--
ALTER TABLE `portafolios_habilidades`
  ADD PRIMARY KEY (`id_portafolios_habilidades`),
  ADD KEY `idPortafolio_portafolios_habilidades` (`id_portafolio_portafolios_habilidades`) USING BTREE,
  ADD KEY `idHabilidad_portafolios_habilidades` (`id_habilidad_portafolios_habilidades`) USING BTREE;

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_usuario_post` (`id_usuario_post`),
  ADD KEY `id_categoria_post` (`id_categoria_post`),
  ADD KEY `id_estado_post` (`id_estado_post`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id_proyecto`),
  ADD KEY `id_categoria_proyecto` (`id_categoria_proyecto`),
  ADD KEY `id_estado_proyecto` (`id_estado_proyecto`),
  ADD KEY `id_usuario_proyecto` (`id_usuario_proyecto`);

--
-- Indices de la tabla `proyectos_agrupados_portafolio`
--
ALTER TABLE `proyectos_agrupados_portafolio`
  ADD PRIMARY KEY (`id_proyecto_agrupado_portafolio`),
  ADD KEY `id_proyecto_proyectos_agrupados_portafolio` (`id_proyecto_proyectos_agrupados_portafolio`),
  ADD KEY `id_portafolio_proyectos_agrupados_portafolio` (`id_portafolio_proyectos_agrupados_portafolio`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol_usuario` (`id_rol_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de la tabla `comentarios_proyectos`
--
ALTER TABLE `comentarios_proyectos`
  MODIFY `id_comentario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id_etiqueta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `etiquetas_agrupadas`
--
ALTER TABLE `etiquetas_agrupadas`
  MODIFY `id_etiqueta_agrupada` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;

--
-- AUTO_INCREMENT de la tabla `etiquetas_agrupadas_proyectos`
--
ALTER TABLE `etiquetas_agrupadas_proyectos`
  MODIFY `id_etiqueta_agrupada` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `id_habilidades` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `portafolios`
--
ALTER TABLE `portafolios`
  MODIFY `id_portafolio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `portafolios_habilidades`
--
ALTER TABLE `portafolios_habilidades`
  MODIFY `id_portafolios_habilidades` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id_proyecto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `proyectos_agrupados_portafolio`
--
ALTER TABLE `proyectos_agrupados_portafolio`
  MODIFY `id_proyecto_agrupado_portafolio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`id_estado_categoria`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_post_comentario`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_3` FOREIGN KEY (`id_estado_comentario`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_usuario_comentario` FOREIGN KEY (`id_usuario_comentario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `comentarios_proyectos`
--
ALTER TABLE `comentarios_proyectos`
  ADD CONSTRAINT `comentario_fk1` FOREIGN KEY (`id_proyecto_comentario`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentario_fk2` FOREIGN KEY (`id_estado_comentario`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentario_fk3` FOREIGN KEY (`id_usuario_comentario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD CONSTRAINT `etiquetas_ibfk_1` FOREIGN KEY (`id_categoria_etiqueta`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `etiquetas_agrupadas`
--
ALTER TABLE `etiquetas_agrupadas`
  ADD CONSTRAINT `etiquetas_agrupadas_ibfk_1` FOREIGN KEY (`id_etiqueta_etiquetas_agrupadas`) REFERENCES `etiquetas` (`id_etiqueta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `etiquetas_agrupadas_ibfk_2` FOREIGN KEY (`id_post_etiquetas_agrupadas`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `etiquetas_agrupadas_proyectos`
--
ALTER TABLE `etiquetas_agrupadas_proyectos`
  ADD CONSTRAINT `etiquetas_agrupadas_proyectos_ibfk_1` FOREIGN KEY (`id_proyecto_etiquetas_agrupadas`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `etiquetas_agrupadas_proyectos_ibfk_2` FOREIGN KEY (`id_etiqueta_etiquetas_agrupadas`) REFERENCES `etiquetas` (`id_etiqueta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `portafolios`
--
ALTER TABLE `portafolios`
  ADD CONSTRAINT `estado_portafolio` FOREIGN KEY (`id_estado_portafolio`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `portafolios_ibfk_1` FOREIGN KEY (`id_usuario_portafolio`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `portafolios_habilidades`
--
ALTER TABLE `portafolios_habilidades`
  ADD CONSTRAINT `fk-idHabilidad_portafolios_habilidades` FOREIGN KEY (`id_habilidad_portafolios_habilidades`) REFERENCES `habilidades` (`id_habilidades`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-idPortafolio_portafolios_habilidades` FOREIGN KEY (`id_portafolio_portafolios_habilidades`) REFERENCES `portafolios` (`id_portafolio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_usuario_post`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`id_categoria_post`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`id_estado_post`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`id_categoria_proyecto`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proyectos_ibfk_2` FOREIGN KEY (`id_usuario_proyecto`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proyectos_ibfk_3` FOREIGN KEY (`id_estado_proyecto`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proyectos_agrupados_portafolio`
--
ALTER TABLE `proyectos_agrupados_portafolio`
  ADD CONSTRAINT `proyectos_agrupados_portafolio_ibfk_1` FOREIGN KEY (`id_proyecto_proyectos_agrupados_portafolio`) REFERENCES `proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `proyectos_agrupados_portafolio_ibfk_2` FOREIGN KEY (`id_portafolio_proyectos_agrupados_portafolio`) REFERENCES `portafolios` (`id_portafolio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol_usuario`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
