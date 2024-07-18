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
            $mail->Host = 'smtp.gmail.com'; // Use your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'bfpcalapancity@gmail.com';
            $mail->Password = 'ggtw yfhn kxqj bvut';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption - ssl or tls
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('bfpcalapancity@gmail.com', 'Admin_BFP_Calapan');
            $mail->addAddress($to); // Add a recipient, passed via method parameter
            log_message('debug', 'Recipient added: ' . $to);

            // Add attachment if exists
            if ($attachmentPath && file_exists($attachmentPath)) {
                $cid = $mail->addEmbeddedImage($attachmentPath, 'qr-code-cid', 'QRCode.png');
                $message .= '<br><img src="cid:qr-code-cid">';
                log_message('debug', 'Attachment added: ' . $attachmentPath);
            } elseif ($attachmentPath) {
                log_message('error', "Attachment file does not exist: $attachmentPath");
                throw new Exception("Attachment file does not exist: $attachmentPath");
            }

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject; // Use the $subject parameter
            $mail->Body = $message; // Use the $message parameter

            $mail->send();
            log_message('debug', 'Email sent successfully');
            return true; // Return true on success
        } catch (Exception $e) {
            log_message('error', 'Mailer Error: ' . $mail->ErrorInfo); // Log error instead of printing
            return false; // Return false on error
        }
    }
}
