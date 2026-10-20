
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $mailConfig['gmail_address'];
    $mail->Password = $mailConfig['gmail_app_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(
        $mailConfig['gmail_address'],
        'Special Login System'
    );

    $mail->addAddress($mailConfig['gmail_address']);
    $mail->isHTML(true);
    $mail->Subject = 'Special Login System Email Test';
    $mail->Body = '<p>Your Gmail connection is working!</p>';
    $mail->AltBody = 'Your Gmail connection is working!';

    $mail->send();

    echo 'Success! Test email sent.';
} catch (Exception $e) {
    error_log('Email test failed: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo 'Email could not be sent. Check the server error log.';
}
