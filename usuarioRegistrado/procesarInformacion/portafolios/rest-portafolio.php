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
        if (!empty($_GET)) {
            getPortafolio($conexion, $_GET['id-portafolio']);
            exit();
        }
        if (!empty($_POST)) {
            //  o para obtenerlos filtrandolos por las habilidades q tiene el portafolio
            getPortafolios($conexion, $_POST["id"], $_POST["habilidades"]);
        } else {
            //es para obtener los datos de 1
            getPortafolios($conexion);

        }
        break;
    case "POST":
        //  como no se pueden enviar archivos por metodo put lo hare en post
        if(empty($_POST['id'])){
            $titulo = $_POST['titulo'];
            $mensaje = $_POST['mensaje'];
            $estudios = $_POST['estudios'];
            $sobreMi = $_POST['sobreMi'];
            $habilidadesTecnicas = $_POST['habilidadesTecnicas'];
            $habilidadesSociales = $_POST['habilidadesSociales'];
            $proyectos = $_POST['proyectos'];
            $dataBasic = [$titulo, $mensaje, $estudios, $sobreMi];
            $cosasAVer = array_merge($dataBasic, $habilidadesTecnicas, $habilidadesSociales);

            if (contieneCaracteresEspeciales($conexion, $cosasAVer)) {
                http_response_code(405);
                echo json_encode(["error" => "Los campos tienen valores inseguros"]);
                exit();
            }

            savePortafolio($conexion, $titulo, $habilidadesTecnicas, $habilidadesSociales, $estudios, $sobreMi, $mensaje, $proyectos);
            exit();
        }else{
            $portafolioId = $_POST['id'];
            $titulo = $_POST['titulo'];
            $mensaje = $_POST['mensaje'];
            $estudios = $_POST['estudios'];
            $sobreMi = $_POST['sobreMi'];
            $habilidadesTecnicas = $_POST['habilidadesTecnicas'];
            $habilidadesSociales = $_POST['habilidadesSociales'];
            $proyectos = $_POST['proyectos'];
            $dataBasic = [$titulo, $mensaje, $estudios, $sobreMi, $portafolioId];
            $cosasAVer = array_merge($dataBasic, $habilidadesTecnicas, $habilidadesSociales);
            if (contieneCaracteresEspeciales($conexion, $cosasAVer)) {
                http_response_code(405);
                echo json_encode(["error" => "Los campos tienen valores inseguros"]);
                exit();
            }

            //updatePortafolio($conexion, $userId, $portafolioId, $titulo, $habT, $habS, $estudios, $sobreMi, $mensaje, $proyectos)
            updatePortafolio($conexion, $portafolioId,$titulo, $habilidadesTecnicas, $habilidadesSociales, $estudios, $sobreMi, $mensaje, $proyectos);
        }
        break;
    case "DELETE":
        if (!empty($_GET['id-portafolio'])) {
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

function obtenerCarpetaUsuario($conexion, $userId)
{
    $dataUser = getUserName($conexion, $userId);
    return $dataUser["carpeta"] . '/portafolio/';
}

function contieneCaracteresEspeciales($conexion, $arrayTitulos)
{
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
    $userId = $_SESSION['user_id'];
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

    // Subir archivos
    $rutaCarpetaUsuario = "../../" . obtenerCarpetaUsuario($conexion, $userId);
    //$rutaCarpetaUsuario = "../../../usersContent/" . $datosUsuario;
    //echo "La carpeta del usuario es:" .$rutaCarpetaUsuario;
    $identificadorUnico = uniqid();

    // Comprobar si la carpeta con el identificador único ya existe
    while (file_exists($rutaCarpetaUsuario . "/" . $identificadorUnico)) {
        $identificadorUnico = uniqid();
    }
    $rutaCarpetaUsuario .= "/$identificadorUnico";
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
    $stmt->bind_param("sssssssss", $userId, $titulo, $estudios, $sobreMi, $mensaje, $identificadorUnico, $_FILES['fotoP']['name'], $_FILES['fotoF']['name'], $_FILES['cv']['name']);

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
            $stmt->bind_param("ii", $lastInsertedId, $habilidad);
            $stmt->execute();
        }
        if (isset($proyectos)) {
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
    //echo "La ruta destino es: $rutaDestinoArchivo";
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

function updatePortafolio($conexion, $portafolioId, $titulo, $habT, $habS, $estudios, $sobreMi, $mensaje, $proyectos)
{
    // Iniciar la transacción
    $conexion->begin_transaction();

    try {
        session_start();
        $userId = $_SESSION['user_id'];
        // session_write_close(); 
        // Cierra la sesión para evitar bloqueos

        $sql = "SELECT id_usuario_portafolio, ubicacion_portafolio, foto_portafolio, fondo_portafolio, cv_portafolio FROM portafolios WHERE id_portafolio = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $portafolioId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            http_response_code(404);
            echo json_encode(["error" => "Portafolio no encontrado"]);
            exit();
        }

        $portfolioData = $result->fetch_assoc();

        if ($portfolioData["id_usuario_portafolio"] != $userId) {
            http_response_code(403);
            echo json_encode(["error" => "No tiene permiso para actualizar este portafolio"]);
            exit();
        }

        // Eliminar carpeta del portafolio anterior
        $datosUsuario = "../../" . obtenerCarpetaUsuario($conexion, $userId);
        $rutaCarpetaUsuario = $datosUsuario . "/" . $portfolioData["ubicacion_portafolio"];
        print_r($rutaCarpetaUsuario);
        // Actualizar el portafolio
        $sql = "UPDATE portafolios SET titulo_portafolio = ?, educacion_portafolio = ?, sobre_mi_portafolio = ?, mensaje_bienvenida_portafolio = ? WHERE id_portafolio = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssss", $titulo, $estudios, $sobreMi, $mensaje, $portafolioId);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el portafolio");
        }

        // Actualizar archivos si existen
        if(!empty($FILES)){
            print_r($FILES);
            if(!empty($_FILES['fotoP'])){
                // eliminar archivo y ponerlo en el mismo sitio
                deleteFile($rutaCarpetaUsuario.$portfolioData["foto_portafolio"]);
                //  subir a la misma ruta
                subirArchivo("fotoP", $rutaCarpetaUsuario);
                //actualizar
                updatePortfolioColumn($conexion,$_FILES['fotoP']['name'],"foto_portafolio", $portafolioId);
            }
            if(!empty($_FILES['fotoF'])){
                deleteFile($rutaCarpetaUsuario.$portfolioData["fondo_portafolio"]);
                subirArchivo("fotoF", $rutaCarpetaUsuario);
                updatePortfolioColumn($conexion,$_FILES['fotoF']['name'],"fondo_portafolio",$portafolioId);
            }

            if(!empty($_FILES['cv'])){
                deleteFile($rutaCarpetaUsuario.$portfolioData["cv_portafolio"]);
                subirArchivo("cv", $rutaCarpetaUsuario);
                updatePortfolioColumn($conexion,$_FILES['cv']['name'],"cv_portafolio",$portafolioId);
            }
        }
        
        $sql = "UPDATE portafolios SET foto_portafolio = ?, fondo_portafolio = ?, cv_portafolio = ? WHERE id_portafolio = ? AND id_usuario_portafolio = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssss", $_FILES['fotoP']['name'], $_FILES['fotoF']['name'], $_FILES['cv']['name'], $portafolioId, $userId);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar los archivos del portafolio");
        }
        // Actualizar habilidades si existen
        if (!empty($habT) || !empty($habS)) {
            $habilidades = array_merge($habT, $habS);

            // Eliminar habilidades anteriores
            $sql = "DELETE FROM portafolios_habilidades WHERE id_portafolio_portafolios_habilidades = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $portafolioId);
            $stmt->execute();

            // Insertar nuevas habilidades
            $sql = "INSERT INTO portafolios_habilidades (id_portafolio_portafolios_habilidades, id_habilidad_portafolios_habilidades) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            foreach ($habilidades as $habilidad) {
                $stmt->bind_param("ii", $portafolioId, $habilidad);
                $stmt->execute();
            }
        }
        // Actualizar proyectos si existen
        if (!empty($proyectos)) {
            // Eliminar proyectos anteriores
            $sql = "DELETE FROM proyectos_agrupados_portafolio WHERE id_portafolio_proyectos_agrupados_portafolio = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $portafolioId);
            $stmt->execute();

            // Insertar nuevos proyectos
            $sql = "INSERT INTO proyectos_agrupados_portafolio (id_portafolio_proyectos_agrupados_portafolio, id_proyecto_proyectos_agrupados_portafolio) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            foreach ($proyectos as $proy) {
                $stmt->bind_param("ii", $portafolioId, $proy);
                $stmt->execute();
            }
        }
        // Confirmar la transacción
        $conexion->commit();
        http_response_code(200);
        echo json_encode(["message" => "Portafolio actualizado correctamente"]);
    } catch (Exception $e) {
        // Si hay algún error, revertir la transacción
        $conexion->rollback();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

function deleteFile($ruta){
    if (file_exists($ruta)) {
        // Intentar eliminar el archivo
        if (!unlink($ruta)) {
            echo "Error al intentar eliminar el archivo $ruta.";
        }
    } else {
        echo "El archivo $ruta no existe.";
    }
}

//function getPortafolio($conexion, $id = 0, $idHabilidades=0)
function getPortafolios($conexion, $idHabilidades = 0)
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
            if ($row["id_estado_portafolio"] === 0 && $_SESSION['user_id'] !== $row["id_usuario_portafolio"]) {
                continue;
            }
            $portafolio = array(
                'id_portafolio' => $row['id_portafolio'],
                'id_usuario_portafolio' => $row['id_usuario_portafolio'],
                'titulo_portafolio' => $row['titulo_portafolio'],
                'educacion_portafolio' => $row['educacion_portafolio'],
                'sobre_mi_portafolio' => $row['sobre_mi_portafolio'],
                'mensaje_bienvenida_portafolio' => $row['mensaje_bienvenida_portafolio'],
                'carpetaPortafolio' => $row['ubicacion_portafolio'],
                'foto_portafolio' => $row['foto_portafolio'],
                'fondo_portafolio' => $row['fondo_portafolio'],
                'ubicacionCv_portafolio' => $row['cv_portafolio'],
                'id_estado_portafolio' => $row['id_estado_portafolio']
            );
            //  colocar habilidades relacionadas a un portafolio
            $portafolio["habilidades"] = getHabilidadesByPortfolioId($conexion, $portafolio["id_portafolio"]);
            $portafolios[] = $portafolio;
        }
        http_response_code(200);
        echo json_encode($portafolios);
    } else {

        if (empty($_SESSION['user_id'])) {
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
            //  esta bien porque solo no encontro datos
            http_response_code(200);
            echo json_encode(["status" => "OK", "statusText" => "El usuario no tiene portafolios actualmente."]);
            exit();
        }
        // Retornar los valores
        // $portafolios = [];
        // while ($row = $result_userData->fetch_assoc()) {

        $portafolios = array();
        while ($row = $result_userData->fetch_assoc()) {
            //  si no es visible, primero ver si es el usuario el propietario, si lo es añadimos sino continuamos
            if ($row["id_estado_portafolio"] === 0 && $_SESSION['user_id'] !== $row["id_usuario_portafolio"]) {
                continue;
            }
            //  http://localhost/3/proyecto-mds/usersContent../usersContent/DRTX_13436/portafolio/664043dda0cd1/
            $carpetaUsuario = obtenerCarpetaUsuario($conexion, $_SESSION['user_id']) . $row['ubicacion_portafolio'] . "/";
            $portafolio = array(
                'id_portafolio' => $row['id_portafolio'],
                'id_usuario_portafolio' => $row['id_usuario_portafolio'],
                'titulo_portafolio' => $row['titulo_portafolio'],
                'educacion_portafolio' => $row['educacion_portafolio'],
                'sobre_mi_portafolio' => $row['sobre_mi_portafolio'],
                'mensaje_bienvenida_portafolio' => $row['mensaje_bienvenida_portafolio'],
                'foto_portafolio' => $carpetaUsuario . $row['foto_portafolio'],
                'fondo_portafolio' => $carpetaUsuario . $row['fondo_portafolio'],
                'ubicacionCv_portafolio' => $row['cv_portafolio'],
                'id_estado_portafolio' => $row['id_estado_portafolio']
            );
            $idP=$portafolio["id_portafolio"];
            //  colocar habilidades relacionadas a un portafolio
            //$portafolio["habilidades"]=array();
            $portafolio["habilidades"] = json_encode(getHabilidadesByPortfolioId($conexion, $idP));
            //print_r($portafolio["habilidades"]);
            //  echo $idP."<br>";
            $portafolios[] = $portafolio;
        }
        http_response_code(200);
        echo json_encode($portafolios);
    }
}


