<?php

require_once ("conexion.php");

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  echo json_encode(crearCuenta($conexion, $_POST['register_name'], $_POST['register_mail'], $_POST['register_password']));



}


function crearCuenta($conexion, $name, $email, $password)
{

  $sql_email = "SELECT * FROM usuarios WHERE correo_electronico_usuario = ? ";
  $stmt_email = $conexion->prepare($sql_email);
  $stmt_email->bind_param("s", $email);
  $stmt_email->execute();
  $result_email = $stmt_email->get_result();

  $sql_name = "SELECT * FROM usuarios WHERE nombre_usuario = ? ";
  $stmt_name = $conexion->prepare($sql_name);
  $stmt_name->bind_param("s", $name);
  $stmt_name->execute();
  $result_name = $stmt_name->get_result();

  if ($result_email->num_rows > 0) {
    return false;
  }
  if ($result_name->num_rows > 0) {
    return false;
  } else {

    $fotoPerfilDefecto = "../img/defaulAvatar.png";
    // Crear directorio para el nuevo usuario
    $randomNumbers = mt_rand(1000, 9999);
    $carpetaUsuario = "../usersContent/$name$randomNumbers";
    if (!mkdir($carpetaUsuario, 0755, true)) {
      return false; // Error al crear directorio principal del usuario
    }

    // Crear subdirectorios
    $subdirectorios = ['temp', 'posts', 'proyectos', 'portafolio', 'perfil'];
    foreach ($subdirectorios as $subdir) {
      $carpetaSubdir = "$carpetaUsuario/$subdir";
      if (!mkdir($carpetaSubdir, 0755, true)) {
        return false; // Error al crear subdirectorio
      }
    }



    $sourceFile = '../img/defaulAvatar.png';
    $destinationFile = "$carpetaUsuario/perfil/defaulAvatar.png";
    if (!copy($sourceFile, $destinationFile)) {
      return false; // Error al copiar archivo de imagen de perfil
    }




    session_start();
    $_SESSION['password_user'] = $password;
    $password = hash("sha256", $password);
    $sql_insert = "INSERT INTO usuarios (nombre_usuario, correo_electronico_usuario, contrasenia_usuario,ubicacion_foto_perfil_usuario,carpeta_usuario) VALUES (?, ?, ?,?,?)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("sssss", $name, $email, $password, $destinationFile, $carpetaUsuario);
    $stmt_insert->execute();
    $user_id = $stmt_insert->insert_id;


    $_SESSION['user_id'] = $user_id;

    $resultado = true;

  }

  return $resultado;



}























?>