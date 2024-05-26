<?php

// Este archivo PHP asume que estás recibiendo datos en formato JSON
// Por lo tanto, necesitas decodificar esos datos antes de trabajar con ellos
header("Content-Type: application/json");
// conexion a la base de datos
require_once ('../../../procesarInformacion/conexion.php');
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

//  se mira por que metodo llego la peticion
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (empty($_GET)) {
            exit();
        }
        if(empty($_GET["type"])){
            getSkills($conexion, $_GET['id-skill']);
        }else{
            getSkills($conexion, $_GET['id-skill'], $_GET["type"]);
        }
        break;
    case "POST":
        print_r($_POST["skills"]);
        saveSkills($conexion, $_POST["skills"]);       
        break;
    case "DELETE":
        if (!empty($_GET['id-skill'])) {
            $idPortafolio = $_GET['id-portafolio'];
            // Asegúrate de que el id no esté vacío
            if (!empty($idPortafolio)) {
                // Aquí puedes procesar el id-portafolio
                deletePortafolio($conexion, $idPortafolio);
            } else {
                echo json_encode(["status" => "BAD_REQUEST", "statusText" => "id-portafolio is empty"]);
            }
        } else {
            echo json_encode(["status" => "BAD_REQUEST", "statusText" => "Missing id-portafolio"]);
            exit;
        }
        break;
        defaulft:
        // Si la petición no es POST, UPDATE,PUT o DELETE, devolvemos un error
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}


function saveSkills($conexion, $skills){
    if(!empty($skills)){
        $idsSkills=array();
        if(!empty($skills["tecnicas"])){
            $idsSkills["tecnicas"]=insertarHabilidades($conexion, $skills["tecnicas"], "tecnica");
        }
        if(!empty($skills["sociales"])){
            $idsSkills["sociales"]=insertarHabilidades($conexion, $skills["sociales"], "social");
        }
        http_response_code(200);
        echo json_encode(["status" => "OK", "statusText" => "Habilidad guardada correctamente","ids"=>$idsSkills]);
    } else {
        http_response_code(405);
        echo json_encode(["status" => "BAD_REQUEST", "statusText" => "No existen habilidades a guardar"]);
    }
}

function insertarHabilidades($conexion, $habilidades, $tipo) {
    $identificadores = array();
    $tipo = ($tipo === "tecnica") ? 1 : 0;
    print_r($habilidades);

    // Iniciar una transacción
    $conexion->begin_transaction();

    try {
        foreach ($habilidades as $habilidad) {
            $sql = "INSERT INTO habilidades (nombre_habilidades, tipo_habilidades) VALUES (?,?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ss", $habilidad, $tipo);
            if ($stmt->execute()) {
                $identificadores[] = $conexion->insert_id;
            } else {
                throw new Exception("Error en la ejecución de la consulta: " . $stmt->error);
            }
        }
        // Confirmar la transacción
        $conexion->commit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollback();
        die($e->getMessage());
    }

    return $identificadores;
}