function getPortafolio($conexion, $idPortfolio)
{
    //traer todos
    session_start();
    if (empty($_SESSION['user_id'])) {
        http_response_code(500);
        echo json_encode(["error" => "No existe un usuario en el sistema"]);
        exit();
    }

    //traer solo los del usuario
    $sql = "SELECT p.id_portafolio, p.id_usuario_portafolio, p.titulo_portafolio, p.educacion_portafolio, p.sobre_mi_portafolio, p.mensaje_bienvenida_portafolio, p.ubicacion_portafolio, p.foto_portafolio, p.fondo_portafolio, p.cv_portafolio, p.id_estado_portafolio 
    FROM portafolios p WHERE p.id_portafolio = ?";

    $stmt_userData = $conexion->prepare($sql);
    $stmt_userData->bind_param("s", $idPortfolio);
    $stmt_userData->execute();
    $result_userData = $stmt_userData->get_result();
    if ($result_userData->num_rows < 1) {
        //  esta mal porque no encontro datos
        http_response_code(200);
        echo json_encode(["status" => "BAD_REQUEST", "statusText" => "El portafolio buscado no existe"]);
        exit();
    }
    $row = $result_userData->fetch_assoc();
    //  si no es visible, primero ver si es el usuario el propietario, si lo es añadimos sino continuamos
    if ($row["id_estado_portafolio"] === 0 && $_SESSION['user_id'] !== $row["id_usuario_portafolio"]) {
        http_response_code(200);
        echo json_encode(["status" => "ILLEGAL_REQUEST", "statusText" => "Usuario sin permisos necesarios."]);
        exit();
    }
    //  http://localhost/3/proyecto-mds/usersContent../usersContent/DRTX_13436/portafolio/664043dda0cd1/
    $carpetaUsuario = obtenerCarpetaUsuario($conexion, $_SESSION['user_id']) . $row['ubicacion_portafolio'] . "/";
    $portafolio = array(
        'titulo_portafolio' => $row['titulo_portafolio'],
        'educacion_portafolio' => $row['educacion_portafolio'],
        'sobre_mi_portafolio' => $row['sobre_mi_portafolio'],
        'mensaje_bienvenida_portafolio' => $row['mensaje_bienvenida_portafolio'],
        'foto_portafolio' => $carpetaUsuario . $row['foto_portafolio'],
        'fondo_portafolio' => $carpetaUsuario . $row['fondo_portafolio'],
        'ubicacionCv_portafolio' => $row['cv_portafolio'],
        'id_estado_portafolio' => $row['id_estado_portafolio']
    );
    //  colocar habilidades relacionadas a un portafolio
    $portafolio["habilidades"] = getHabilidadesByPortfolioId($conexion, $idPortfolio);
    $portafolio["proyectos"] = getProyectosByPortfolioId($conexion, $idPortfolio);
    http_response_code(200);
    echo json_encode($portafolio);
}

