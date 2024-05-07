<?php
require_once ("../../../procesarInformacion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    // Mostrar las etiquetas en funcion de la categoria
    if (isset($_POST["action"]) && $_POST["action"] == "changeCategory" && isset($_POST['category']) && !empty($_POST['category'])) {
        echo json_encode(getLabels($conexion, $_POST["category"]));
    }

    // Mostrar las habilidades
    if (isset($_POST["action"]) && $_POST["action"] == "getHabilities") {
        echo json_encode(getHabilities($conexion));
    }

    // Obtener post de acuerdo a los filtros
    if (
        isset($_POST['action']) && $_POST["action"] == "filterPosts" &&
        isset($_POST['category']) && !empty($_POST['category']) &&
        isset($_POST['tags']) && !empty($_POST['tags'])
    ) {
        echo json_encode(getPosts($conexion, $_POST['category'], $_POST['tags']));
    }

    // Obtener los proyectos de acuerdo a los filtros
    if (
        isset($_POST['action']) && $_POST['action'] == "filterProjects" &&
        isset($_POST['category']) && !empty($_POST['category'])
    ) {
        echo json_encode(getProjects($conexion, $_POST['category']));
    }

    // Obtener los portafolios de acuerdo a las habilidades
    if (
        isset($_POST['action']) && $_POST['action'] == "filterPortfolio" &&
        isset($_POST['habilities'])
    ) {
        echo json_encode(getPortfolios($_POST['habilities'], $conexion));
    }
}

function getLabels($conexion, $idCategory)
{
    $sql = "SELECT nombre_etiqueta, id_etiqueta FROM etiquetas WHERE id_categoria_etiqueta=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idCategory);
    $stmt->execute();
    $result = $stmt->get_result();

    $labelsArray = [];

    while ($row = $result->fetch_assoc()) {
        $labelsArray[] = $row;
    }
    $stmt->close();
    return $labelsArray;
}

function getHabilities($conexion)
{
    $sql = "SELECT id_habilidades, nombre_habilidades, tipo_habilidades FROM habilidades";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $habilitiesArray = [];

    while ($row = $result->fetch_assoc()) {
        $habilitiesArray[] = $row;
    }
    $stmt->close();
    return $habilitiesArray;
}

// El post solo tiene el id de categoria. Entonces lo que tengo que hacer es
// hacer un join, de tal forma que ese id de categoria y los id de etiquetas dados, deben coincidir
// en la tabla de etiquetas.
function getPosts($conexion, $idCategory, $tagsSelected)
{
    // Crear una cadena de marcadores de posición para tagsSelected
    // Basicamente si tengo 3 valores tendria ?, ?, ?
    $placeholders = str_repeat('?,', count($tagsSelected) - 1) . '?';

    // Consulta SQL con marcadores de posición
    $sql = "SELECT DISTINCT p.* 
            FROM posts p
            JOIN etiquetas_agrupadas ea ON p.id_post = ea.id_post_etiquetas_agrupadas
            JOIN etiquetas e ON ea.id_etiqueta_etiquetas_agrupadas = e.id_etiqueta
            JOIN categorias c ON p.id_categoria_post = c.id_categoria
            WHERE p.id_categoria_post = ? 
            AND e.id_etiqueta IN ($placeholders)";

    $stmt = $conexion->prepare($sql);

    // Unir los valores de tagsSelected a la consulta
    $params = array_merge([$idCategory], $tagsSelected);

    // Vincular los parámetros a la consulta preparada
    $stmt->bind_param(str_repeat('i', count($params)), ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    $post_array = [];
    while ($row = $result->fetch_assoc()) {
        $post_array[] = $row;
    }

    $stmt->close();
    return $post_array;
}

// Para poder obtener los portafolios simplemente debo traer aquellos que contenga la categoria dada.
function getProjects($conexion, $idCategory)
{
    $sql = "SELECT p.* 
            FROM proyectos p
            JOIN categorias c ON p.id_categoria_proyecto = c.id_categoria
            WHERE p.id_categoria_proyecto = ?";

    $stmt_userData = $conexion->prepare($sql);
    $stmt_userData->bind_param('i', $idCategory);
    $stmt_userData->execute();

    $result_userData = $stmt_userData->get_result();

    if ($result_userData->num_rows < 1) {
        return [];
    }

    while ($row = $result_userData->fetch_assoc()) {
        $proyectos[] = $row;
    }

    return $proyectos;
}

function getPortfolios($conexion, $idHabilidades)
{
    $placeholders = str_repeat("?,", count($idHabilidades) - 1) . "?";
    $sql = "SELECT p.* 
            FROM portafolios p
            JOIN portafolios_habilidades ph ON p.id_portafolio = ph.idPortafolio_portafolios_habilidades
            WHERE ph.idHabilidad_portafolios_habilidades IN ($placeholders)";

    $stmt_userData = $conexion->prepare($sql);
    $stmt_userData->bind_param(str_repeat("i", count($idHabilidades)), ...$idHabilidades);
    $stmt_userData->execute();

    $result_userData = $stmt_userData->get_result();

    if ($result_userData->num_rows < 1) {
        return [];
    }

    while ($row = $result_userData->fetch_assoc()) {
        $portafolios[] = $row;
    }

    return $portafolios;
}