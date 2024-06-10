<?php
require_once ("../conexion.php");

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


/*
Filtrado para los visitantes
*/

function filterPostsByCategoriesVisitor($conexion, $categories)
{
    // Preparar los marcadores de posición para los IDs de categoría
    $placeholders = str_repeat('?,', count($categories) - 1) . '?';

    // Consulta SQL para filtrar posts por categorías y usuario
    $sql = "SELECT DISTINCT posts.*, usuarios.*
        FROM posts
        JOIN usuarios on posts.id_usuario_post = usuarios.id_usuario
        WHERE posts.id_categoria_post IN ($placeholders)
        AND posts.id_estado_post = 1";

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
    $sql = "SELECT DISTINCT p.*, u.*
            FROM posts p
            INNER JOIN etiquetas_agrupadas ea ON p.id_post = ea.id_post_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            INNER JOIN usuarios u ON p.id_usuario_post = u.id_usuario
            WHERE p.id_categoria_post = ?
            AND e.id_etiqueta IN ($tagPlaceholders)
            AND p.id_estado_post = 1";

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
    $sql = "SELECT DISTINCT p.*, u.*
        FROM proyectos p
        JOIN usuarios u ON p.id_usuario_proyecto = u.id_usuario
        WHERE p.id_categoria_proyecto IN ($placeholders)
        AND p.id_estado_proyecto = 1";

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
    $sql = "SELECT DISTINCT p.*, u.*
            FROM proyectos p
            INNER JOIN etiquetas_agrupadas_proyectos ea ON p.id_proyecto = ea.id_proyecto_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            INNER JOIN usuarios u ON p.id_usuario_proyecto = u.id_usuario
            WHERE p.id_categoria_proyecto = ?
            AND e.id_etiqueta IN ($tagPlaceholders)
            AND p.id_estado_proyecto = 1";

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