function getProyectosByPortfolioId($conexion, $idProyecto)
{
    if (empty($idProyecto)) {
        return [];
    }
    $sql = "SELECT 
	        p.id_proyecto AS 'id', p.id_estado_proyecto, p.titulo_proyecto, p.id_categoria_proyecto
	FROM 
		proyectos_agrupados_portafolio pa 
	JOIN
		proyectos p ON pa.id_proyecto_proyectos_agrupados_portafolio = p.id_proyecto
	WHERE 
		pa.id_portafolio_proyectos_agrupados_portafolio = ?
	ORDER BY
		p.id_proyecto ASC";
    $stmt = $conexion->prepare($sql);
    // Asignar los valores de los placeholders
    $stmt->bind_param("i", $idProyecto);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function deletePortafolio($conexion, $id_portafolio)
{
    session_start();
    $userId = $_SESSION['user_id'];
    session_write_close(); // Cierra la sesión para evitar bloqueos

    $sql = "SELECT id_usuario_portafolio, ubicacion_portafolio FROM portafolios WHERE id_portafolio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_portafolio);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["error" => "Portafolio no encontrado"]);
        exit();
    }

    $portfolioData = $result->fetch_assoc();
    //  la verdad no le se cachar a esta parte, no se si concluye la conexion de esa parte o de todo
    //  $stmt->close();

    if ($portfolioData["id_usuario_portafolio"] != $userId) {
        http_response_code(403);
        echo json_encode(["error" => "No tiene permiso para eliminar este portafolio"]);
        exit();
    }

    // Eliminar carpeta del portafolio: ../usersContent/_USER_/portafolios/_ID-PATH_/
    $datosUsuario = "../../" . obtenerCarpetaUsuario($conexion, $userId);
    $rutaCarpetaUsuario = $datosUsuario . "/" . $portfolioData["ubicacion_portafolio"];
    deleteDirectory($rutaCarpetaUsuario);

    // Iniciar transacción para eliminación total, incluyendo sus archivos y directorios en el sistema de archivos
    $conexion->begin_transaction();

    // Eliminar habilidades asociadas al portafolio
    $sql = "DELETE FROM portafolios_habilidades WHERE id_portafolio_portafolios_habilidades = ?";
    $stmtDeleteHabilidades = $conexion->prepare($sql);
    $stmtDeleteHabilidades->bind_param("i", $id_portafolio);
    $stmtDeleteHabilidades->execute();
    $stmtDeleteHabilidades->close();

    // Eliminar proyectos asociados al portafolio
    $sql = "DELETE FROM proyectos_agrupados_portafolio WHERE id_portafolio_proyectos_agrupados_portafolio = ?";
    $stmtDeleteProyectos = $conexion->prepare($sql);
    $stmtDeleteProyectos->bind_param("i", $id_portafolio);
    $stmtDeleteProyectos->execute();
    $stmtDeleteProyectos->close();

    // Eliminar portafolio de la base de datos
    $sql = "DELETE FROM portafolios WHERE id_portafolio = ?";
    $stmtDeletePortafolio = $conexion->prepare($sql);
    $stmtDeletePortafolio->bind_param("i", $id_portafolio);
    if (!$stmtDeletePortafolio->execute()) {
        $conexion->rollback();
        http_response_code(500);
        echo json_encode(["error" => "Error al eliminar el portafolio"]);
        exit();
    }

    $stmtDeletePortafolio->close();

    // Confirmar transacción
    $conexion->commit();

    http_response_code(200);
    echo json_encode(["status" => "OK", "statusText" => "Portafolio eliminado correctamente"]);
}

