<?php

// Se incluye la clase PHPMailer para el envío de correos electrónicos
require '../dist/libs/PHPMailer/PHPMailerAutoload.php';

// Se inicia la sesión para almacenar el código de restablecimiento de contraseña
session_start();

// Se verifica si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Se verifica si el campo "user_email" está definido en la solicitud POST
    if (isset($_POST["user_email"])) {

        // Se obtiene el correo electrónico del usuario desde la solicitud POST
        $mail_setFromEmail = $_POST["user_email"];

        // Se define el nombre del remitente del correo electrónico
        $mail_setFromName = "User";

        // Se define el asunto del correo electrónico
        $mail_subject = "Change Password";

        //Correo encargado de enviar el mensaje al usuario
        $mail_username = "proyectoenviomensajes@gmail.com";
        $mail_userpassword = "degpnyiojyivohxg";

        // Se establece la dirección de correo electrónico a la que se enviará el correo electrónico
        $mail_addAddress = $mail_setFromEmail;

        // Se define la ruta del archivo de plantilla de correo electrónico
        $template = "../templates/email_resetPassword_template.html";

        // Se crea una instancia de la clase PHPMailer
        $mail = new PHPMailer;

        // Se configura el protocolo SMTP
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = $mail_username;
        $mail->Password = $mail_userpassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Se configura la codificación del correo electrónico
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // Se codifica el asunto del correo electrónico en UTF-8
        $mail->Subject = '=?UTF-8?B?' . base64_encode($mail_subject) . '?=';

        // Se establece la dirección y el nombre del remitente del correo electrónico
        $mail->setFrom($mail_setFromEmail, "My Creative Portfolio");

        // Se establece la dirección de respuesta del correo electrónico
        $mail->addReplyTo($mail_setFromEmail);

        // Se agrega la dirección de destino del correo electrónico
        $mail->addAddress($mail_addAddress);

        // Se genera un código aleatorio de 16 bytes y se guarda en la sesión
        $code = bin2hex(random_bytes(16));
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION["code"] = $code;

        // Se lee el contenido de la plantilla de correo electrónico
        $message = file_get_contents($template);

        // Se reemplaza el marcador de posición '{{codigo}}' en la plantilla con el código generado
        $message = str_replace('{{codigo}}', $code, $message);

        // Se configura el correo electrónico como HTML y se establece el contenido del mensaje
        $mail->isHTML(true);
        $mail->Subject = $mail_subject;
        $mail->msgHTML($message);

        // Se envía el correo electrónico y se devuelve un valor booleano indicando si se envió correctamente o no
        if ($mail->send()) {
            // Se retorna true si el correo se envió correctamente
            echo json_encode(true);
        } else {
            // Se retorna false si hubo un error al enviar el correo
            echo json_encode(false);
        }

    }

}

?>