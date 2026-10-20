
<?php

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
    // Get the submitted name and email.
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));

    // Validate the information.
    if (
        $name === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        respond(400, [
            'success' => false,
            'message' => 'Enter a valid name and email.'
        ]);
    }

    // Connect to the existing users collection.
    $users = $database->selectCollection('users');

    // Check whether this email is already registered.
    $existingUser = $users->findOne([
        'email' => $email
    ]);

    if ($existingUser !== null) {
        respond(409, [
            'success' => false,
            'message' =>
            'This email already has an account. Please log in.'
        ]);
    }

    // Create the account without sending an OTP.
    $users->insertOne([
        'name' => $name,
        'email' => $email,
        'emailVerified' => false,
        'createdAt' => new \MongoDB\BSON\UTCDateTime()
    ]);

    respond(201, [
        'success' => true,
        'message' =>
        'Account created successfully! Please log in.'
    ]);
} catch (\Throwable $e) {
    error_log('Signup error: ' . $e->getMessage());

    respond(500, [
        'success' => false,
        'message' =>
        'Account creation failed. Please try again.'
    ]);
}
