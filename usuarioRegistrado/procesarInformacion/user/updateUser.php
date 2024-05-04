<?php
require_once ("../../../procesarInformacion/conexion.php");

require_once ("../img/gestorImagenes.php");



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


    $routeImage = uploadImage("../../$userContent/perfil/", "profileImage", true);


    if ($routeImage !== false) {
      $routeImage = preg_replace('/^\.\.\//', '', $routeImage);
      $updateName = $_POST['update_name'];
      $updateEmail = $_POST['update_email'];
      $updatePassword = $_POST['update_password'];
      $updatePassword = hash("sha256", $updatePassword);
      $sql_update = "UPDATE usuarios SET nombre_usuario=?,correo_electronico_usuario=?,contrasenia_usuario=?,ubicacion_foto_perfil_usuario=?  WHERE id_usuario=?";
      $stmt_update = $conexion->prepare($sql_update);
      $stmt_update->bind_param("ssssi", $updateName, $updateEmail, $updatePassword, $routeImage, $user_id);
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








?>