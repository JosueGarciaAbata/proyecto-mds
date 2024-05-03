<?php
require_once ("../../../procesarInformacion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

  session_start();
  $user_id = $_SESSION['user_id'];

  $sql = "SELECT carpeta_usuario FROM usuarios WHERE id_usuario=?";
  $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userContent = $row['carpeta_usuario'];
  }
  //$stmt->close();


  if (isset($_POST["action"]) && $_POST["action"] == "changeCategory" && isset($_POST['category']) && !empty($_POST['category'])) {

    echo json_encode(getLabels($conexion, $_POST["category"]));

  } elseif (isset($_POST['action']) && $_POST['action'] === 'imgProjectTemporal') {

    $routeImage = uploadImage("../../$userContent/temp/", true);


    if ($routeImage !== false) {

      $routeImage = preg_replace('/^\.\.\//', '', $routeImage);

      echo $routeImage;
    } else {
      echo 'false';
    }
  } elseif (isset($_POST['action']) && $_POST['action'] === 'createNewProject' && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id_category']) && isset($_POST['state']) && isset($_POST["date_start"]) && isset($_POST["date_end"])) {
    if (!isset($_FILES['projectImage'])) {
      $sql = "INSERT INTO proyectos (id_usuario_proyecto, id_categoria_proyecto,id_estado_proyecto ,titulo_proyecto,descripcion_proyecto,fecha_inicio_proyecto,fecha_finalizacion_proyecto) VALUES (?, ?,?, ?, ?,?,?)";
      $stmt = $conexion->prepare($sql);
      $stmt->bind_param("iiissss", $user_id, $_POST["id_category"], $_POST["state"], $_POST["title"], $_POST["content"], $_POST["date_start"], $_POST["date_end"]);

      if ($stmt->execute()) {
        $labelsActive = isset($_POST['labelsActive']) ? json_decode($_POST['labelsActive']) : [];

        if (!empty($labelsActive)) {
          // Insertar registros adicionales para asociar las etiquetas con la publicación
          $projectId = $conexion->insert_id; // Obtener el ID de la publicación insertada anteriormente

          $sqlLabels = "INSERT INTO etiquetas_agrupadas_proyectos (id_etiqueta_etiquetas_agrupadas,id_proyecto_etiquetas_agrupadas) VALUES (?, ?)";
          $stmtLabels = $conexion->prepare($sqlLabels);

          foreach ($labelsActive as $labelId) {
            $stmtLabels->bind_param("ii", $labelId, $projectId);
            $stmtLabels->execute();
          }

          echo "true"; // Se insertaron etiquetas correctamente
        } else {
          echo "true"; // No se insertaron etiquetas, pero la publicación principal tuvo éxito
        }
      } else {
        // Error al insertar
        echo "false";
      }

    } else {

      $routeImage = uploadImage("../../$userContent/proyectos/", false);
      if ($routeImage !== false) {
        $routeImage = preg_replace('/^\.\.\//', '', $routeImage);


        $sql = "INSERT INTO proyectos (id_usuario_proyecto, id_categoria_proyecto,id_estado_proyecto ,titulo_proyecto,descripcion_proyecto,fecha_inicio_proyecto,fecha_finalizacion_proyecto,ubicacion_imagen_proyecto) VALUES (?, ?,?, ?, ?,?,?,?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iiisssss", $user_id, $_POST["id_category"], $_POST["state"], $_POST["title"], $_POST["content"], $_POST["date_start"], $_POST["date_end"], $routeImage);

        if ($stmt->execute()) {
          $labelsActive = isset($_POST['labelsActive']) ? json_decode($_POST['labelsActive']) : [];

          if (!empty($labelsActive)) {
            // Insertar registros adicionales para asociar las etiquetas con la publicación
            $projectId = $conexion->insert_id; // Obtener el ID de la publicación insertada anteriormente

            $sqlLabels = "INSERT INTO etiquetas_agrupadas_proyectos (id_etiqueta_etiquetas_agrupadas, id_proyecto_etiquetas_agrupadas) VALUES (?, ?)";
            $stmtLabels = $conexion->prepare($sqlLabels);

            foreach ($labelsActive as $labelId) {
              $stmtLabels->bind_param("ii", $labelId, $projectId);
              $stmtLabels->execute();

            }

            echo "true"; // Se insertaron etiquetas correctamente
          } else {
            echo "true"; // No se insertaron etiquetas, pero la publicación principal tuvo éxito
          }
        } else {
          echo "false"; // Error al insertar la publicación principal
        }

      } else {
        echo 'false';

      }
    }





  } elseif (isset($_POST['action']) && $_POST['action'] === 'updateProject' && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id_category']) && isset($_POST['state']) && isset($_POST['state']) && isset($_POST["date_start"]) && isset($_POST["date_end"])) {

    if (!isset($_FILES['projectImage'])) {
      $sql = "UPDATE proyectos SET id_categoria_proyecto=?,id_estado_proyecto=?,titulo_proyecto=?,descripcion_proyecto=?,fecha_inicio_proyecto=?,fecha_finalizacion_proyecto=? WHERE id_proyecto=?  ";

      $stmt = $conexion->prepare($sql);
      $stmt->bind_param("iissssi", $_POST["id_category"], $_POST["state"], $_POST["title"], $_POST["content"], $_POST["date_start"], $_POST["date_end"], $_POST["id_project"]);

      if ($stmt->execute()) {
        $labelsActive = isset($_POST['labelsActive']) ? json_decode($_POST['labelsActive']) : [];

        // Obtener el ID de la publicación insertada anteriormente

        // Eliminar registros antiguos asociados con el post
        $sqlDeleteOldLabels = "DELETE FROM etiquetas_agrupadas_proyectos WHERE id_proyecto_etiquetas_agrupadas = ?";
        $stmtDeleteOldLabels = $conexion->prepare($sqlDeleteOldLabels);
        $stmtDeleteOldLabels->bind_param("i", $_POST["id_project"]);
        $stmtDeleteOldLabels->execute();

        // Insertar nuevos registros asociados con el post
        if (!empty($labelsActive)) {
          $sqlInsertNewLabels = "INSERT INTO etiquetas_agrupadas_proyectos(id_etiqueta_etiquetas_agrupadas, id_proyecto_etiquetas_agrupadas) VALUES (?, ?)";
          $stmtInsertNewLabels = $conexion->prepare($sqlInsertNewLabels);

          foreach ($labelsActive as $labelId) {
            $stmtInsertNewLabels->bind_param("ii", $labelId, $_POST["id_project"]);
            $stmtInsertNewLabels->execute();
          }
        }

        echo "true"; // Operación de actualización exitosa
      } else {
        // Error al insertar la publicación principal
        echo "false";
      }

    } else {

      $routeImage = uploadImage("../../$userContent/proyectos/", false);
      if ($routeImage !== false) {
        $routeImage = preg_replace('/^\.\.\//', '', $routeImage);

        $sql = "UPDATE proyectos SET id_categoria_proyecto=?,id_estado_proyecto=?,titulo_proyecto=?,descripcion_proyecto=?,fecha_inicio_proyecto=?,fecha_finalizacion_proyecto=?,ubicacion_imagen_proyecto=? WHERE id_proyecto=?  ";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iisssssi", $_POST["id_category"], $_POST["state"], $_POST["title"], $_POST["content"], $_POST["date_start"], $_POST["date_end"], $routeImage, $_POST["id_project"]);

        if ($stmt->execute()) {
          $labelsActive = isset($_POST['labelsActive']) ? json_decode($_POST['labelsActive']) : [];

          // Obtener el ID de la publicación insertada anteriormente
          // $postId = $conexion->insert_id;

          // Eliminar registros antiguos asociados con el post
          $sqlDeleteOldLabels = "DELETE FROM etiquetas_agrupadas_proyectos WHERE id_proyecto_etiquetas_agrupadas = ?";
          $stmtDeleteOldLabels = $conexion->prepare($sqlDeleteOldLabels);
          $stmtDeleteOldLabels->bind_param("i", $_POST["id_project"]);
          $stmtDeleteOldLabels->execute();

          // Insertar nuevos registros asociados con el post
          if (!empty($labelsActive)) {
            $sqlInsertNewLabels = "INSERT INTO etiquetas_agrupadas_proyectos(id_etiqueta_etiquetas_agrupadas, id_proyecto_etiquetas_agrupadas) VALUES (?, ?)";
            $stmtInsertNewLabels = $conexion->prepare($sqlInsertNewLabels);

            foreach ($labelsActive as $labelId) {
              $stmtInsertNewLabels->bind_param("ii", $labelId, $_POST["id_project"]);
              $stmtInsertNewLabels->execute();
            }
          }

          echo "true"; // Operación de actualización exitosa
        } else {
          // Error al insertar la publicación principal
          echo "false";// Error al insertar la publicación principal
        }

      } else {
        echo 'false';

      }
    }





  } elseif (isset($_POST["action"]) && $_POST["action"] == "selectProjects") {
    $stmt->close();
    $posts = getProjects($conexion, $user_id);

    // Convertir los posts a formato JSON
    $response = json_encode($posts);

    // Imprimir la respuesta JSON
    echo $response;
  } elseif (isset($_POST["action"]) && $_POST["action"] == "getInfoUpdateProject" && isset($_POST["id_project"])) {
    $stmt->close();
    $project = getInfoUpdateProject($conexion, $_POST["id_project"]);

    echo json_encode($project);
  } elseif (isset($_POST["action"]) && $_POST["action"] == "deleteProject" && isset($_POST["id_project"])) {
    // Preparar y ejecutar la consulta para eliminar etiquetas asociadas al post
    $sql_labels = "DELETE FROM etiquetas_agrupadas_proyectos WHERE id_proyecto_etiquetas_agrupadas = ?";
    $stmt_labels = $conexion->prepare($sql_labels);
    $stmt_labels->bind_param("i", $_POST["id_project"]);
    $stmt_labels->execute();

    // Preparar y ejecutar la consulta para eliminar el post
    $sql_delete = "DELETE FROM proyectos WHERE id_proyecto=?";
    $stmt_delete = $conexion->prepare($sql_delete);
    $stmt_delete->bind_param("i", $_POST["id_project"]);
    $stmt_delete->execute();

    echo "true";


  } else {
    echo "false";
  }

}




