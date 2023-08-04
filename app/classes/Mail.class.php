<?php

use PHPMailer\PHPMailer\PHPMailer;

require('vendor/autoload.php');

class Mail
{
    public static function getMailer($fromAddress,$fromName = "Admin") : PHPMailer
    {
        $mail = new PHPMailer();    
        $mail->isSMTP();                                            
        $mail->Host       = 'sandbox.smtp.mailtrap.io';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = '01dda3fee8e1e0';                
        $mail->Password   = '2efd3355d78d1e';             
        $mail->Port       = 2525;                              
        $mail->SMTPSecure = 'tls'; 
        // $mail->Subject = 'Password Recovery';  
        $mail->isHTML(true); 
        $mail->setFrom('admin@shadabkhan.com', 'Mailer');

        return $mail;
    }
}

?>