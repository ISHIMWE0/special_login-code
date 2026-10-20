
<?php

echo "PHP version: " . PHP_VERSION . "<br>";

echo "Loaded php.ini: " .
    (php_ini_loaded_file() ?: "None") . "<br>";

echo "MongoDB extension loaded: ";
echo extension_loaded('mongodb') ? "YES" : "NO";

echo "<br>";

echo "UTCDateTime class available: ";
echo class_exists('MongoDB\\BSON\\UTCDateTime')
    ? "YES"
    : "NO";