function getHabilidadesByPortfolioId($conexion, $idPortafolio)
{
    if (empty($idPortafolio)) {
        return [];
    }

    // Crear las consultas SQL para obtener la información de las habilidades técnicas y sociales
    $sqlTecnicas = "SELECT ph.id_habilidad_portafolios_habilidades AS 'id', h.nombre_habilidades as 'nombre'
                    FROM portafolios_habilidades ph
                    JOIN habilidades h ON ph.id_habilidad_portafolios_habilidades = h.id_habilidades
                    WHERE ph.id_portafolio_portafolios_habilidades = ? AND h.tipo_habilidades = 1";

    $sqlSociales = "SELECT ph.id_habilidad_portafolios_habilidades AS 'id', h.nombre_habilidades as 'nombre'
                    FROM portafolios_habilidades ph
                    JOIN habilidades h ON ph.id_habilidad_portafolios_habilidades = h.id_habilidades
                    WHERE ph.id_portafolio_portafolios_habilidades = ? AND h.tipo_habilidades = 0";

    // Consultar habilidades técnicas
    $stmtTecnicas = $conexion->prepare($sqlTecnicas);
    $stmtTecnicas->bind_param("i", $idPortafolio);
    $stmtTecnicas->execute();
    $resultTecnicas = $stmtTecnicas->get_result();

    $habilidadesTecnicas = [];
    while ($row = $resultTecnicas->fetch_assoc()) {
        $habilidadesTecnicas[] = [
            "id" => $row["id"],
            "nombre" => $row["nombre"],
        ];
    }

    // Consultar habilidades sociales
    $stmtSociales = $conexion->prepare($sqlSociales);
    $stmtSociales->bind_param("i", $idPortafolio);
    $stmtSociales->execute();
    $resultSociales = $stmtSociales->get_result();

    $habilidadesSociales = [];
    while ($row = $resultSociales->fetch_assoc()) {
        $habilidadesSociales[] = [
            "id" => $row["id"],
            "nombre" => $row["nombre"],
        ];
    }

    return [
        "habilidadesTecnicas" => $habilidadesTecnicas,
        "habilidadesSociales" => $habilidadesSociales
    ];
}

function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return false;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}

function updatePortfolioColumn($conexion, $valueColumn, $columna, $portfolioId){
    $sql = "UPDATE portafolios SET $columna = ? WHERE id_portafolio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $valueColumn,$portfolioId);
    if (!$stmt->execute()) {
        throw new Exception("Error al actualizar los archivos del portafolio");
    }
}



?>