<?php
// db.php
$host = 'localhost';
$dbname = 'ac_todo_list';
$username = 'root'; // Sesuaikan dengan username MySQL Anda
$password = '';     // Sesuaikan dengan password MySQL Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
