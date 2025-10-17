<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

function send_mail($name, $email, $subject, $message) {    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';              // Gmail SMTP server
        $mail->SMTPAuth   = true;                          // Enable SMTP authentication
        $mail->Username   = 'shaiksohail0726@gmail.com';         // Your Gmail address
        $mail->Password   = 'txxm poaj lxbd wpve';           // Your Gmail App Password (NOT your regular password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption
        $mail->Port       = 587;                           // TCP port for TLS

        // Recipients
        $mail->setFrom('shaiksohail0726@gmail.com', 'Sohail Shaik');
        $mail->addAddress($email, $name); // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo '✅ Message has been sent successfully';
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
