<?php
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Не авторизован']);
    exit;
}

$studentId = isset($_POST['studentId']) ? (int) $_POST['studentId'] : 0;

if ($studentId <= 0) {
    echo json_encode(['error' => 'Некорректный ID ученика']);
    exit;
}

$query = 'DELETE FROM Student WHERE id = :studentId';
$stmt = $pdo->prepare($query);
if ($stmt->execute(['studentId' => $studentId])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Ошибка при удалении ученика']);
}
?>