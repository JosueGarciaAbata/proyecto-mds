-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-05-2024 a las 22:05:00
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
-- Base de datos: `my_creative_portfolio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `id_estado_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
  `id_comentario` int(11) NOT NULL,
  `id_post_comentario` int(11) NOT NULL,
  `id_usuario_comentario` int(11) NOT NULL,
  `id_estado_comentario` int(11) NOT NULL,
  `contenido_comentario` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenidos`
--

CREATE TABLE `contenidos` (
  `id_contenido` int(11) NOT NULL,
  `id_post_contenido` int(11) NOT NULL,
  `ubicacion_contenido` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
  `id_etiqueta` int(11) NOT NULL,
  `id_categoria_etiqueta` int(11) NOT NULL,
  `nombre_etiqueta` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
(9, 2, ' Podcasts'),
(10, 2, 'Música');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas_agrupadas`
--

CREATE TABLE `etiquetas_agrupadas` (
  `id_etiqueta_agrupada` int(11) NOT NULL,
  `id_etiqueta_etiquetas_agrupadas` int(11) NOT NULL,
  `id_post_etiquetas_agrupadas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `etiquetas_agrupadas`
--

INSERT INTO `etiquetas_agrupadas` (`id_etiqueta_agrupada`, `id_etiqueta_etiquetas_agrupadas`, `id_post_etiquetas_agrupadas`) VALUES
(86, 6, 74),
(87, 7, 74),
(88, 8, 74),
(97, 1, 76),
(98, 2, 76),
(99, 6, 69),
(100, 7, 69);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas_agrupadas_proyectos`
--

CREATE TABLE `etiquetas_agrupadas_proyectos` (
  `id_etiqueta_agrupada` int(11) NOT NULL,
  `id_etiqueta_etiquetas_agrupadas` int(11) NOT NULL,
  `id_proyecto_etiquetas_agrupadas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `etiquetas_agrupadas_proyectos`
--

INSERT INTO `etiquetas_agrupadas_proyectos` (`id_etiqueta_agrupada`, `id_etiqueta_etiquetas_agrupadas`, `id_proyecto_etiquetas_agrupadas`) VALUES
(10, 6, 5),
(11, 7, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portafolios`
--

CREATE TABLE `portafolios` (
  `id_portafolio` int(11) NOT NULL,
  `id_usuario_portafolio` int(11) NOT NULL,
  `nombre_apellido_portafolio` varchar(100) NOT NULL,
  `habilidades_portafolio` text NOT NULL,
  `habilidades_sociales_portafolio` text NOT NULL,
  `educacion_portafolio` text NOT NULL,
  `sobre_mi_portafolio` text NOT NULL,
  `mensaje_bienvenida_portafolio` text NOT NULL,
  `ubicacion_foto_portafolio` text NOT NULL,
  `ubicacion_cv_portafolio` text NOT NULL,
  `fecha_modificacion_portafolio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id_post` int(11) NOT NULL,
  `id_usuario_post` int(11) NOT NULL,
  `id_categoria_post` int(11) NOT NULL,
  `id_estado_post` int(11) NOT NULL,
  `titulo_post` varchar(50) NOT NULL,
  `contenido_textual_post` text NOT NULL,
  `ubicacion_imagen_post` text NOT NULL,
  `fecha_creacion_post` datetime NOT NULL,
  `fecha_modificacion_post` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`id_post`, `id_usuario_post`, `id_categoria_post`, `id_estado_post`, `titulo_post`, `contenido_textual_post`, `ubicacion_imagen_post`, `fecha_creacion_post`, `fecha_modificacion_post`) VALUES
(45, 63, 1, 1, 'dasdasddasdas', 'dsadasda', '', '0000-00-00 00:00:00', '2024-04-20 00:47:43'),
(46, 63, 1, 1, 'dasdasdsadasdsa', 'dasdasd', '', '0000-00-00 00:00:00', '2024-04-20 00:54:13'),
(47, 63, 1, 1, 'dasdasdasdas', 'dasdas', '', '0000-00-00 00:00:00', '2024-04-20 00:54:57'),
(48, 63, 1, 1, 'dasdasddas', 'dasdas', '', '0000-00-00 00:00:00', '2024-04-20 00:56:59'),
(49, 63, 1, 1, 'dasdasddas', 'dasdas', '', '0000-00-00 00:00:00', '2024-04-20 00:57:03'),
(51, 63, 1, 1, 'dasdasdasdas', 'dsadasdasdas', '', '0000-00-00 00:00:00', '2024-04-20 01:00:11'),
(53, 64, 1, 2, 'JSADFHADALHK', 'LASHDAJLKSQWUE', '../usersContent/Joel123642/posts/a74900b7740d181f.jpg', '0000-00-00 00:00:00', '2024-04-20 01:26:43'),
(60, 63, 1, 1, 'The Liness', 'La princesa', '../usersContent/Josue1352/posts/7a5bcc5fb9a58d0a.jpg', '0000-00-00 00:00:00', '2024-04-20 14:09:47'),
(61, 63, 1, 2, 'dasdasdasdasdas', 'dasdas', '', '0000-00-00 00:00:00', '2024-04-20 14:10:14'),
(62, 63, 1, 1, 'The New Cambio', 'dasdasdasdas', '../usersContent/Josue1352/posts/829319d6fd0815e8.jpg', '0000-00-00 00:00:00', '2024-04-20 14:10:37'),
(63, 63, 1, 1, 'dasdasddsadas', 'dasdsa', '../usersContent/Josue1352/posts/1bb638ffe2f32d8f.jpg', '0000-00-00 00:00:00', '2024-04-20 14:49:49'),
(64, 63, 2, 1, 'Temporall12', 'dasdasdasdas', '', '0000-00-00 00:00:00', '2024-04-20 15:39:05'),
(66, 63, 2, 1, 'ssssssssssss', 'asdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasddddddddddddasdddddddddddddddddddddddddddddddddddddasdasddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddasdasdddddddddddd', '', '0000-00-00 00:00:00', '2024-04-20 16:48:40'),
(69, 63, 2, 1, 'The New Post', 'Thats\'s a post content', '../usersContent/Josue1352/posts/6f0da6b3f297ce9b.jpg', '0000-00-00 00:00:00', '2024-04-20 17:45:37'),
(72, 65, 2, 1, 'Entretenimiento', 'Entretenimiento', '', '0000-00-00 00:00:00', '2024-04-22 23:33:37'),
(74, 64, 2, 1, 'El señor de los anillos', 'Contenido12', '../usersContent/Joel123642/posts/6f7604a1d304e318.jpg', '0000-00-00 00:00:00', '2024-04-23 15:22:41'),
(76, 68, 1, 1, 'The New Game', 'Dasasdsadasd', '../usersContent/cxzxcz4862/posts/db131a006146c1db.jpg', '0000-00-00 00:00:00', '2024-05-03 14:10:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id_proyecto` int(11) NOT NULL,
  `id_usuario_proyecto` int(11) NOT NULL,
  `id_categoria_proyecto` int(11) NOT NULL,
  `id_estado_proyecto` int(11) NOT NULL,
  `titulo_proyecto` text NOT NULL,
  `descripcion_proyecto` text NOT NULL,
  `fecha_inicio_proyecto` date NOT NULL,
  `fecha_finalizacion_proyecto` date NOT NULL,
  `ubicacion_imagen_proyecto` text NOT NULL,
  `fecha_creacion_proyecto` datetime NOT NULL,
  `fecha_modificacion_proyecto` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id_proyecto`, `id_usuario_proyecto`, `id_categoria_proyecto`, `id_estado_proyecto`, `titulo_proyecto`, `descripcion_proyecto`, `fecha_inicio_proyecto`, `fecha_finalizacion_proyecto`, `ubicacion_imagen_proyecto`, `fecha_creacion_proyecto`, `fecha_modificacion_proyecto`) VALUES
(1, 69, 2, 1, 'dasdasdasd', 'dasdasdas', '2024-05-03', '2024-05-03', '', '2024-05-03 17:32:48', '2024-05-03 15:33:02'),
(2, 63, 1, 1, 'dasdasdasdas', 'dasdasdasdasdas', '2024-05-08', '2024-05-18', '../usersContent/Josue1352/proyectos/259044e77c90f9c0.jpg', '0000-00-00 00:00:00', '2024-05-03 16:13:27'),
(3, 63, 1, 1, 'dasdasdasdas', 'dasdasdasdasdas', '2024-05-08', '2024-05-18', '../usersContent/Josue1352/proyectos/c22ab2ca5a2a1a42.jpg', '0000-00-00 00:00:00', '2024-05-03 16:17:14'),
(5, 63, 2, 1, 'Los 300 espartanos', 'Pelicula delos 300 espartanos', '2024-05-08', '2024-05-18', '../usersContent/Josue1352/proyectos/85adb9b7f3e884c0.jpg', '0000-00-00 00:00:00', '2024-05-03 16:44:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos_agrupados_portafolio`
--

CREATE TABLE `proyectos_agrupados_portafolio` (
  `id_proyecto_agrupado_portafolio` int(11) NOT NULL,
  `id_proyecto_proyectos_agrupados_portafolio` int(11) NOT NULL,
  `id_portafolio_proyectos_agrupados_portafolio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
  `id_usuario` int(11) NOT NULL,
  `id_rol_usuario` int(11) NOT NULL DEFAULT 1,
  `nombre_usuario` varchar(50) NOT NULL,
  `correo_electronico_usuario` varchar(50) NOT NULL,
  `contrasenia_usuario` varchar(100) NOT NULL,
  `ubicacion_foto_perfil_usuario` text NOT NULL,
  `carpeta_usuario` text NOT NULL,
  `fecha_registro_usuario` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol_usuario`, `nombre_usuario`, `correo_electronico_usuario`, `contrasenia_usuario`, `ubicacion_foto_perfil_usuario`, `carpeta_usuario`, `fecha_registro_usuario`) VALUES
(63, 1, 'Josue', 'pruebasjos04@gmail.com', '350999c6bb95c301acdf44e32bec6cd3c1fdfce389fe6a04902c91baa8b39476', '../usersContent/Josue1352/perfil/da3fc585c50d45b4.jpg', '../usersContent/Josue1352', '2024-04-19 17:39:59'),
(64, 1, 'TheJoe', 'bjeferssonvinicio2005@gmail.com', '1ebb7892e4d3c8e9f9e7ee45478e4f81912a430b0fc0b668b3a9b14f91a18cd4', '../usersContent/Joel123642/perfil/e5ec9b8614767981.jpeg', '../usersContent/Joel123642', '2024-04-19 20:25:44'),
(65, 1, 'TheKing12', 'joelbonillaguerrrero@gmail.com', 'c12812878bf5a2ec53c00c9665395865aed2ee9a7c7df70e1e3c4f5f96d0dadd', '../usersContent/TheKing4341/perfil/3b03ab2f79a75b52.jpg', '../usersContent/TheKing4341', '2024-04-22 18:15:54'),
(66, 1, 'sadasdas', 'joel04@gmail.com', '350999c6bb95c301acdf44e32bec6cd3c1fdfce389fe6a04902c91baa8b39476', '../usersContent/sadasdas7847/perfil/defaulAvatar.png', '../usersContent/sadasdas7847', '2024-05-03 09:03:11'),
(67, 1, 'dasdasdas', 'pruebasjossa04@gmail.com', '350999c6bb95c301acdf44e32bec6cd3c1fdfce389fe6a04902c91baa8b39476', '../usersContent/dasdasdas7326/perfil/defaulAvatar.png', '../usersContent/dasdasdas7326', '2024-05-03 09:03:35'),
(68, 1, 'cxzxcz', 'pruebasjos0s4@gmail.com', '350999c6bb95c301acdf44e32bec6cd3c1fdfce389fe6a04902c91baa8b39476', '../usersContent/cxzxcz4862/perfil/defaulAvatar.png', '../usersContent/cxzxcz4862', '2024-05-03 09:09:09'),
(69, 1, 'TheJa', 'pruebasjos10@gmail.com', '350999c6bb95c301acdf44e32bec6cd3c1fdfce389fe6a04902c91baa8b39476', '../usersContent/TheJa5582/perfil/defaulAvatar.png', '../usersContent/TheJa5582', '2024-05-03 09:40:35'),
(70, 1, 'JuanCarlos', 'Miguel@gmail.com', '350999c6bb95c301acdf44e32bec6cd3c1fdfce389fe6a04902c91baa8b39476', '../usersContent/JuanCarlos6183/perfil/defaulAvatar.png', '../usersContent/JuanCarlos6183', '2024-05-03 13:34:11');

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
  ADD KEY `id_usuario_comentario` (`id_usuario_comentario`),
  ADD KEY `id_estado_comentario` (`id_estado_comentario`),
  ADD KEY `id_post_comentario` (`id_post_comentario`);

--
-- Indices de la tabla `contenidos`
--
ALTER TABLE `contenidos`
  ADD PRIMARY KEY (`id_contenido`),
  ADD KEY `id_post_contenido` (`id_post_contenido`);

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
-- Indices de la tabla `portafolios`
--
ALTER TABLE `portafolios`
  ADD PRIMARY KEY (`id_portafolio`),
  ADD KEY `id_usuario_portafolio` (`id_usuario_portafolio`);

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
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contenidos`
--
ALTER TABLE `contenidos`
  MODIFY `id_contenido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id_etiqueta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `etiquetas_agrupadas`
--
ALTER TABLE `etiquetas_agrupadas`
  MODIFY `id_etiqueta_agrupada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de la tabla `etiquetas_agrupadas_proyectos`
--
ALTER TABLE `etiquetas_agrupadas_proyectos`
  MODIFY `id_etiqueta_agrupada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `portafolios`
--
ALTER TABLE `portafolios`
  MODIFY `id_portafolio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id_proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `proyectos_agrupados_portafolio`
--
ALTER TABLE `proyectos_agrupados_portafolio`
  MODIFY `id_proyecto_agrupado_portafolio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`id_estado_categoria`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_usuario_comentario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_post_comentario`) REFERENCES `posts` (`id_post`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_3` FOREIGN KEY (`id_estado_comentario`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contenidos`
--
ALTER TABLE `contenidos`
  ADD CONSTRAINT `contenidos_ibfk_1` FOREIGN KEY (`id_post_contenido`) REFERENCES `posts` (`id_post`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD CONSTRAINT `etiquetas_ibfk_1` FOREIGN KEY (`id_categoria_etiqueta`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `etiquetas_agrupadas`
--
ALTER TABLE `etiquetas_agrupadas`
  ADD CONSTRAINT `etiquetas_agrupadas_ibfk_1` FOREIGN KEY (`id_etiqueta_etiquetas_agrupadas`) REFERENCES `etiquetas` (`id_etiqueta`) ON UPDATE CASCADE,
  ADD CONSTRAINT `etiquetas_agrupadas_ibfk_2` FOREIGN KEY (`id_post_etiquetas_agrupadas`) REFERENCES `posts` (`id_post`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `etiquetas_agrupadas_proyectos`
--
ALTER TABLE `etiquetas_agrupadas_proyectos`
  ADD CONSTRAINT `etiquetas_agrupadas_proyectos_ibfk_1` FOREIGN KEY (`id_proyecto_etiquetas_agrupadas`) REFERENCES `proyectos` (`id_proyecto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `etiquetas_agrupadas_proyectos_ibfk_2` FOREIGN KEY (`id_etiqueta_etiquetas_agrupadas`) REFERENCES `etiquetas` (`id_etiqueta`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `portafolios`
--
ALTER TABLE `portafolios`
  ADD CONSTRAINT `portafolios_ibfk_1` FOREIGN KEY (`id_usuario_portafolio`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_usuario_post`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`id_categoria_post`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`id_estado_post`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`id_categoria_proyecto`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE,
  ADD CONSTRAINT `proyectos_ibfk_2` FOREIGN KEY (`id_usuario_proyecto`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `proyectos_ibfk_3` FOREIGN KEY (`id_estado_proyecto`) REFERENCES `estado` (`id_estado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `proyectos_agrupados_portafolio`
--
ALTER TABLE `proyectos_agrupados_portafolio`
  ADD CONSTRAINT `proyectos_agrupados_portafolio_ibfk_1` FOREIGN KEY (`id_proyecto_proyectos_agrupados_portafolio`) REFERENCES `proyectos` (`id_proyecto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `proyectos_agrupados_portafolio_ibfk_2` FOREIGN KEY (`id_portafolio_proyectos_agrupados_portafolio`) REFERENCES `portafolios` (`id_portafolio`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol_usuario`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
