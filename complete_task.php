<?php
require 'db.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    // Update status is_completed
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    header('Location: index.php');
}
