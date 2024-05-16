<?php

// Este archivo PHP asume que estás recibiendo datos en formato JSON
// Por lo tanto, necesitas decodificar esos datos antes de trabajar con ellos
header("Content-Type: application/json");
//vamos a hacer crud asi q necesitamos la conexion
require_once ('../../../procesarInformacion/conexion.php');
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
//  array asociativo con la respuesta

// Verificar si la petición es POST
//  print_r($data);
//  $response = [];
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        //  retorna un array, toca volverlo json, claro no los trae con los proyectos, pero se soluciona con un join a proyectos agrupados en cada iteracion para unirlo al array asociativo pertinente
        if(empty($_POST)){
            getPortafolio($conexion);
        }else{
            getPortafolio($conexion, $_POST["id"], $_POST["habilidades"]);
        }
        break;
    case "POST":
        $titulo = $_POST['titulo'];
        $mensaje = $_POST['mensaje'];
        $estudios = $_POST['estudios'];
        $sobreMi = $_POST['sobreMi'];
        $habilidadesTecnicas = $_POST['habilidadesTecnicas'];
        $habilidadesSociales = $_POST['habilidadesSociales'];
        $proyectos = $_POST['proyectos'];
        $dataBasic=[$titulo,$mensaje,$estudios,$sobreMi];
        $cosasAVer=array_merge($dataBasic,$habilidadesTecnicas,$habilidadesSociales);

        if(contieneCaracteresEspeciales($conexion,$titulo)){
            http_response_code(405);
            echo json_encode(["error" => "Los campos tienen valores inseguros"]);
            exit();
        }

        savePortafolio($conexion, $titulo, $habilidadesTecnicas, $habilidadesSociales, $estudios, $sobreMi, $mensaje,$proyectos);
        break;
    case "PUT":
        //  update por completo
        $titulo = $_POST['titulo'];
        $mensaje = $_POST['mensaje'];
        $estudios = $_POST['estudios'];
        $sobreMi = $_POST['sobreMi'];
        $habilidadesTecnicas = $_POST['habilidadesTecnicas'];
        $habilidadesSociales = $_POST['habilidadesSociales'];
        $proyectos = $_POST['proyectos'];
        $dataBasic=[$titulo,$mensaje,$estudios,$sobreMi];
        $cosasAVer=array_merge($dataBasic,$habilidadesTecnicas,$habilidadesSociales);

        if(contieneCaracteresEspeciales($conexion,$titulo)){
            http_response_code(405);
            echo json_encode(["error" => "Los campos tienen valores inseguros"]);
            exit();
        }

        updatePortafolio($conexion, $titulo, $habilidadesTecnicas, $habilidadesSociales, $estudios, $sobreMi, $mensaje,$proyectos);
        break;
    case "DELETE":
        deletePortafolio($conexion, $_POST['id-portafolio']);

        
        break;
        defaulft:
        // Si la petición no es POST, UPDATE,PUT o DELETE, devolvemos un error
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}


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

function updatePortafolio($conexion, $titulo, $habT, $habS, $estudios, $sobreMi, $mensaje, $proyectos)
{
    
}

