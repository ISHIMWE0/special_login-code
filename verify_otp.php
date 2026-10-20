
<?php

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/config.php';

function respond(int $status, array $data): never
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, [
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}

try {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $otp = trim($_POST['otp'] ?? '');

    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !preg_match('/^\d{6}$/', $otp)
    ) {
        respond(400, [
            'success' => false,
            'message' => 'Enter a valid email and six-digit OTP.'
        ]);
    }

    $otps = $database->selectCollection('login_otps');

    $record = $otps->findOne([
        'email' => $email,
        'used' => false
    ]);

    if ($record === null) {
        respond(400, [
            'success' => false,
            'message' => 'OTP not found or already used. Please request a new one.'
        ]);
    }

    // Check the number of attempts.
    if (($record->attempts ?? 0) >= 5) {
        $otps->deleteOne(['_id' => $record->_id]);

        respond(429, [
            'success' => false,
            'message' => 'Too many attempts. Please request a new OTP.'
        ]);
    }

    // Check expiry time.
    $now = new MongoDB\BSON\UTCDateTime();

    if ($record->expires_at->toDateTime() < $now->toDateTime()) {
        $otps->deleteOne(['_id' => $record->_id]);

        respond(400, [
            'success' => false,
            'message' => 'Your OTP has expired. Please request a new one.'
        ]);
    }

    // Compare the entered OTP with the stored hash.
    if (!password_verify($otp, $record->otp_hash)) {
        $otps->updateOne(
            ['_id' => $record->_id],
            ['$inc' => ['attempts' => 1]]
        );

        respond(400, [
            'success' => false,
            'message' => 'Incorrect OTP. Please check your email and try again.'
        ]);
    }

    // Find the registered user.
    $users = $database->selectCollection('users');

    $user = $users->findOne([
        'email' => $email
    ]);

    if ($user === null) {
        $otps->deleteOne(['_id' => $record->_id]);

        respond(404, [
            'success' => false,
            'message' => 'Account not found. Please sign up first.'
        ]);
    }

    // Consume the OTP before granting access.
    $consumed = $otps->deleteOne([
        '_id' => $record->_id,
        'used' => false
    ]);

    if ($consumed->getDeletedCount() !== 1) {
        respond(400, [
            'success' => false,
            'message' => 'OTP already used. Request a new one.'
        ]);
    }

    // Start the authenticated session.
    session_regenerate_id(true);

    $_SESSION['user_id'] = (string) $user->_id;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_email'] = $user->email;

    respond(200, [
        'success' => true,
        'message' => 'Login successful!',
        'redirect' => 'dashboard.php'
    ]);

} catch (Throwable $e) {
    error_log('OTP verification error: ' . $e->getMessage());

    respond(500, [
        'success' => false,
        'message' => 'Verification failed. Please try again.'
    ]);
}
