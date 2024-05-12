<?php

// Este archivo PHP asume que estás recibiendo datos en formato JSON
// Por lo tanto, necesitas decodificar esos datos antes de trabajar con ellos
header("Content-Type: application/json");
//vamos a hacer crud asi q necesitamos la conexion
require_once ('../../../procesarInformacion/conexion.php');
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
//  array asociativo con la respuesta
$data = json_decode(file_get_contents('php://input'), true);
// Verificar si la petición es POST
//  print_r($data);
//  $response = [];
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        //  retorna un array, toca volverlo json
        $response = getPortafolio($conexion, $data["id"], $data["habilidades"]);
        break;
    case "POST":
        //  print_r($_POST);
        $titulo = $_POST['titulo'];
        $mensaje = $_POST['mensaje'];
        $estudios = $_POST['estudios'];
        $sobreMi = $_POST['sobreMi'];
        //$fotoP = $_FILES['fotoP'];
        //$cv = $_FILES['cv'];
        $habilidadesTecnicas = $_POST['habilidadesTecnicas'];
        $habilidadesSociales = $_POST['habilidadesSociales'];
        $proyectos = $_POST['proyectos'];
        $dataBasic=[$titulo,$mensaje,$estudios,$sobreMi];
        $cosasAVer=array_merge($dataBasic,$habilidadesTecnicas,$habilidadesSociales);
/*
        if(contieneCaracteresEspeciales($conexion,$titulo)){
            http_response_code(405);
            echo json_encode(["error" => "Los campos tienen valores inseguros"]);
            exit();
        }
*/
        savePortafolio($conexion, $titulo, $habilidadesTecnicas, $habilidadesSociales, $estudios, $sobreMi, $mensaje,$proyectos);
        break;
    case "PUT":
        //$response = updatePortafolio();
        break;
    case "DELETE":
       // $response = deletePortafolio();
        break;
        defaulft:
        // Si la petición no es POST, UPDATE,PUT o DELETE, devolvemos un error
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
//  print_r($response);


