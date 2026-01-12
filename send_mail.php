<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/wvgrhpew/_subdomains/api/PHPMailer-7.0.1/src/Exception.php';
require '/home/wvgrhpew/_subdomains/api/PHPMailer-7.0.1/src/PHPMailer.php';
require '/home/wvgrhpew/_subdomains/api/PHPMailer-7.0.1/src/SMTP.php';

function smtp_mail($email_to, $subject, $message, $from = "test", $port = 465){
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host             = 'api.exal-script.ro'; // Serverul tau SMTP
        $mail->SMTPAuth         = true;
        $mail->Username         = 'mailbox@api.exal-script.ro';
        $mail->Password         = 'Gn~P+cc(Y}y}';
        //$mail->Password       = 'gvsc xnmd xcxd rpmp'; //gmail app
        if($port == 587){
            $mail->SMTPSecure   = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port         = 587;
        } else {
            $mail->SMTPSecure   = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port         = 465;
        }
        
        $mail->setFrom('mailbox-'.$from.'@api.exal-script.ro', 'MailBox '.ucfirst($from));
        $mail->addAddress($email_to);
    
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
        $mail->send();
        echo "Mesajul a fost trimis! " . date("d-m-Y H:i:s");
        return true;
    } catch (Exception $e) {
        echo "Mesajul nu a putut fi trimis. Eroare: {$mail->ErrorInfo}";
        return false;
    }
}
