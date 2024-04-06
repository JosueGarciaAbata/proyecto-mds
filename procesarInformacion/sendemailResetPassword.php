<?php

require '../dist/libs/PHPMailer/PHPMailerAutoload.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST["user_email"])){


        $mail_setFromEmail = $_POST["user_email"];
        $mail_setFromName = "User";
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
        
        $code = bin2hex(random_bytes(16));
        
    
        
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        $_SESSION["code"]=$code;
        
        $message = file_get_contents($template);
        
    
        $message = str_replace('{{codigo}}',$code, $message);
        
        
    
        
        
        $mail->isHTML(true);
        $mail->Subject = $mail_subject;
        $mail->msgHTML($message);
        
        
        
        
        
        if ($mail->send()) {
            // Retorna true si el correo se envió correctamente
            echo json_encode(true);
        } else {
            // Retorna false si hubo un error al enviar el correo
            echo json_encode(false);
        }
    
    }

}









?>