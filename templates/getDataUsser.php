<?php
header("Content-Type: application/json");
require_once ('../procesarInformacion/conexion.php');
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if (!empty($_GET['id-portfolio'])) {
    getUserData($conexion, $_GET['id-portfolio']);
}else{
    http_response_code(400);
    echo json_encode(["status" => "BAD_REQUEST", "statusText" => "Portfolio no exist"]);
}

function getUserData($conexion, $id) {
    $sql = 'SELECT 
                u.nombre_usuario, u.correo_electronico_usuario, 
                p.sobre_mi_portafolio, p.mensaje_bienvenida_portafolio, 
                p.educacion_portafolio, p.fecha_modificacion_portafolio
            FROM 
                proyectos_agrupados_portafolio AS pa
            JOIN
                portafolios p ON pa.id_portafolio_proyectos_agrupados_portafolio = p.id_portafolio
            JOIN 
                usuarios u ON u.id_usuario = p.id_usuario_portafolio
            WHERE
                p.id_portafolio = ?
            LIMIT 1';

    if ($stmt_projectData = $conexion->prepare($sql)) {
        $stmt_projectData->bind_param("s", $id);
        $stmt_projectData->execute();
        $result_projectData = $stmt_projectData->get_result()->fetch_assoc();

        if ($result_projectData) {
            http_response_code(200);
            echo json_encode($result_projectData);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "NOT_FOUND", "statusText" => "No projects found for this portfolio"]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["status" => "ERROR", "statusText" => "Failed to prepare the SQL statement"]);
    }
}
