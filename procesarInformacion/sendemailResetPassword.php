<?php

require '../dist/libs/PHPMailer/PHPMailerAutoload.php';

session_start();




    $mail_setFromEmail = "bjeferssonvinicio2005@gmail.com";
    $mail_setFromName = "Joel";
    
    $mail_subject="Cambio de clave";
    
    $mail_username = "proyectoenviomensajes@gmail.com";
    $mail_userpassword = "degpnyiojyivohxg";
    $mail_addAddress = $mail_setFromEmail;
    $template = "../templates/email_resetPassword_template.html";
    
    
       
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = $mail_username;
    $mail->Password = $mail_userpassword;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;


    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Subject = '=?UTF-8?B?' . base64_encode($mail_subject) . '?=';    
    
    $mail->setFrom($mail_setFromEmail, "My Creative Portfolio");
    $mail->addReplyTo($mail_setFromEmail);
    $mail->addAddress($mail_addAddress);
    
    
    
    $message = file_get_contents($template);
    
    
    // $clave=$_SESSION["clave"];

    // $url_base= 'http://localhost/Modelamieto-Proyecto/changePassword.php';
    
    // $url_enviar = $url_base . '?primaryToken=' . urlencode($clave);

    // $token = $_SESSION["token"]; // Genera un token único

    
    // $url_enviar_con_token = $url_enviar . '&secondaryToken=' . urlencode($token);
    $message = str_replace('{{contraseña}}',"Hola", $message);
    
    

    
    
    $mail->isHTML(true);
    $mail->Subject = $mail_subject;
    $mail->msgHTML($message);
    
    
    
    
    
    if (!$mail->send()) {
        echo '<p style="color:red">No se pudo enviar el mensaje..';
        echo 'Error de correo: ' . $mail->ErrorInfo;
        echo "</p>";
    } else {
        echo '<p style="color:green">Mensaje enviado!</p>';
    }







?>