
<?php

use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

function sendOtpResponse(int $status, array $data): never
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendOtpResponse(405, [
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}

$email = '';

try {
    // Get the values entered on the login form.
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendOtpResponse(400, [
            'success' => false,
            'message' => 'Enter your registered name and email.'
        ]);
    }

    // Check whether the account exists in MongoDB.
    $users = $database->selectCollection('users');

    $user = $users->findOne([
        'name' => $name,
        'email' => $email
    ]);

    if ($user === null) {
        sendOtpResponse(404, [
            'success' => false,
            'message' => 'Account not found. Check your name and email or sign up first.'
        ]);
    }

    // Generate a six-digit OTP.
    $otp = (string) random_int(100000, 999999);

    $otps = $database->selectCollection('login_otps');

    // Remove any previous OTP for this email.
    $otps->deleteMany(['email' => $email]);

    // Save a hashed OTP that expires after 10 minutes.
    $otps->insertOne([
        'email' => $email,
        'otp_hash' => password_hash($otp, PASSWORD_DEFAULT),
        'expires_at' => new MongoDB\BSON\UTCDateTime(
            (time() + 600) * 1000
        ),
        'attempts' => 0,
        'used' => false
    ]);

    try {
        // Send the OTP using your existing Gmail configuration.
        $mail = new PHPMailer(true);

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

        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your Login OTP';

        $safeOtp = htmlspecialchars($otp, ENT_QUOTES, 'UTF-8');

        $mail->Body = "
            <h2>Special Login System</h2>
            <p>Your login verification code is:</p>
            <h1>{$safeOtp}</h1>
            <p>This code expires in 10 minutes.</p>
            <p>Please do not share this code with anyone.</p>
        ";

        $mail->AltBody =
            "Your login OTP is {$otp}. It expires in 10 minutes.";

        $mail->send();

    } catch (Throwable $mailError) {
        // Remove the OTP if the email could not be sent.
        $otps->deleteMany(['email' => $email]);
        throw $mailError;
    }

    sendOtpResponse(200, [
        'success' => true,
        'message' => 'OTP sent successfully! Check your email inbox or spam folder.'
    ]);

} catch (Throwable $e) {
    error_log('Send OTP error: ' . $e->getMessage());

    sendOtpResponse(500, [
        'success' => false,
        'message' => 'Could not send the OTP. Please try again.'
    ]);
}
