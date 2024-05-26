<?php

// Este archivo PHP asume que estás recibiendo datos en formato JSON
// Por lo tanto, necesitas decodificar esos datos antes de trabajar con ellos
header("Content-Type: application/json");
//vamos a hacer crud asi q necesitamos la conexion
require_once ('../../../procesarInformacion/conexion.php');
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        //  retorna un array, toca volverlo json, claro no los trae con los proyectos, pero se soluciona con un join a proyectos agrupados en cada iteracion para unirlo al array asociativo pertinente
        if(empty($_POST)){
            getPost($conexion);
        }else{
            //  getPost($conexion, $id);
            getPost($conexion);
        }
        break;
    case "POST":
        // print_r($_POST);
        if(empty($_POST)){
            http_response_code(30);
            echo json_encode(["status" => "BAD_REQUEST", "statusText" => "No existe informacion"]);
            exit();
        }
        $cosasAVer=[$_POST["id-post"],$_POST["content"]];
        if(contieneCaracteresEspeciales($conexion,$cosasAVer)){
            http_response_code(405);
            echo json_encode(["error" => "Los campos tienen valores inseguros"]);
            exit();
        }
        //saveComentary($conexion, $idPost, $contenidoComentario, $idUsuarioComentario=0)
        session_start();
        $usuarioId=empty($_SESSION)?-1:$_SESSION['user_id'];
        //  $_SESSION['user_id']
        saveComentary($conexion, $_POST["id-post"], $_POST["content"],$usuarioId);
        break;
    case "PUT":
        
        break;
    case "DELETE":
        if (isset($_GET['id-comentary'])) {
            $idPortafolio = $_GET['id-comentary'];
            // Asegúrate de que el id no esté vacío
            if (!empty($idPortafolio)) {
                // Aquí puedes procesar el id-portafolio
                deleteComentary($conexion, $idPortafolio);  
            } else {
                echo json_encode(["status" => "BAD_REQUEST", "statusText" => "id-comentary is empty"]);
            }
        } else {
            echo json_encode(["status" => "BAD_REQUEST", "statusText" => "Missing id-comentary"]);
            exit;
        }
        break;
        defaulft:
        // Si la petición no es POST, UPDATE,PUT o DELETE, devolvemos un error
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}




function contieneCaracteresEspeciales($conexion, $arrayTitulos) {
    foreach ($arrayTitulos as $titulo) {
        $tituloEscapado = $conexion->real_escape_string($titulo);
        // Comparar el título original con el escapado
        if ($titulo !== $tituloEscapado) {
            return true; 
        }
    }
    return false; // Ningún título contiene caracteres especiales
}

function saveComentary($conexion, $idPost, $contenidoComentario, $idUsuarioComentario=0)
{
    //  insertarlo
    // Preparar la consulta con el campo id_estado_comentario
    $sql="INSERT INTO comentarios (id_post_comentario, id_usuario_comentario, contenido_comentario) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    // Enlazar los parámetros
    $stmt->bind_param("iis", $idPost, $idUsuarioComentario, $contenidoComentario);
    // Ejecutar la consulta
    if (!$stmt->execute()) {
        echo json_encode(["status" => "BAD_REQUEST", "statusText" => $stmt->error]);
    }
    http_response_code(200);
    echo json_encode(["status" => "OK", "statusText" => "El comentario fue insertado con exito."],);
}
function updatePortafolio($conexion, $comentaryId, $content)
{
    
}

//function getPortafolio($conexion, $id = 0, $idHabilidades=0)
function getPost($conexion, $start = -1)
{
    $result_posts = array();
    $sql = "";
    if ($start !== -1) {
        $sql = "SELECT 
                    p.id_post, p.id_categoria_post, p.titulo_post, p.contenido_textual_post, 
                    p.ubicacion_imagen_post, p.fecha_creacion_post, u.nombre_usuario 
                FROM 
                    posts p 
                JOIN 
                    usuarios u 
                ON 
                    p.id_usuario_post = u.id_usuario
                WHERE 
                    p.id_estado_post = 1 AND p.id_post >= ? 
                ORDER BY 
                    p.id_post 
                LIMIT 15";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $start);
        $stmt->execute();
        $result_posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $sql = "SELECT 
                p.id_post, c.id_categoria,c.nombre_categoria, p.titulo_post, p.contenido_textual_post, 
                p.ubicacion_imagen_post, p.fecha_creacion_post, u.nombre_usuario 
            FROM 
                posts p 
            JOIN 
                usuarios u ON p.id_usuario_post = u.id_usuario
            JOIN 
                categorias c ON p.id_categoria_post=c.id_categoria
            WHERE 
                p.id_estado_post = 1 
                AND p.id_post >= (SELECT MIN(id_post) FROM posts WHERE id_estado_post = 1)
            ORDER BY 
                p.id_post 
            LIMIT 15";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $result_posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    if(empty($result_posts)){
        http_response_code(200);
        echo json_encode(["status" => "OK", "statusText" => "Post not exist"]);
        exit();
    }

    // Obtener comentarios y etiquetas para cada post, sin lo del index no vale, osea trabaja con una copia y cuando termina no se modifica en la referencia
    foreach ($result_posts as $index => $post) {
        $result_posts[$index]["comentarios"] = getVisibleComentaryByPostId($conexion, $post["id_post"]);
        $result_posts[$index]["etiquetas"] = getLabelName($conexion, $post["id_categoria"]);
    }
    //print_r($result_posts);
    http_response_code(200);
    echo json_encode($result_posts);
}

function getVisibleComentaryByPostId($conexion,$postId){
    $sql = "SELECT 
                c.id_comentario, c.id_usuario_comentario, 
                CASE 
                    WHEN c.id_usuario_comentario = 0 THEN '' 
                    ELSE u.nombre_usuario 
                END AS nombre_usuario, c.contenido_comentario 
                FROM 
                    comentarios c 
                LEFT JOIN 
                    usuarios u ON c.id_usuario_comentario = u.id_usuario
                WHERE 
                    c.id_post_comentario = ? AND c.id_estado_comentario = 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function getLabelName($conexion, $idCategoria)
{
    $sql = "SELECT nombre_etiqueta FROM etiquetas WHERE id_categoria_etiqueta = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $result_etiquetas = $stmt->get_result();
    // Obtener todas las etiquetas como un array asociativo
    $etiquetas = $result_etiquetas->fetch_all(MYSQLI_ASSOC);
    // Extraer los valores de la columna 'nombre_etiqueta' en un array
    $nombresEtiquetas = array_column($etiquetas, 'nombre_etiqueta');
    // Devolver las etiquetas como una cadena separada por comas
    return implode(", ", $nombresEtiquetas);
}

function deleteComentary($conexion, $id_portafolio) {
    http_response_code(200);
    echo json_encode(["status" => "OK", "statusText" => "Portafolio eliminado correctamente"]);
}

?>