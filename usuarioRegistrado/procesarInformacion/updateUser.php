<?php
require_once ("../../procesarInformacion/conexion.php");




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
    $carpeta_usuario = $row['carpeta_usuario'];
  }
  if (isset($_POST['action']) && $_POST['action'] === 'imgPerfilTemporal') {

    $rutaImagen = subirImagenPerfil("../$carpeta_usuario/temp/");


    if ($rutaImagen !== false) {
      echo "$rutaImagen";
    } else {
      echo 'false';
    }
  } elseif (isset($_POST['action']) && $_POST['action'] === 'imgPerfil' && isset($_POST['update_name']) && isset($_POST['update_email']) && isset($_POST['update_password'])) {

    $rutaImagen = subirImagenPerfil("../$carpeta_usuario/perfil/");


    if ($rutaImagen !== false) {
      $updateName = $_POST['update_name'];
      $updateEmail = $_POST['update_email'];
      $updatePassword = $_POST['update_password'];
      $sql_update = "UPDATE usuarios SET nombre_usuario=?,correo_electronico_usuario=?,contrasenia_usuario=?,ubicacion_foto_perfil_usuario=?  WHERE id_usuario=?";
      $stmt_update = $conexion->prepare($sql_update);
      $stmt_update->bind_param("ssssi", $updateName, $updateEmail, $updatePassword, $rutaImagen, $user_id);
      $stmt_update->execute();
      echo "true";

    } else {
      echo 'false';
    }
  } else {
    echo 'false';

  }
} else {

  echo 'false';
}






function generarNombreUnico($extension)
{
  $nombreEncriptado = bin2hex(random_bytes(8));
  return $nombreEncriptado . '.' . $extension;
}

function subirImagenPerfil($carpetaDestino)
{

  if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {

    $imageFileType = exif_imagetype($_FILES['profileImage']['tmp_name']);
    if ($imageFileType !== false) {

      $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
      if (in_array($imageFileType, $allowedTypes)) {






        $tempDir = $carpetaDestino;


        if (!is_dir($tempDir)) {
          mkdir($tempDir, 0755, true);
        }


        $files = scandir($tempDir);
        foreach ($files as $file) {
          if ($file != '.' && $file != '..') {
            unlink($tempDir . $file);
          }
        }


        $extension = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = generarNombreUnico($extension);


        $targetFile = $tempDir . $nombreArchivo;


        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFile)) {
          // Devolver la ruta completa de la imagen
          return $targetFile = preg_replace('/^\.\.\//', '', $targetFile, 1);
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


?>