//function getPortafolio($conexion, $id = 0, $idHabilidades=0)
function getPortafolio($conexion, $idHabilidades = 0)
{
    //traer todos
    session_start();
    if ($idHabilidades !== 0) {

        //  es un get general para todo portafolio visible por lo que se omite la revision del identificador del usuario


        //se puede consultar uno especificamente
        //  para formar n veces el "?,"
        //  $placeholders = str_repeat("?,", count($idHabilidades) - 1) . "?";
        //  vamos a traer todo el contenido de la tabla portafolios, siempre y cuando este entre los idHabilidadescorrespondientes en el array q se le pasa a este medoto
        $sql = "SELECT p.* 
                FROM portafolios p
                JOIN portafolios_habilidades ph ON p.id_portafolio = ph.id_portafolio_portafolios_habilidades
                WHERE ph.id_habilidad_portafolios_habilidades IN ( ? )";

        $stmt_userData = $conexion->prepare($sql);
        $stmt_userData->bind_param(str_repeat("i", count($idHabilidades)), ...$idHabilidades);
        $stmt_userData->execute();
        $result_userData = $stmt_userData->get_result();
        if ($result_userData->num_rows < 1) {
            return [];
        }
        $portafolios = array();
        while ($row = $result_userData->fetch_assoc()) {
            //  si no es visible, primero ver si es el usuario el propietario, si lo es añadimos sino continuamos
            if($row["id_estado_portafolio"]===0 && $_SESSION['user_id'] !== $row["id_usuario_portafolio"]){
                continue;
            }
            $portafolio=array(
                'id_portafolio' => $row['id_portafolio'],
                'id_usuario_portafolio' => $row['id_usuario_portafolio'],
                'titulo_portafolio' => $row['titulo_portafolio'],
                'educacion_portafolio' => $row['educacion_portafolio'],
                'sobre_mi_portafolio' => $row['sobre_mi_portafolio'],
                'mensaje_bienvenida_portafolio' => $row['mensaje_bienvenida_portafolio'],
                'carpetaPortafolio' => $row['ubicacion_portafolio'],
                'foto_portafolio' => $row['foto_portafolio'],
                'fondo_portafolio'=>$row['fondo_portafolio'],
                'ubicacionCv_portafolio' => $row['cv_portafolio'],
                'id_estado_portafolio'=>$row['id_estado_portafolio']
            );
            //  colocar habilidades relacionadas a un portafolio
            $portafolio["habilidades"] = getHabilidadesById($conexion, $portafolio["id_portafolio"]);
            $portafolios[] = $portafolio;
        }
        http_response_code(200);
        echo json_encode($portafolios);
    } else {
        
        if(empty($_SESSION['user_id'])){
            http_response_code(500);
            echo json_encode(["error" => "No existe un usuario en el sistema"]);
            exit();
        }
        
        //traer solo los del usuario
        $sql = "SELECT p.id_portafolio, p.id_usuario_portafolio, p.titulo_portafolio, p.educacion_portafolio, p.sobre_mi_portafolio, p.mensaje_bienvenida_portafolio, p.ubicacion_portafolio, p.foto_portafolio, p.fondo_portafolio, p.cv_portafolio, p.id_estado_portafolio 
        FROM portafolios p WHERE p.id_usuario_portafolio = ?";
        $stmt_userData = $conexion->prepare($sql);
        $stmt_userData->bind_param("s", $_SESSION['user_id']);
        $stmt_userData->execute();
        $result_userData = $stmt_userData->get_result();
        if ($result_userData->num_rows < 1) {
            return [];
        }
        // Retornar los valores
        // $portafolios = [];
        // while ($row = $result_userData->fetch_assoc()) {

        $portafolios = array();
        while ($row = $result_userData->fetch_assoc()) {
            //  si no es visible, primero ver si es el usuario el propietario, si lo es añadimos sino continuamos
            if($row["id_estado_portafolio"]===0 && $_SESSION['user_id'] !== $row["id_usuario_portafolio"]){
                continue;
            }
            //  http://localhost/3/proyecto-mds/usersContent../usersContent/DRTX_13436/portafolio/664043dda0cd1/
            $carpetaUsuario=obtenerCarpetaUsuario($conexion,$_SESSION['user_id']).$row['ubicacion_portafolio']."/";
            $portafolio=array(
                'id_portafolio' => $row['id_portafolio'],
                'id_usuario_portafolio' => $row['id_usuario_portafolio'],
                'titulo_portafolio' => $row['titulo_portafolio'],
                'educacion_portafolio' => $row['educacion_portafolio'],
                'sobre_mi_portafolio' => $row['sobre_mi_portafolio'],
                'mensaje_bienvenida_portafolio' => $row['mensaje_bienvenida_portafolio'],
                'foto_portafolio' => $carpetaUsuario.$row['foto_portafolio'],
                'fondo_portafolio'=>$carpetaUsuario.$row['fondo_portafolio'],
                'ubicacionCv_portafolio' => $row['cv_portafolio'],
                'id_estado_portafolio'=>$row['id_estado_portafolio']
            );
            //  colocar habilidades relacionadas a un portafolio
            $portafolio["habilidades"] = getHabilidadesById($conexion, $portafolio["id_portafolio"]);
            $portafolios[] = $portafolio;
        }
        http_response_code(200);
        echo json_encode($portafolios);

    }

}


function deletePortafolio($conexion, $id_portafolio) {
    // Comprobar si el usuario tiene permiso para eliminar el portafolio
    session_start();
    $userId = $_SESSION['user_id'];
    $id_usuario_portafolio = null; // Inicializar la variable

    $sql = "SELECT id_usuario_portafolio FROM portafolios WHERE id_portafolio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_portafolio);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["error" => "Portafolio no encontrado"]);
        exit();
    }
    $stmt->bind_result($id_usuario_portafolio);
    $stmt->fetch();

    if ($id_usuario_portafolio != $userId) {
        http_response_code(403);
        echo json_encode(["error" => "No tiene permiso para eliminar este portafolio"]);
        exit();
    }

    // Iniciar transacción
    $conexion->begin_transaction();

    // Eliminar portafolio de la base de datos
    $sql = "DELETE FROM portafolios WHERE id_portafolio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_portafolio);
    if (!$stmt->execute()) {
        $conexion->rollback();
        http_response_code(500);
        echo json_encode(["error" => "Error al eliminar el portafolio"]);
        exit();
    }

    // Eliminar habilidades asociadas al portafolio
    $sql = "DELETE FROM portafolios_habilidades WHERE id_portafolio_portafolios_habilidades = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_portafolio);
    $stmt->execute();

    // Eliminar proyectos asociados al portafolio
    $sql = "DELETE FROM proyectos_agrupados_portafolio WHERE id_portafolio_proyectos_agrupados_portafolio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_portafolio);
    $stmt->execute();

    // Confirmar transacción
    $conexion->commit();

    http_response_code(200);
    echo json_encode(["message" => "Portafolio eliminado correctamente"]);
}

function getHabilidadesById($conexion, $idPortafolio)
{
    if (empty($idPortafolio)) {
        return [];
    }
    // Crear la consulta SQL para obtener la información de las habilidades
    $sql =
        "SELECT 
                ph.id_habilidad_portafolios_habilidades, h.nombre_habilidades, h.tipo_habilidades
            FROM 
                portafolios_habilidades ph
            JOIN 
                habilidades h ON ph.id_habilidad_portafolios_habilidades = h.id_habilidades
            WHERE 
                ph.id_portafolio_portafolios_habilidades = ?";
    $stmt = $conexion->prepare($sql);
    // Asignar los valores de los placeholders
    $stmt->bind_param("i", $idPortafolio);
    $stmt->execute();
    $result = $stmt->get_result();
    //$conexion->close();
    // Obtener los datos de las habilidades
    $habilidades = [];
    $habilidadesTecnicas = [];
    $habilidadesSociales = [];
    while ($row = $result->fetch_assoc()) {
        $habilidad = [
            "id" => $row["id_habilidad_portafolios_habilidades"],
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