function getUserName($conexion, $id)
{
    $sql = "SELECT nombre_usuario, carpeta_usuario, ubicacion_foto_perfil_usuario FROM usuarios WHERE id_usuario = ?";
    $stmt_userData = $conexion->prepare($sql);
    $stmt_userData->bind_param("s", $id);
    $stmt_userData->execute();
    $result_userData = $stmt_userData->get_result();
    if ($result_userData->num_rows < 1) {
        return false;
    }
    // Obtener los valores de la consulta
    $userData = $result_userData->fetch_assoc();
    $nombreUsuario = $userData['nombre_usuario'];
    $carpetaUsuario = $userData['carpeta_usuario'];
    $ubicacionFotoPerfilUsuario = $userData['ubicacion_foto_perfil_usuario'];
    // Retornar los valores
    return array(
        'nombre' => $nombreUsuario,
        'carpeta' => $carpetaUsuario,
        'fotoPerfil' => $ubicacionFotoPerfilUsuario
    );
}
function obtenerCarpetaUsuario($conexion, $userId){
    $dataUser=getUserName($conexion, $userId);
    return $dataUser["carpeta"].'/portafolio/';
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

function savePortafolio($conexion, $titulo, $habT, $habS, $estudios, $sobreMi, $mensaje, $proyectos)
{
    session_start();
    $userId=$_SESSION['user_id'];
/*
    $requiredInputs = ['userId', 'titulo', 'habT', 'habS', 'estudios', 'sobreMi', 'mensaje', 'foto-perfil', 'cv'];
    
    foreach ($requiredInputs as $input) {
        if (empty($$input)) {
            // Devolver un error si falta algún campo requerido
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
            exit();
        }
    }
    
*/
/*
    // Escapar valores para evitar inyección SQL
    $titulo = $conexion->real_escape_string($titulo);
    $estudios = $conexion->real_escape_string($estudios);
    $sobreMi = $conexion->real_escape_string($sobreMi);
    $mensaje = $conexion->real_escape_string($mensaje);
*/  
    // Subir archivos
    $datosUsuario=obtenerCarpetaUsuario($conexion, $userId);
    $rutaCarpetaUsuario = "../../../usersContent/" . $datosUsuario;
    //echo "La carpeta del usuario es:" .$rutaCarpetaUsuario;
    $identificadorUnico = uniqid();
    
    // Comprobar si la carpeta con el identificador único ya existe
    while (file_exists($rutaCarpetaUsuario ."/". $identificadorUnico)) {
        $identificadorUnico = uniqid();
    }
    $rutaCarpetaUsuario.="/$identificadorUnico";
    //print_r($_FILES);
    //print_r($_FILES["foto"]);
   
    $fotoP = subirArchivo("fotoP", $rutaCarpetaUsuario);
    $fotoF = subirArchivo("fotoF", $rutaCarpetaUsuario);
    $cv = subirArchivo("cv", $rutaCarpetaUsuario);

    //echo "La carpeta para el portafolio estara en: $rutaCarpetaUsuario\nLa foto de perfil esta en: $fotoP\nLa foto del fondo esta en: $fotoF\nY el cv en: $cv. \nLo que vamos a guardar es guardar la ruta de la carpeta($identificadorUnico) y el nombre de la foto(".$_FILES['fotoP']['name'].") y cv(".$_FILES['cv']['name']."),";
    // exit();
    // Insertar en la base de datos
    $sql = "INSERT INTO portafolios (id_usuario_portafolio, titulo_portafolio, educacion_portafolio, sobre_mi_portafolio, mensaje_bienvenida_portafolio, ubicacion_portafolio, foto_portafolio, fondo_portafolio, cv_portafolio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssss", $userId, $titulo, $estudios, $sobreMi, $mensaje, $identificadorUnico, $_FILES['fotoP']['name'],$_FILES['fotoF']['name'], $_FILES['cv']['name']);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(["error" => "Error al guardar el portafolio"]);
        exit();
    }
    //  consultar el ultimo portafolio de este usuario
    $lastInsertedId = $conexion->insert_id;

    // Insertar habilidades y proyectos
    $conexion->begin_transaction();
    $habilidades = array_merge($habT, $habS);
    //  print_r($proyectos);
    try {
        // Insertar habilidades
        $sql = "INSERT INTO portafolios_habilidades (id_portafolio_portafolios_habilidades, id_habilidad_portafolios_habilidades) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql);
        foreach ($habilidades as $habilidad) {
            $stmt->bind_param("ii", $lastInsertedId,$habilidad);
            $stmt->execute();
        }
        if(isset($proyectos)){
            // Insertar proyectos
            $sql = "INSERT INTO proyectos_agrupados_portafolio (id_portafolio_proyectos_agrupados_portafolio, id_proyecto_proyectos_agrupados_portafolio) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            foreach ($proyectos as $proy) {
                $stmt->bind_param("ii", $lastInsertedId, $proy);
                $stmt->execute();
            }
        }

        // Confirmar transacción
        $conexion->commit();
    } catch (Exception $e) {
        // Si hay algún error, revertir la transacción
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
        http_response_code(500);
        echo json_encode(["error" => "Error al los proyectos"]);
        exit();
    }
    http_response_code(200);
    echo json_encode(["message" => "Portafolio guardado correctamente"]);
}

function subirArchivo($nombreCampo, $rutaDestino)
{
    $archivoTemporal = $_FILES[$nombreCampo]["tmp_name"];
    $nombreArchivo = $_FILES[$nombreCampo]["name"];
    $extensionPermitida = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    $rutaDestinoArchivo = $rutaDestino . "/" . $nombreArchivo;

    // Si la carpeta de destino no existe, intenta crearla
    if (!file_exists($rutaDestino)) {
        if (!mkdir($rutaDestino, 0777, true)) {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear la carpeta de destino"]);
            exit();
        }
    }

    if (in_array($extensionPermitida, ["jpeg", "jpg", "png", "gif", "svg", "pdf"])) {
        if (!move_uploaded_file($archivoTemporal, $rutaDestinoArchivo)) {
            http_response_code(500);
            echo json_encode(["error" => "Error al subir el archivo $nombreCampo"]);
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "La extensión del archivo $nombreCampo no está permitida"]);
        exit();
    }

    return $rutaDestinoArchivo;
}

