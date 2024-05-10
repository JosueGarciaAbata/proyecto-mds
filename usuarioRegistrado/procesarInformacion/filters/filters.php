<?php
require_once ("../../../procesarInformacion/conexion.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
    $estado_categoria = 1; // Visible, visitante
    $estado_post = 1; // Visible, visitante

    // Obtener las categorias para filtrar en posts
    if (isset($_POST["action"]) && $_POST["action"] == "getCategoriesPosts") {
        echo json_encode(getCategoriesPosts($conexion, $estado_categoria));
        exit;
    }

    // Obtener tags de una sola categoria
    if (
        isset($_POST['action']) && $_POST['action'] == "getOneCategoryTagsPost" &&
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
        echo json_encode(filterPostsByCategories($conexion, $_POST['categoriesData']));
        exit;
    }

    // Filtrado por categorias proyectos. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByCategoriesProjects" &&
        isset($_POST['categoriesData'])
    ) {
        echo json_encode(filterPostsByCategoriesProjects($conexion, $_POST['categoriesData']));
        exit;
    }

    // Filtrado por etiquetas post. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByTags" &&
        isset($_POST['categoryData']) && isset($_POST['tagsData'])
    ) {
        echo json_encode(filterPostByTags($conexion, $_POST['categoryData'], $_POST['tagsData']));
    }

    // Filtrado por etiquetas post. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByTagsProjects" &&
        isset($_POST['categoryData']) && isset($_POST['tagsData'])
    ) {
        echo json_encode(filterPostByTagsProjects($conexion, $_POST['categoryData'], $_POST['tagsData']));
    }

    // Filtrado por habilidades portafolios. Usuario registrado
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsBySkills" &&
        isset($_POST['skillsData'])
    ) {
        echo json_encode(filterPortfoliosBySkills($conexion, $_POST['skillsData']));
    }
}

/*
Getters
*/

// Trae todas las categorias
function getCategoriesPosts($conexion, $estado_categoria)
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
function filterPostsByCategories($conexion, $categories)
{
    $placeholders = str_repeat('?,', count($categories) - 1) . '?';

    $sql = "SELECT DISTINCT id_post, id_usuario_post, id_categoria_post, id_estado_post, titulo_post, ubicacion_imagen_post
        FROM posts
        WHERE id_categoria_post IN ($placeholders)";

    $types = str_repeat("i", count($categories));
    $params = $categories;

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    $postsArray = [];

    while ($row = $result->fetch_assoc()) {
        $postsArray[] = $row;
    }
    $stmt->close();
    return $postsArray;
}
// Traer los post que tengan esa etiqueta y categoria (solo una vez). Usuario registrado
function filterPostByTags($conexion, $category, $tags)
{
    // Tomar solo el primer valor del array de categorías
    $id_category = $category[0];

    $tagPlaceholders = implode(',', array_fill(0, count($tags), '?'));

    $sql = "SELECT DISTINCT p.id_post, p.titulo_post, p.contenido_textual_post, p.ubicacion_imagen_post, p.id_categoria_post, p.id_estado_post
            FROM posts p
            INNER JOIN etiquetas_agrupadas ea ON p.id_post = ea.id_post_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            WHERE p.id_categoria_post = ?
            AND e.id_etiqueta IN ($tagPlaceholders)";

    $stmt = $conexion->prepare($sql);

    $types = 'i' . str_repeat('s', count($tags));

    $params = array_merge([$id_category], $tags);

    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    $post_array = [];
    while ($row = $result->fetch_assoc()) {
        $post_array[] = $row;
    }

    $stmt->close();
    return $post_array;
}

/*
Filtrado para proyectos
*/

function filterPostsByCategoriesProjects($conexion, $categories)
{
    $placeholders = str_repeat('?,', count($categories) - 1) . '?';

    $sql = "SELECT DISTINCT id_proyecto, id_usuario_proyecto, id_categoria_proyecto, id_estado_proyecto, titulo_proyecto, ubicacion_imagen_proyecto
        FROM proyectos
        WHERE id_categoria_proyecto IN ($placeholders)";

    $types = str_repeat("i", count($categories));
    $params = $categories;

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    $postsArray = [];

    while ($row = $result->fetch_assoc()) {
        $postsArray[] = $row;
    }
    $stmt->close();
    return $postsArray;
}

function filterPostByTagsProjects($conexion, $category, $tags)
{
    // Tomar solo el primer valor del array de categorías
    $id_category = $category[0];

    $tagPlaceholders = implode(',', array_fill(0, count($tags), '?'));

    $sql = "SELECT DISTINCT p.id_proyecto, p.id_usuario_proyecto,  p.id_categoria_proyecto, p.id_estado_proyecto, p.titulo_proyecto, p.ubicacion_imagen_proyecto
            FROM proyectos p
            INNER JOIN etiquetas_agrupadas_proyectos ea ON p.id_proyecto = ea.id_proyecto_etiquetas_agrupadas
            INNER JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            WHERE p.id_categoria_proyecto = ?
            AND e.id_etiqueta IN ($tagPlaceholders)";

    $stmt = $conexion->prepare($sql);

    $types = 'i' . str_repeat('s', count($tags));

    $params = array_merge([$id_category], $tags);

    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    $post_array = [];
    while ($row = $result->fetch_assoc()) {
        $post_array[] = $row;
    }

    $stmt->close();
    return $post_array;
}

/*
Filtrado para portfolios
*/

function filterPortfoliosBySkills($conexion, $skills_data)
{
    // Tomar solo el primer valor del array de categorías
    $skills_placeholder = implode(',', array_fill(0, count($skills_data), '?'));

    $sql = "SELECT DISTINCT p.id_portafolio, p.id_usuario
            FROM portafolios p
            INNER JOIN portafolios_habilidades ph ON p.id_portafolio = ph.id_portafolio_habilidades
            INNER JOIN habilidades h ON ph.id_habilidad_portafolios_habilidades = h.id_habilidades
            WHERE h.id_habilidades IN ($skills_placeholder);";

    $stmt = $conexion->prepare($sql);

    foreach ($skills_data as $index => $skill) {
        $stmt->bind_param("i", $skill);
    }

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultados
    $result = $stmt->get_result();

    // Obtener los portafolios
    $portfolios = $result->fetch_all(MYSQLI_ASSOC);

    // Cerrar la consulta
    $stmt->close();

    return $portfolios;
}