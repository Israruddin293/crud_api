<?php
$host = "localhost";
$db_name = "crud_api";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
    )";
    $conn->exec($sql);

    // Add missing columns if they do not exist
    $sql = "ALTER TABLE users 
            ADD COLUMN IF NOT EXISTS password VARCHAR(255) NOT NULL,
            ADD COLUMN IF NOT EXISTS role ENUM('admin', 'user') NOT NULL DEFAULT 'user'";
    $conn->exec($sql);
} catch(PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
?>
