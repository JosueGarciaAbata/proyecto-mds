<?php
require_once ("../../../procesarInformacion/conexion.php");

// Obtener el id de la sesion para filtrar en funcion del usaurio actual
session_start();

// Si no está definido un usuario, se le asigna un valor -1
if (!isset($_SESSION['user_id'])) {
    $id_usario = -1;
} else {
    // Si esta definido un usuario, se escoge el id de la sesion
    $id_usuario = $_SESSION['user_id'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
    $estado_categoria = 1; // Visible, visitante
    $estado_post = 1; // Visible, visitante

    // Obtener las categorias para filtrar en posts
    if (isset($_POST["action"]) && $_POST["action"] == "getCategories") {
        echo json_encode(getCategories($conexion, $estado_categoria));
        exit;
    }

    // Obtener tags de una sola categoria
    if (
        isset($_POST['action']) && $_POST['action'] == "getOneCategoryTags" &&
        isset($_POST['categoryData'])
    ) {
        echo json_encode(getOneCategoryTags($conexion, $_POST['categoryData'], $estado_post));
    }

    // Obtener habilidades para portafolios
    if (isset($_POST["action"]) && $_POST["action"] == "getSkillsPortfolios") {
        echo json_encode(getSkillsPortfolios($conexion));
        exit;
    }

    // Filtrado por categorias post. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByCategories" &&
        isset($_POST['categoriesData'])
    ) {
        echo json_encode(filterPostsByCategories($conexion, $_POST['categoriesData'], $id_usuario));
        exit;
    }

    // Filtrado por categorias proyectos. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterProjectsByCategories" &&
        isset($_POST['categoriesData'])
    ) {
        echo json_encode(filterProjectsByCategories($conexion, $_POST['categoriesData'], $id_usuario));
        exit;
    }

    // Filtrado por etiquetas post. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByTags" &&
        isset($_POST['categoryData']) && isset($_POST['tagsData'])
    ) {
        echo json_encode(filterPostByTags($conexion, $_POST['categoryData'], $_POST['tagsData'], $id_usuario));
    }

    // Filtrado por etiquetas post. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterProjectsByTags" &&
        isset($_POST['categoryData']) && isset($_POST['tagsData'])
    ) {
        echo json_encode(filterProjectsByTags($conexion, $_POST['categoryData'], $_POST['tagsData'], $id_usuario));
    }

    // Filtrado por habilidades portafolios. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsBySkills" &&
        isset($_POST['skillsData'])
    ) {
        echo json_encode(filterPortfoliosBySkills($conexion, $_POST['skillsData'], $id_usuario));
    }

    // Filtrado para visitantes: posts, proyectos y portafolios

    // Posts
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByCategoriesVisitor" &&
        isset($_POST['categoriesData'])
    ) {
        echo json_encode(filterPostsByCategoriesVisitor($conexion, $_POST['categoriesData']));
    }

    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByTagsVisitor" &&
        isset($_POST['categoryData']) && isset($_POST['tagsData'])
    ) {
        echo json_encode(filterPostByTagsVisitor($conexion, $_POST['categoryData'], $_POST['tagsData']));
    }

    // Proyecots
    if (
        isset($_POST['action']) && $_POST['action'] == "filterProjectsByCategoriesVisitor" &&
        isset($_POST['categoriesData'])
    ) {
        echo json_encode(filterProjectsByCategoriesVisitor($conexion, $_POST['categoriesData']));
    }

    if (
        isset($_POST['action']) && $_POST['action'] == "filterProjectsByTagsVisitor" &&
        isset($_POST['categoryData']) && isset($_POST['tagsData'])
    ) {
        echo json_encode(filterProjectstByTagsVisitor($conexion, $_POST['categoryData'], $_POST['tagsData']));
    }

    if (
        isset($_POST['action']) && $_POST['action'] == "filterPortfoliosBySkillsVisitor" &&
        isset($_POST['skillsData'])
    ) {
        echo json_encode(filterPortfoliosBySkillsVisitor($conexion, $_POST['skillsData']));
    }
}

/*
Getters
*/

// Trae todas las categorias
function getCategories($conexion, $estado_categoria)
{
    $sql = "SELECT id_categoria, id_estado_categoria, nombre_categoria FROM categorias WHERE id_estado_categoria = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $estado_categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    $categoriesArray = [];

    while ($row = $result->fetch_assoc()) {
        $categoriesArray[] = $row;
    }
    $stmt->close();
    return $categoriesArray;
}
// Trae los tags de una categoria
function getOneCategoryTags($conexion, $id_category, $estado_post)
{
    $sql = "SELECT id_etiqueta, nombre_etiqueta 
            FROM etiquetas 
            WHERE id_categoria_etiqueta = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param('i', $id_category);

    $stmt->execute();

    $resultados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return $resultados;
}

