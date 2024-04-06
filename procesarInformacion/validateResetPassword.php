<?php

require_once ("conexion.php");

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (isset($_POST['action']) && $_POST['action'] == "validatEmail") {
    echo json_encode(validatEmail($conexion, $_POST['user_email']));

  }

  if (isset($_POST['action']) && $_POST['action'] == "validateCode") {
    $redirectUrl = validateCode($_POST["user_code"]);
    if ($redirectUrl !== false) {
      echo json_encode(array("success" => true, "redirectUrl" => $redirectUrl));
    } else {
      echo json_encode(array("success" => false));
    }
  }
  if (isset($_POST["action"]) && $_POST["action"] == "changePassword") {

    echo json_encode(changePassword($conexion, $_POST["new_password"]));

  }



}


function validatEmail($conexion, $email)
{

  $sql_email = "SELECT * FROM usuarios WHERE correo_electronico_usuario = ? ";
  $stmt_email = $conexion->prepare($sql_email);
  $stmt_email->bind_param("s", $email);
  $stmt_email->execute();

  $result = $stmt_email->get_result();


  if ($result->num_rows > 0) {

    if (session_status() == PHP_SESSION_NONE) {
      session_start();
      $_SESSION["userEmailPassword"] = $email;
    }
    return true;

  } else {

    return false;

  }

}
function validateCode($code)
{
  session_start();
  $codeStored = $_SESSION["code"];
  if ($codeStored == $code) {
    $redirectUrl = "resetPassword.php?code=$code";
    return $redirectUrl;
  } else {
    return false;
  }
}

function changePassword($conexion, $newPassword)
{

  session_start();

  $newPassword = hash("sha256", $newPassword);
  $user_email = $_SESSION["userEmailPassword"];

  $sql = "UPDATE usuarios SET contrasenia_usuario=? WHERE correo_electronico_usuario=?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("ss", $newPassword, $user_email);
  if ($stmt->execute()) {
    session_destroy();
    return true; // Devuelve true si la operación fue exitosa
  } else {
    return false; // Devuelve false si ocurrió algún error
  }
}


