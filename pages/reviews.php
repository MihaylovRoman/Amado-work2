<?php
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['user_id'])) {
    echo "Не авторизован";
    exit;
}

// 1. Найти самого младшего ученика из классов, содержащих "2"
$queryYoungestFirstGrader = "
    SELECT surname, name, patronymic, birthday 
    FROM Student 
    WHERE id IN (
        SELECT studentId 
        FROM ClassList 
        WHERE classId IN (
            SELECT id FROM Class WHERE name LIKE '%1%'
        )
    )
    ORDER BY birthday DESC 
    LIMIT 1
";
$stmt = $pdo->query($queryYoungestFirstGrader);
$youngestFirstGrader = $stmt->fetch();

// 2. Подсчитать количество учеников во всех классах, содержащих "2"
$queryCountSecondGraders = "
    SELECT COUNT(*) 
    FROM Student 
    WHERE id IN (
        SELECT studentId 
        FROM ClassList 
        WHERE classId IN (
            SELECT id FROM Class WHERE name LIKE '%2%'
        )
    )
";
$countSecondGraders = $pdo->query($queryCountSecondGraders)->fetchColumn();

// 3. Подсчитать учеников у каждого классного руководителя
$queryCountStudentsByTeacher = "
    SELECT t.surname AS teacher_surname, t.name AS teacher_name, COUNT(s.id) AS student_count
    FROM Teacher t
    LEFT JOIN Class c ON t.id = c.teacherId
    LEFT JOIN ClassList cl ON c.id = cl.classId
    LEFT JOIN Student s ON cl.studentId = s.id
    GROUP BY t.id
";
$stmt = $pdo->query($queryCountStudentsByTeacher);
$studentsByTeacher = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчеты</title>
    <link rel="stylesheet" href="../style/index.css">
</head>

<body>


    <div style="margin: 150px; display: flex; flex-direction: column; align-items: center;">
        <h1 style="text-align: center; text-decoration: underline;">Отчеты</h1>

        <h2>Самый младший первоклассник из школы</h2>
        <?php if ($youngestFirstGrader): ?>
            <table>
                <tr>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Дата рождения</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($youngestFirstGrader['surname']) ?></td>
                    <td><?= htmlspecialchars($youngestFirstGrader['name']) ?></td>
                    <td><?= htmlspecialchars($youngestFirstGrader['patronymic']) ?></td>
                    <td><?= htmlspecialchars($youngestFirstGrader['birthday']) ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>Нет учеников во вторых классах.</p>
        <?php endif; ?>

        <h2>Количество учеников во всех вторых классах</h2>
        <p>Количество учеников: <?= $countSecondGraders ?></p>

        <h2>Количество учеников у каждого классного руководителя</h2>
        <table>
            <tr>
                <th>Фамилия учителя</th>
                <th>Имя учителя</th>
                <th>Количество учеников</th>
            </tr>
            <?php foreach ($studentsByTeacher as $teacher): ?>
                <tr>
                    <td><?= htmlspecialchars($teacher['teacher_surname']) ?></td>
                    <td><?= htmlspecialchars($teacher['teacher_name']) ?></td>
                    <td><?= htmlspecialchars($teacher['student_count']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>

</html>