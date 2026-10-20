
<?php

require_once __DIR__ . '/vendor/autoload.php';

try {
    $client = new MongoDB\Client(
        "mongodb://127.0.0.1:27017"
    );

    $database = $client->selectDatabase("special_login");

    // Test the database connection
    $database->command(['ping' => 1]);
} catch (Throwable $e) {
    error_log("MongoDB connection error: " . $e->getMessage());

    http_response_code(500);
    exit("Database connection failed. Please check your MongoDB server.");
}
$mailConfig = require 'C:/xampp/private_config/mail_config.php';