function updatePortafolio()
{
    // Recibir los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Procesar los datos recibidos (actualizar un registro existente, por ejemplo)
    // Aquí puedes realizar la lógica necesaria para actualizar los datos en tu base de datos
    // Por ejemplo, podrías usar los datos recibidos para actualizar un registro existente en una tabla

    // En este ejemplo, simplemente imprimimos los datos recibidos
    echo json_encode($data);
}
function deletePortafolio()
{
    $data = json_decode(file_get_contents("php://input"), true);

    // Procesar los datos recibidos (eliminar un registro existente, por ejemplo)
    // Aquí puedes realizar la lógica necesaria para eliminar los datos en tu base de datos
    // Por ejemplo, podrías usar los datos recibidos para eliminar un registro existente en una tabla

    // En este ejemplo, simplemente imprimimos los datos recibidos
    echo json_encode($data);
}
//function getPortafolio($conexion, $id = 0, $idHabilidades=0)
function getPortafolio($conexion, $id = 0, $idHabilidades = 0)
{
    //traer todos
    if ($id === 0 && $idHabilidades !== null) {

        //se puede consultar uno especificamente
        //  para formar n veces el "?,"
        $placeholders = str_repeat("?,", count($idHabilidades) - 1) . "?";
        //  vamos a traer todo el contenido de la tabla portafolios, siempre y cuando este entre los idHabilidadescorrespondientes en el array q se le pasa a este medoto
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
        $portafolios = [];
        while ($row = $result_userData->fetch_assoc()) {
            $portafolio = [
                'id_portafolio' => $row['id_portafolio'],
                'id_usuario_portafolio' => $row['id_usuario_portafolio'],
                // Añadir los demás campos necesarios de la tabla 'portafolios'
            ];
            $portafolios[] = $portafolio;
        }
        return $portafolios;
    } else {
        //traer solo 1
        $sql = "SELECT p.id_portafolio, p.id_usuario_portafolio, p.titulo_portafolio, p.educacion_portafolio, p.sobreMi_portafolio,
        p.mensajeBienvenida_portafolio, p.ubicacionPortafolio_portafolio, p.foto_portafolio, p.cv_portafolio 
        FROM portafolios p WHERE p.id_usuario_portafolio = ?";
        $stmt_userData = $conexion->prepare($sql);
        $stmt_userData->bind_param("s", $id);
        $stmt_userData->execute();
        $result_userData = $stmt_userData->get_result();
        if ($result_userData->num_rows < 1) {
            return [];
        }
        // Retornar los valores
        // $portafolios = [];
        // while ($row = $result_userData->fetch_assoc()) {
        //  Solo es 1 resultado
        $row = $result_userData->fetch_assoc();
        $portafolio = array(
            'id_portafolio' => $row['id_portafolio'],
            'id_usuario_portafolio' => $row['id_usuario_portafolio'],
            'titulo_portafolio' => $row['titulo_portafolio'],
            'educacion_portafolio' => $row['educacion_portafolio'],
            'sobreMi_portafolio' => $row['sobreMi_portafolio'],
            'mensajeBienvenida_portafolio' => $row['mensajeBienvenida_portafolio'],
            'carpetaPortafolio' => $row['ubicacionPortafolio_portafolio'],
            'foto_portafolio' => $row['foto_portafolio'],
            'ubicacionCv_portafolio' => $row['cv_portafolio']
        );
        $portafolio["habilidades"] = getHabilidadesById($conexion, $portafolio["id_portafolio"]);
        return $portafolio;
    }

    //http_response_code(200);
}





function getHabilidadesById($conexion, $idPortafolio)
{
    if (empty($idPortafolio)) {
        return [];
    }
    // Crear la consulta SQL para obtener la información de las habilidades
    $sql =
        "SELECT 
                ph.idHabilidad_portafolios_habilidades, h.nombre_habilidades, h.tipo_habilidades
            FROM 
                portafolios_habilidades ph
            JOIN 
                habilidades h ON ph.idHabilidad_portafolios_habilidades = h.id_habilidades
            WHERE 
                ph.idPortafolio_portafolios_habilidades = ?";
    $stmt = $conexion->prepare($sql);
    // Asignar los valores de los placeholders
    $stmt->bind_param("i", $idPortafolio);
    $stmt->execute();
    $result = $stmt->get_result();
    $conexion->close();
    // Obtener los datos de las habilidades
    $habilidades = [];
    $habilidadesTecnicas = [];
    $habilidadesSociales = [];
    while ($row = $result->fetch_assoc()) {
        $habilidad = [
            "id" => $row["idHabilidad_portafolios_habilidades"],
            "nombre" => $row["nombre_habilidades"],
            "tipo" => $row["tipo_habilidades"]
        ];
        if ($row["tipo_habilidades"] == 1) {
            $habilidadesTecnicas[] = $habilidad;
        } else {
            $habilidadesSociales[] = $habilidad;
        }

    }
    // Retornar las habilidades encontradass
    // print_r($habilidadesTecnicas);
    // print_r($habilidadesSociales);
    return array("habilidadesTecnicas" => $habilidadesTecnicas, "habilidadesSociales" => $habilidadesSociales);

}




?>