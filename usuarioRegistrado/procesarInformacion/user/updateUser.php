<?php
require_once ("../../../procesarInformacion/conexion.php");

require_once ("../img/gestorImagenes.php");

require_once ("../../../procesarInformacion/filter_input.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //Recuperar el id del usuario
  session_start();
  $user_id = $_SESSION['user_id'];

  //Buscar la carpeta del usuario
  $sql = "SELECT carpeta_usuario FROM usuarios WHERE id_usuario=?";
  $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userContent = $row['carpeta_usuario'];
  } else {

    echo "false";
  }
  //Procesar imagen temporal
  if (isset($_POST['action']) && $_POST['action'] === 'imgPerfilTemporal') {

    $routeImage = uploadImage("../../$userContent/temp/", "profileImage", true);


    if ($routeImage !== false) {
      $routeImage = preg_replace('/^\.\.\//', '', $routeImage);
      echo $routeImage;
    } else {
      echo 'false';
    }
  }

  //Actualizar usuario
  elseif (isset($_POST['action']) && $_POST['action'] === 'imgPerfil' && isset($_POST['update_name']) && isset($_POST['update_email']) && isset($_POST['update_password'])) {

    $updateName = cleanText($_POST['update_name']);
    $updateEmail = cleanText($_POST['update_email']);
    $updatePassword = $_POST['update_password'];
    $updatePassword = hash("sha256", $updatePassword);
    if (validateAccountExistence($conexion, $updateEmail, $updateName, $user_id)) {

      echo "false";
      return;
    }

    if (!isset($_FILES['profileImage'])) {
      $sql_update = "UPDATE usuarios SET nombre_usuario=?,correo_electronico_usuario=?,contrasenia_usuario=?  WHERE id_usuario=?";
      $stmt_update = $conexion->prepare($sql_update);
      $stmt_update->bind_param("sssi", $updateName, $updateEmail, $updatePassword, $user_id);
      $stmt_update->execute();
      echo "true";


    } else {
      $routeImage = uploadImage("../../$userContent/perfil/", "profileImage", true);
      if ($routeImage !== false) {
        $routeImage = preg_replace('/^\.\.\//', '', $routeImage);
        $sql_update = "UPDATE usuarios SET nombre_usuario=?,correo_electronico_usuario=?,contrasenia_usuario=?,ubicacion_foto_perfil_usuario=?  WHERE id_usuario=?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("ssssi", $updateName, $updateEmail, $updatePassword, $routeImage, $user_id);
        $stmt_update->execute();
        echo "true";

      } else {
        echo 'false';
      }
    }


  } else {
    echo 'false';

  }
} else {

  echo 'false';
}


function validateAccountExistence($conexion, $email, $name, $user_id)
{
  //Validar no existencia de la cuenta ingresada
  $sql_email = "SELECT * FROM usuarios WHERE correo_electronico_usuario = ? AND id_usuario != ?";
  $stmt_email = $conexion->prepare($sql_email);
  $stmt_email->bind_param("si", $email, $user_id);
  $stmt_email->execute();
  $result_email = $stmt_email->get_result();

  $sql_name = "SELECT * FROM usuarios WHERE nombre_usuario = ? AND id_usuario != ?";
  $stmt_name = $conexion->prepare($sql_name);
  $stmt_name->bind_param("si", $name, $user_id);
  $stmt_name->execute();
  $result_name = $stmt_name->get_result();
  //La cuenta ya existe
  if ($result_email->num_rows > 0) {
    return true;
  }
  if ($result_name->num_rows > 0) {
    return true;
  }
  return false;


}






?>