function getSkillsPortfolios($conexion)
{
    $sql = "SELECT id_habilidades, nombre_habilidades, tipo_habilidades 
            FROM habilidades";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $categoriesArray = [];

    while ($row = $result->fetch_assoc()) {
        $categoriesArray[] = $row;
    }
    $stmt->close();
    return $categoriesArray;
}

/*
Filtrado para posts
*/

// Trae los post en base a las categorias. Usuario registrado
function filterPostsByCategories($conexion, $categories, $id_usuario)
{
    // Preparar los marcadores de posición para los IDs de categoría
    $placeholders = str_repeat('?,', count($categories) - 1) . '?';

    // Consulta SQL para filtrar posts por categorías y usuario
    $sql = "SELECT DISTINCT id_post, id_usuario_post, id_categoria_post, id_estado_post, titulo_post, ubicacion_imagen_post
        FROM posts
        WHERE id_usuario_post = ? AND id_categoria_post IN ($placeholders)";

    // Crear una cadena de tipos de parámetros para bind_param
    $types = 'i' . str_repeat("i", count($categories));

    // Los parámetros de la consulta incluyen el ID de usuario seguido de los IDs de categoría
    $params = array_merge([$id_usuario], $categories);

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$params);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los posts filtrados
    $postsArray = [];

    // Iterar sobre el resultado y almacenar los posts en el array
    while ($row = $result->fetch_assoc()) {
        $postsArray[] = $row;
    }

    // Cerrar la consulta
    $stmt->close();

    // Devolver el array de posts filtrados
    return $postsArray;
}

// Traer los post que tengan esa etiqueta y categoria (solo una vez). Usuario registrado
function filterPostByTags($conexion, $category, $tags, $id_usuario)
{
    // Tomar solo el primer valor del array de categorías
    $id_category = $category[0];

    // Crear marcadores de posición para los IDs de etiqueta
    $tagPlaceholders = implode(',', array_fill(0, count($tags), '?'));

    // Consulta SQL para filtrar los posts por etiquetas y usuario
    $sql = "SELECT DISTINCT p.id_post, p.id_usuario_post, p.titulo_post, p.contenido_textual_post, p.ubicacion_imagen_post, p.id_categoria_post, p.id_estado_post
            FROM posts p
            INNER JOIN etiquetas_agrupadas ea ON p.id_post = ea.id_post_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            WHERE p.id_usuario_post = ? AND p.id_categoria_post = ?
            AND e.id_etiqueta IN ($tagPlaceholders)";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Crear una cadena de tipos de parámetros para bind_param
    $types = 'ii' . str_repeat('s', count($tags)); // Agregar 'ii' para los parámetros de ID de usuario y categoría

    // Los parámetros incluyen el ID de usuario, el ID de categoría y los IDs de etiqueta
    $params = array_merge([$id_usuario, $id_category], $tags);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$params);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los posts filtrados
    $post_array = [];

    // Iterar sobre el resultado y almacenar los posts en el array
    while ($row = $result->fetch_assoc()) {
        $post_array[] = $row;
    }

    // Cerrar la consulta
    $stmt->close();

    // Devolver el array de posts filtrados
    return $post_array;
}

/*
Filtrado para proyectos
*/

function filterProjectsByCategories($conexion, $categories, $id_usuario)
{
    // Preparar los marcadores de posición para los IDs de categoría
    $placeholders = str_repeat('?,', count($categories) - 1) . '?';

    // Consulta SQL para filtrar proyectos por categorías y usuario
    $sql = "SELECT DISTINCT id_proyecto, id_usuario_proyecto, id_categoria_proyecto, id_estado_proyecto, titulo_proyecto, ubicacion_imagen_proyecto
        FROM proyectos
        WHERE id_usuario_proyecto = ? AND id_categoria_proyecto IN ($placeholders)";

    // Crear una cadena de tipos de parámetros para bind_param
    $types = 'i' . str_repeat("i", count($categories));

    // Los parámetros incluyen el ID de usuario seguido de los IDs de categoría
    $params = array_merge([$id_usuario], $categories);

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$params);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los proyectos filtrados
    $projectsArray = [];

    // Iterar sobre el resultado y almacenar los proyectos en el array
    while ($row = $result->fetch_assoc()) {
        $projectsArray[] = $row;
    }

    // Cerrar la consulta
    $stmt->close();

    // Devolver el array de proyectos filtrados
    return $projectsArray;
}

