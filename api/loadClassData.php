<?php
session_start();
require_once("../db/db.php");

$classId = isset($_GET['classId']) ? (int) $_GET['classId'] : 0;

if ($classId <= 0) {
    echo 'Ошибка';
    exit;
}

// Получаем учеников
$query = 'SELECT student.id, student.surname, student.name, student.patronymic, student.birthday
          FROM Student student
          JOIN ClassList class ON student.id = class.studentId
          WHERE class.classId = :classId';

$stmt = $pdo->prepare($query);
$stmt->execute(['classId' => $classId]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем классного преподавателя
$query = 'SELECT t.id, t.surname, t.name, t.patronymic 
          FROM Teacher t 
          JOIN Class ct ON t.id = ct.teacherId 
          WHERE ct.id = :classId';

$stmt = $pdo->prepare($query);
$stmt->execute(['classId' => $classId]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['students' => $students, 'teacher' => $teacher]);



?>