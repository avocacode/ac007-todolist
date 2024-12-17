<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskName = $_POST['task_name'];

    // Dapatkan posisi terakhir
    $stmt = $pdo->query("SELECT MAX(position) AS max_position FROM tasks");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $position = $result['max_position'] + 1;

    // Masukkan tugas baru
    $stmt = $pdo->prepare("INSERT INTO tasks (task_name, position) VALUES (?, ?)");
    $stmt->execute([$taskName, $position]);

    header('Location: index.php');
}