function filterProjectsByTags($conexion, $category, $tags, $id_usuario)
{
    // Tomar solo el primer valor del array de categorías
    $id_category = $category[0];

    // Crear marcadores de posición para los IDs de etiqueta
    $tagPlaceholders = implode(',', array_fill(0, count($tags), '?'));

    // Consulta SQL para filtrar proyectos por etiquetas y usuario
    $sql = "SELECT DISTINCT p.id_proyecto, p.id_usuario_proyecto,  p.id_categoria_proyecto, p.id_estado_proyecto, p.titulo_proyecto, p.ubicacion_imagen_proyecto
            FROM proyectos p
            INNER JOIN etiquetas_agrupadas_proyectos ea ON p.id_proyecto = ea.id_proyecto_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            WHERE p.id_usuario_proyecto = ? AND p.id_categoria_proyecto = ?
            AND e.id_etiqueta IN ($tagPlaceholders)";

    // Crear una cadena de tipos de parámetros para bind_param
    $types = 'ii' . str_repeat('s', count($tags)); // Agregar 'ii' para los parámetros de ID de usuario y categoría

    // Los parámetros incluyen el ID de usuario, el ID de categoría y los IDs de etiqueta
    $params = array_merge([$id_usuario, $id_category], $tags);

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$params);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los proyectos filtrados
    $project_array = [];

    // Iterar sobre el resultado y almacenar los proyectos en el array
    while ($row = $result->fetch_assoc()) {
        $project_array[] = $row;
    }

    // Cerrar la consulta
    $stmt->close();

    // Devolver el array de proyectos filtrados
    return $project_array;
}

/*
Filtrado para portafolios
*/

function filterPortfoliosBySkills($conexion, $idHabilidades, $id_usuario)
{
    $placeholders = str_repeat("?,", count($idHabilidades) - 1) . "?";
    $sql = "SELECT p.* 
            FROM portafolios p
            JOIN portafolios_habilidades ph ON p.id_portafolio = ph.id_portafolio_portafolios_habilidades
            WHERE p.id_usuario_portafolio = ? AND ph.id_habilidad_portafolios_habilidades IN ($placeholders)";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Crear una cadena de tipos de parámetros para bind_param
    $types = 'i' . str_repeat('i', count($idHabilidades)); // Agregar 'i' para el parámetro de ID de usuario

    // Los parámetros incluyen el ID de usuario y los IDs de habilidades
    $params = array_merge([$id_usuario], $idHabilidades);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$params);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los portafolios filtrados
    $portfoliosArray = [];

    // Iterar sobre el resultado y almacenar los portafolios en el array
    while ($row = $result->fetch_assoc()) {
        $portfoliosArray[] = $row;
    }

    // Devolver el array de portafolios filtrados
    return $portfoliosArray;
}

/*
Filtrado para los visitantes
*/

function filterPostsByCategoriesVisitor($conexion, $categories)
{
    // Preparar los marcadores de posición para los IDs de categoría
    $placeholders = str_repeat('?,', count($categories) - 1) . '?';

    // Consulta SQL para filtrar posts por categorías y usuario
    $sql = "SELECT DISTINCT id_post, id_usuario_post, id_categoria_post, id_estado_post, titulo_post, ubicacion_imagen_post
        FROM posts
        WHERE id_categoria_post IN ($placeholders)";

    // Crear una cadena de tipos de parámetros para bind_param
    $types = str_repeat("i", count($categories));

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$categories);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los posts filtrados
    $postsArray = [];

    // Iterar sobre el resultado y almacenar los posts en el array
    while ($row = $result->fetch_assoc()) {
        $postsArray[] = $row;
    }

    // Cerrar la consulta
    $stmt->close();

    // Devolver el array de posts filtrados
    return $postsArray;
}

