<?php
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Не авторизован']);
    exit;
}

$surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$patronymic = isset($_POST['patronymic']) ? trim($_POST['patronymic']) : '';
$birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
$classId = isset($_POST['classId']) ? (int) $_POST['classId'] : 0;



if (empty($surname) || empty($name) || empty($patronymic) || empty($birthday) || $classId <= 0) {
    echo json_encode(['error' => 'Некорректные данные']);
    exit;
}

if (strtotime($birthday) > time()) {
    echo json_encode(['error' => 'Дата рождения не может быть больше сегодняшней даты']);
    exit;
}

if (preg_match('/\d/', $surname) || preg_match('/\d/', $name) || preg_match('/\d/', $patronymic)) {
    echo json_encode(['error' => 'Фамилия, имя и отчество не должны содержать цифр']);
    exit;
}

$query = 'SELECT COUNT(*) FROM Student WHERE surname = :surname AND name = :name AND patronymic = :patronymic AND birthday = :birthday';
$stmt = $pdo->prepare($query);
$stmt->execute(['surname' => $surname, 'name' => $name, 'patronymic' => $patronymic, 'birthday' => $birthday]);
$exists = $stmt->fetchColumn();

if ($exists > 0) {
    echo json_encode(['error' => 'Студент с такими данными уже существует']);
    exit;
}

$query = 'INSERT INTO Student (surname, name, patronymic, birthday) VALUES (:surname, :name, :patronymic, :birthday)';
$stmt = $pdo->prepare($query);
if ($stmt->execute(['surname' => $surname, 'name' => $name, 'patronymic' => $patronymic, 'birthday' => $birthday])) {

    $studentId = $pdo->lastInsertId();

    $query = 'INSERT INTO ClassList (classId, studentId) VALUES (:classId, :studentId)';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['classId' => $classId, 'studentId' => $studentId]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Ошибка при добавлении ученика']);
}
?>