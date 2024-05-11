<?php

require_once ("conexion.php");


$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //Validar correo ingresado
  if (isset($_POST['action']) && $_POST['action'] == "validatEmail") {
    //Enviar respuesta
    echo json_encode(validatEmail($conexion, $_POST['user_email']));

  }
  //Validar codigo ingresado
  if (isset($_POST['action']) && $_POST['action'] == "validateCode") {

    $redirectUrl = validateCode($_POST["user_code"]);
    //Enviar respuesta
    if ($redirectUrl !== false) {
      echo json_encode(array("success" => true, "redirectUrl" => $redirectUrl));
    } else {
      echo json_encode(array("success" => false));
    }
  }
  //Validar contraseña
  if (isset($_POST["action"]) && $_POST["action"] == "changePassword") {
    //Rnviar respuesta
    echo json_encode(changePassword($conexion, $_POST["new_password"]));

  }



}




function validatEmail($conexion, $email)
{
  //Buscar correo ingresado 
  $sql_email = "SELECT * FROM usuarios WHERE correo_electronico_usuario = ? ";
  $stmt_email = $conexion->prepare($sql_email);
  $stmt_email->bind_param("s", $email);
  $stmt_email->execute();

  $result = $stmt_email->get_result();

  //Verificar existencia del correo
  if ($result->num_rows > 0) {

    if (session_status() == PHP_SESSION_NONE) {
      //Guardar correo en la sesion 
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
  //Verificar que el correo ingresado sea el mismo que existe en la sesion
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
  //Cambiar contraseña de usuario
  session_start();

  $newPassword = hash("sha256", $newPassword);
  $user_email = $_SESSION["userEmailPassword"];

  $sql = "UPDATE usuarios SET contrasenia_usuario=? WHERE correo_electronico_usuario=?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("ss", $newPassword, $user_email);

  //Enviar respuesta
  if ($stmt->execute()) {
    session_destroy();
    return true;
  } else {
    return false;
  }
}


