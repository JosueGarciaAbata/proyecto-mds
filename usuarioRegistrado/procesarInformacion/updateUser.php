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
    // Procesar la subida de la imagen como acción 'imgPerfilTemporal'
    $rutaImagen = subirImagenPerfil("../$carpeta_usuario/temp/");

    // Devolver la respuesta (URL de la imagen o mensaje de error)
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

// Función para validar y procesar la subida de la imagen
function subirImagenPerfil($carpetaDestino)
{
  // Verificar si se recibió un archivo de imagen
  if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    // Validar que el archivo sea una imagen
    $imageFileType = exif_imagetype($_FILES['profileImage']['tmp_name']);
    if ($imageFileType !== false) {
      // Solo permitir tipos de imágenes específicos (puedes ajustar según tus necesidades)
      $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
      if (in_array($imageFileType, $allowedTypes)) {





        // Directorio temporal donde se almacenarán las imágenes
        $tempDir = $carpetaDestino;

        // Crear el directorio temporal si no existe
        if (!is_dir($tempDir)) {
          mkdir($tempDir, 0755, true); // Crear directorio recursivamente
        }

        // Eliminar archivos existentes en el directorio temporal
        $files = scandir($tempDir);
        foreach ($files as $file) {
          if ($file != '.' && $file != '..') {
            unlink($tempDir . $file); // Eliminar archivo
          }
        }

        // Generar un nombre único para el archivo de imagen
        $extension = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = generarNombreUnico($extension);

        // Ruta completa del archivo de imagen en el directorio temporal
        $targetFile = $tempDir . $nombreArchivo;

        // Mover el archivo subido al directorio temporal
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFile)) {
          // Devolver la ruta completa de la imagen
          return $targetFile = preg_replace('/^\.\.\//', '', $targetFile, 1);
        } else {
          return false; // Error al mover la imagen
        }

      } else {
        return false; // Tipo de archivo no permitido (no es una imagen válida)
      }
    } else {
      return false; // El archivo no es una imagen válida
    }
  } else {
    return false; // No se ha recibido ninguna imagen o ha ocurrido un error durante la carga
  }
}


?>