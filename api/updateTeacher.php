<?php
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Не авторизован']);
    exit;
}

$classId = isset($_POST['classId']) ? (int) $_POST['classId'] : 0;
$teacherId = isset($_POST['teacherId']) ? (int) $_POST['teacherId'] : 0;

if ($classId <= 0 || $teacherId <= 0) {
    echo json_encode(['error' => 'Некорректные данные']);
    exit;
}

// Обновляем классного преподавателя
$query = 'UPDATE Class SET teacherId = :teacherId WHERE id = :classId';
$stmt = $pdo->prepare($query);
if ($stmt->execute(['teacherId' => $teacherId, 'classId' => $classId])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Ошибка при обновлении преподавателя']);
}
?>