function getLabels($conexion, $idCategory)
{
  $sql = "SELECT nombre_etiqueta,id_etiqueta FROM etiquetas WHERE id_categoria_etiqueta=?";
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


function generateUniqueName($extension)
{
  $nombreEncriptado = bin2hex(random_bytes(8));
  return $nombreEncriptado . '.' . $extension;
}

function uploadImage($carpetaDestino, $clear)
{
  if (isset($_FILES['projectImage']) && $_FILES['projectImage']['error'] === UPLOAD_ERR_OK) {
    $imageFileType = exif_imagetype($_FILES['projectImage']['tmp_name']);
    if ($imageFileType !== false) {
      $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
      if (in_array($imageFileType, $allowedTypes)) {
        $tempDir = $carpetaDestino;
        if (!is_dir($tempDir)) {
          mkdir($tempDir, 0755, true);
        }
        if ($clear) {
          $files = glob($tempDir . '/*');
          foreach ($files as $file) {
            if (is_file($file)) {
              unlink($file);
            }
          }
        }
        $extension = pathinfo($_FILES['projectImage']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = generateUniqueName($extension);
        $targetFile = $tempDir . $nombreArchivo;
        if (move_uploaded_file($_FILES['projectImage']['tmp_name'], $targetFile)) {
          // Redimensionar la imagen a 300x168 píxeles
          resizeImage($targetFile, $targetFile, 300, 168);
          // Devolver la ruta completa de la imagen
          return preg_replace('/^\.\.\//', '', $targetFile, 1);
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  } else {
    return false;
  }
}
function resizeImage($sourceFile, $targetFile, $newWidth, $newHeight)
{
  list($srcWidth, $srcHeight, $type) = getimagesize($sourceFile);
  $sourceImage = null;
  switch ($type) {
    case IMAGETYPE_JPEG:
      $sourceImage = imagecreatefromjpeg($sourceFile);
      break;
    case IMAGETYPE_PNG:
      $sourceImage = imagecreatefrompng($sourceFile);
      break;
    case IMAGETYPE_GIF:
      $sourceImage = imagecreatefromgif($sourceFile);
      break;
    default:
      return false;
  }
  if (!$sourceImage) {
    return false;
  }
  $resizedImage = imagescale($sourceImage, $newWidth, $newHeight);
  if (!$resizedImage) {
    return false;
  }
  switch ($type) {
    case IMAGETYPE_JPEG:
      imagejpeg($resizedImage, $targetFile);
      break;
    case IMAGETYPE_PNG:
      imagepng($resizedImage, $targetFile);
      break;
    case IMAGETYPE_GIF:
      imagegif($resizedImage, $targetFile);
      break;
    default:
      return false;
  }
  imagedestroy($sourceImage);
  imagedestroy($resizedImage);
  return true;
}

function getProjects($conexion, $user_id)
{
  $sql = "SELECT * FROM proyectos WHERE id_usuario_proyecto = ?  ORDER BY id_proyecto DESC";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $data = [];
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }



  return $data;
}

function getInfoUpdateProject($conexion, $id_project)
{
  $data = [];

  // Consulta para obtener la información del post
  $sql = "SELECT * FROM proyectos WHERE id_proyecto=?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("i", $id_project);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $data['project_info'] = $row; // Agregar la información del post al array $data
  }

  // Consulta para obtener las etiquetas asociadas al post
  $sql_etiquetas = "SELECT etiquetas.nombre_etiqueta ,etiquetas.id_etiqueta
                    FROM etiquetas_agrupadas_proyectos 
                    INNER JOIN etiquetas 
                    ON etiquetas_agrupadas_proyectos.id_etiqueta_etiquetas_agrupadas = etiquetas.id_etiqueta 
                    WHERE etiquetas_agrupadas_proyectos.id_proyecto_etiquetas_agrupadas=?";
  $stmt_etiquetas = $conexion->prepare($sql_etiquetas);
  $stmt_etiquetas->bind_param("i", $id_project);
  $stmt_etiquetas->execute();
  $result_etiquetas = $stmt_etiquetas->get_result();

  $etiquetas = [];
  while ($row_etiqueta = $result_etiquetas->fetch_assoc()) {
    $etiqueta = [
      'id_etiqueta' => $row_etiqueta['id_etiqueta'],
      'nombre_etiqueta' => $row_etiqueta['nombre_etiqueta']
    ];
    $etiquetas[] = $etiqueta;
  }

  $data['etiquetas'] = $etiquetas; // Agregar las etiquetas al array $data

  // Consulta para obtener las etiquetas de una categoría específica
  $id_categoria_project = $data['project_info']['id_categoria_proyecto'];
  $sql_etiquetas_categoria = "SELECT * FROM etiquetas WHERE id_categoria_etiqueta=?";
  $stmt_etiquetas_categoria = $conexion->prepare($sql_etiquetas_categoria);
  $stmt_etiquetas_categoria->bind_param("i", $id_categoria_project);
  $stmt_etiquetas_categoria->execute();
  $result_etiquetas_categoria = $stmt_etiquetas_categoria->get_result();

  $etiquetas_categoria = [];
  while ($row_etiqueta_categoria = $result_etiquetas_categoria->fetch_assoc()) {
    $etiquetas_categoria[] = $row_etiqueta_categoria;
  }

  $data['etiquetas_categoria'] = $etiquetas_categoria; // Agregar las etiquetas de la categoría al array $data

  return $data;
}
