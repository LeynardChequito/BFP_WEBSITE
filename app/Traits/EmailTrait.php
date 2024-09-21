<?php

namespace App\Traits;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

trait EmailTrait
{
    public function sendEmail($to, $subject, $message, $attachmentPath = null)
    {
        try {
            log_message('debug', 'sendEmail method called with parameters: ' . json_encode(compact('to', 'subject', 'message', 'attachmentPath')));

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gmail SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'bfpcalapancity@gmail.com'; // Your email address
            $mail->Password = 'ggtw yfhn kxqj bvut'; // App password for Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
            $mail->Port = 587; // TLS port

            // Recipients
            $mail->setFrom('bfpcalapancity@gmail.com', 'Admin_BFP_Calapan');
            $mail->addAddress($to); // Add recipient

            if ($attachmentPath && file_exists($attachmentPath)) {
                $cid = $mail->addEmbeddedImage($attachmentPath, 'qr-code-cid', 'QRCode.png');
                $message .= '<br><img src="cid:qr-code-cid">';
                log_message('debug', 'Attachment added: ' . $attachmentPath);
            } elseif ($attachmentPath) {
                log_message('error', "Attachment file does not exist: $attachmentPath");
                throw new Exception("Attachment file does not exist: $attachmentPath");
            }
            // Email content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            log_message('debug', 'Email sent successfully.');
            return true;
        } catch (Exception $e) {
            log_message('error', 'Mailer Error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