function filterPostByTagsVisitor($conexion, $category, $tags)
{
    // Tomar solo el primer valor del array de categorías
    $id_category = $category[0];

    // Crear marcadores de posición para los IDs de etiqueta
    $tagPlaceholders = implode(',', array_fill(0, count($tags), '?'));

    // Consulta SQL para filtrar los posts por etiquetas
    $sql = "SELECT DISTINCT p.id_post, p.id_usuario_post, p.titulo_post, p.contenido_textual_post, p.ubicacion_imagen_post, p.id_categoria_post, p.id_estado_post
            FROM posts p
            INNER JOIN etiquetas_agrupadas ea ON p.id_post = ea.id_post_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            WHERE p.id_categoria_post = ?
            AND e.id_etiqueta IN ($tagPlaceholders)";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Crear una cadena de tipos de parámetros para bind_param
    $types = 'i' . str_repeat('s', count($tags)); // Agregar 'i' para el parámetro de ID de categoría

    // Los parámetros incluyen el ID de categoría y los IDs de etiqueta
    $params = array_merge([$id_category], $tags);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$params);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los posts filtrados
    $post_array = [];

    // Iterar sobre el resultado y almacenar los posts en el array
    while ($row = $result->fetch_assoc()) {
        $post_array[] = $row;
    }

    // Cerrar la consulta
    $stmt->close();

    // Devolver el array de posts filtrados
    return $post_array;
}

function filterProjectsByCategoriesVisitor($conexion, $categories)
{
    // Preparar los marcadores de posición para los IDs de categoría
    $placeholders = str_repeat('?,', count($categories) - 1) . '?';

    // Consulta SQL para filtrar proyectos por categorías
    $sql = "SELECT DISTINCT id_proyecto, id_usuario_proyecto, id_categoria_proyecto, id_estado_proyecto, titulo_proyecto, ubicacion_imagen_proyecto
        FROM proyectos
        WHERE id_categoria_proyecto IN ($placeholders)";

    // Crear una cadena de tipos de parámetros para bind_param
    $types = str_repeat("i", count($categories));

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$categories);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los proyectos filtrados
    $projectsArray = [];

    // Iterar sobre el resultado y almacenar los proyectos en el array
    while ($row = $result->fetch_assoc()) {
        $projectsArray[] = $row;
    }

    // Cerrar la consulta
    $stmt->close();

    // Devolver el array de proyectos filtrados
    return $projectsArray;
}

function filterProjectstByTagsVisitor($conexion, $category, $tags)
{
    // Tomar solo el primer valor del array de categorías
    $id_category = $category[0];

    // Crear marcadores de posición para los IDs de etiqueta
    $tagPlaceholders = implode(',', array_fill(0, count($tags), '?'));

    // Consulta SQL para filtrar proyectos por etiquetas
    $sql = "SELECT DISTINCT p.id_proyecto, p.id_usuario_proyecto,  p.id_categoria_proyecto, p.id_estado_proyecto, p.titulo_proyecto, p.ubicacion_imagen_proyecto
            FROM proyectos p
            INNER JOIN etiquetas_agrupadas_proyectos ea ON p.id_proyecto = ea.id_proyecto_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            WHERE p.id_categoria_proyecto = ?
            AND e.id_etiqueta IN ($tagPlaceholders)";

    // Crear una cadena de tipos de parámetros para bind_param
    $types = 'i' . str_repeat('s', count($tags)); // Agregar 'i' para el parámetro de ID de categoría

    // Los parámetros incluyen el ID de categoría y los IDs de etiqueta
    $params = array_merge([$id_category], $tags);

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param($types, ...$params);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los proyectos filtrados
    $project_array = [];

    // Iterar sobre el resultado y almacenar los proyectos en el array
    while ($row = $result->fetch_assoc()) {
        $project_array[] = $row;
    }

    // Cerrar la consulta
    $stmt->close();

    // Devolver el array de proyectos filtrados
    return $project_array;
}

function filterPortfoliosBySkillsVisitor($conexion, $idHabilidades)
{
    $placeholders = str_repeat("?,", count($idHabilidades) - 1) . "?";
    $sql = "SELECT p.* 
            FROM portafolios p
            JOIN portafolios_habilidades ph ON p.id_portafolio = ph.id_portafolio_portafolios_habilidades
            WHERE ph.id_habilidad_portafolios_habilidades IN ($placeholders)";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Crear una cadena de tipos de parámetros para bind_param
    $types = str_repeat('i', count($idHabilidades));

    // Vincular los parámetros
    $stmt->bind_param($types, ...$idHabilidades);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Array para almacenar los portafolios filtrados
    $portfoliosArray = [];

    // Iterar sobre el resultado y almacenar los portafolios en el array
    while ($row = $result->fetch_assoc()) {
        $portfoliosArray[] = $row;
    }

    // Devolver el array de portafolios filtrados
    return $portfoliosArray;
}
