<?php

require_once ("conexion.php");

require_once ("filter_input.php");

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //Enviar resultado del proceso
  echo json_encode(createAccount($conexion, $_POST['register_name'], $_POST['register_mail'], $_POST['register_password']));



}



//Crear cuenta de usuario
function createAccount($conexion, $name, $email, $password)
{
  $name = cleanText($name);

  $email = cleanText($email);

  $password = cleanText($password);

  //Validar no existencia de la cuenta ingresada
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

  //La cuenta ya existe
  if ($result_email->num_rows > 0) {
    return false;
  }
  if ($result_name->num_rows > 0) {
    return false;
  }
  //La cuenta es nueva
  else {

    // Crear directorio para el nuevo usuario
    $randomNumbers = mt_rand(1000, 9999);
    $userFolder = "../usersContent/$name$randomNumbers";
    if (!mkdir($userFolder, 0755, true)) {
      return false; // Error al crear directorio principal del usuario
    }

    // Crear subdirectorios
    $subdirectories = ['temp', 'posts', 'proyectos', 'portafolio', 'perfil'];
    foreach ($subdirectories as $subdir) {
      $folderSubdir = "$userFolder/$subdir";
      if (!mkdir($folderSubdir, 0755, true)) {
        return false; // Error al crear subdirectorio
      }
    }


    //Asignar foto de perfil por defecto
    $sourceFile = '../img/defaulAvatar.png';
    $destinationFile = "$userFolder/perfil/defaulAvatar.png";
    if (!copy($sourceFile, $destinationFile)) {
      return false; // Error al copiar archivo de imagen de perfil
    }
    //Generar una sesion para el usuario
    session_start();
    $_SESSION['password_user'] = $password;
    //Encriptar contraseña
    $password = hash("sha256", $password);


    //Generar usuario en la BD
    $sql_insert = "INSERT INTO usuarios (nombre_usuario, correo_electronico_usuario, contrasenia_usuario,ubicacion_foto_perfil_usuario,carpeta_usuario) VALUES (?, ?, ?,?,?)";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("sssss", $name, $email, $password, $destinationFile, $userFolder);
    $stmt_insert->execute();
    $user_id = $stmt_insert->insert_id;

    $_SESSION['user_id'] = $user_id;

    $result = true;

  }

  return $result;



}























?>