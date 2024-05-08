<?php
require_once ("../../../procesarInformacion/conexion.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
    $estado_categoria = 1; // Visible, visitante
    $estado_post = 1; // Visible, visitante

    // Mostrar las categorias para filtrar en posts
    if (isset($_POST["action"]) && $_POST["action"] == "getCategoriesPosts") {
        echo json_encode(getCategoriesPosts($conexion, $estado_categoria));
        exit;
    }

    // Mostrar las etiquetas para filtrar en posts
    if (
        isset($_POST['action']) && $_POST['action'] == "getTagsPosts" &&
        isset($_POST['categoriesData'])
    ) {
        echo json_encode(getTagsPosts($conexion, $_POST['categoriesData']));
        exit;
    }

    // Filtrado por categorias post
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByCategories" &&
        isset($_POST['categoriesData'])
    ) {
        echo json_encode(getPostsCategories($conexion, $_POST['categoriesData'], $estado_post));
        exit;
    }

    // Filtrado por etiquetas post
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPostsByTags" &&
        isset($_POST['tagsData']) && isset($_POST['categoriesData'])
    ) {
        echo json_encode(getPostsTags($conexion, $_POST['categoriesData'], $_POST['tagsData'], $estado_post));
        exit;
    }

}

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

function getTagsPosts($conexion, $id_categories)
{
    $placeholders = implode(',', array_fill(0, count($id_categories), '?'));

    $sql = "SELECT id_etiqueta, id_categoria_etiqueta, nombre_etiqueta 
            FROM etiquetas
            WHERE etiquetas.id_categoria_etiqueta IN ($placeholders)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($id_categories)), ...$id_categories);
    $stmt->execute();
    $result = $stmt->get_result();

    $labelsArray = [];

    while ($row = $result->fetch_assoc()) {
        $labelsArray[] = $row;
    }
    $stmt->close();
    return $labelsArray;
}

function getPostsCategories($conexion, $categories, $estado_post)
{
    $placeholders = str_repeat('?,', count($categories) - 1) . '?';

    $sql = "SELECT DISTINCT id_post, id_usuario_post, id_categoria_post, id_estado_post, titulo_post, ubicacion_imagen_post
        FROM posts
        WHERE id_categoria_post IN ($placeholders) AND id_estado_post = ?";

    $types = str_repeat("i", count($categories)) . 'i';
    $params = array_merge($categories, [$estado_post]);

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

function getPostsTags($conexion, $cagories, $tags, $estado_post)
{
    $categoryPlaceholders = str_repeat('?,', count($cagories) - 1) . '?';
    $tagPlaceholders = str_repeat('?,', count($tags) - 1) . '?';

    $sql = "SELECT DISTINCT p.* 
            FROM posts p
            JOIN etiquetas_agrupadas ea ON p.id_post = ea.id_post_etiquetas_agrupadas
            JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            JOIN categorias c ON p.id_categoria_post = c.id_categoria
            WHERE p.id_categoria_post IN ($categoryPlaceholders) 
            AND e.id_etiqueta IN ($tagPlaceholders)
            AND p.id_estado_post = ?";

    $stmt = $conexion->prepare($sql);

    $params = array_merge($cagories, $tags, [$estado_post]);

    $types = str_repeat('i', count($cagories)) . str_repeat('i', count($tags)) . 'i';

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