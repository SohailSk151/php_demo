<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



function send_mail($name, $email, $subject, $message) {    
    $mail = new PHPMailer(true);
    try {
    
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';              
        $mail->SMTPAuth   = true;                         
        $mail->Username   = 'shaiksohail0726@gmail.com';         
        $mail->Password   = 'txxm poaj lxbd wpve';           
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587;                           

        
        $mail->setFrom('shaiksohail0726@gmail.com', 'Sohail Shaik');
        $mail->addAddress($email, $name); 

        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Message has been sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
