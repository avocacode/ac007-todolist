<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

foreach ($data as $item) {
    $stmt = $pdo->prepare("UPDATE tasks SET position = ? WHERE id = ?");
    $stmt->execute([$item['position'], $item['id']]);
}
