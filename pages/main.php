<?php
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$query = 'SELECT id, name FROM Class';
$stmt = $pdo->prepare($query);
$stmt->execute();

$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = 'SELECT id, surname, name, patronymic FROM Teacher';
$stmt = $pdo->prepare($query);
$stmt->execute();


$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <title>Main</title>
</head>

<body>
    <div class="wrapper">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error"><p>' . $_SESSION['error'] . '</p></div>';
            unset($_SESSION['error']);
        }
        ?>
        <a class="exit" href="../api/logout.php">
            Выход
        </a>
        <div class="main">
            <div class="main-classes">
                <div>
                    <div>
                        <span class="classes-tag">Классы</span>
                        <select class="classes-select" id="classSelector">
                            <option value="">Выберите класс</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= htmlspecialchars($class['id']) ?>">
                                    <?= htmlspecialchars($class['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <select class="classes-select" style="width: 50%; margin-top: 20px; opacity: 0"
                        id="teacherSelector">
                        <option value="">Выберите преподавателя</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= htmlspecialchars($teacher['id']) ?>">
                                <?= htmlspecialchars($teacher['surname']) . ' ' . htmlspecialchars($teacher['name']) . ' ' . htmlspecialchars($teacher['patronymic']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <table>
                        <thead>
                            <tr>
                                <th>ФИО ученика</th>
                                <th>Дата рождения</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="line"></div>

            <div class="main-form">
                <div>
                    <p class="form-tag">Добавление ученика</p>
                    <div>
                        <div class="block-input">
                            <p>Фамилия</p>
                            <input class="block-input_input" id="studentSurname" type="text">
                        </div>
                        <div class="block-input">
                            <p>Имя</p>
                            <input class="block-input_input" id="studentName" type="text">
                        </div>
                        <div class="block-input">
                            <p>Отчество</p>
                            <input class="block-input_input" id="studentPatronymic" type="text">
                        </div>
                    </div>
                    <div class="block-trio">
                        <div class="block-input">
                            <p>Дата рождения</p>
                            <input class="block-input_input" id="studentBirthday" maxlength="10" style="width:85%" type="date">
                        </div>
                        <div class="block-input">
                            <p>Выбор класса</p>
                            <select class="classes-select" id="studentClassSelector" style="width:85%">
                                <option value="">Выберите класс</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= htmlspecialchars($class['id']) ?>">
                                        <?= htmlspecialchars($class['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" id="addStudentButton" class="form-button">Добавить</button>
                </div>

                <a class="form-button" href="reviews.php" style="margin-top: 20px">Сформировать отчеты</a>
            </div>
        </div>
    </div>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {


            function changeClass() {
                $('#tbody').empty();
                var classId = $('#classSelector').val();
                if (classId) {
                    $.ajax({
                        url: '../api/loadClassData.php',
                        type: 'GET',
                        data: { classId: classId },
                        dataType: 'json',
                        success: function (res) {

                            res.students.forEach(function (student) {
                                var studentRow = $('<tr>').attr('data-student-id', student.id);;
                                studentRow.html(`
                            <td>${student.surname} ${student.name} ${student.patronymic}</td>
                            <td class="actions">${student.birthday}</td>
                            <td class="actions"><button class="delete-btn">Удалить</button></td>
                        `);

                                $('#tbody').append(studentRow);

                            });
                            $('#teacherSelector').css('opacity', '1');
                            if (res.teacher) {
                                $('#teacherSelector').val(res.teacher.id);

                            } else {
                                $('#teacherSelector').val('');
                            }


                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error("Ошибка AJAX:", textStatus, errorThrown);
                            alert('Произошла ошибка при загрузке учеников.');
                        }
                    })

                }
                else {
                    $('#teacherSelector').val('');
                    $('#teacherSelector').css('opacity', '0')
                }
            }

            function updateTeacher() {
                let classId = $('#classSelector').val()
                let teacherId = $('#teacherSelector').val();

                if (classId && teacherId) {

                    $.ajax({
                        url: '../api/updateTeacher.php',
                        type: 'POST',
                        data: { classId: classId, teacherId: teacherId },
                        dataType: 'json',
                        success: function (res) {
                            if (res.success) {
                                changeClass();
                                alert('Преподаватель успешно изменен.');
                            } else {
                                alert('Ошибка: ' + res.error);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error("Ошибка AJAX:", textStatus, errorThrown);
                            alert('Произошла ошибка при загрузке учеников.');
                        }
                    })

                }
            }

            function deleteStudent(studentId) {
                if (confirm('Вы уверены, что хотите удалить этого ученика?')) {
                    $.ajax({
                        url: '../api/deleteStudent.php',
                        type: 'POST',
                        data: { studentId: studentId },
                        dataType: 'json',
                        success: function (res) {
                            if (res.success) {
                                alert('Ученик успешно удален.');
                                changeClass();
                            } else {
                                alert('Ошибка: ' + res.error);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error("Ошибка AJAX:", textStatus, errorThrown);
                            alert('Произошла ошибка при удалении ученика.');
                        }
                    });
                }
            }

            function addStudent() {
                var surname = $('#studentSurname').val();
                var name = $('#studentName').val();
                var patronymic = $('#studentPatronymic').val();
                var birthday = $('#studentBirthday').val();
                var classId = $('#studentClassSelector').val();

                // Проверка на заполненность полей
                if (!surname || !name || !patronymic || !birthday || !classId) {
                    alert('Пожалуйста, заполните все поля.');
                    return;
                }

                // Отправка данных на сервер с помощью AJAX
                $.ajax({
                    url: '../api/addStudent.php',
                    type: 'POST',
                    data: {
                        surname: surname,
                        name: name,
                        patronymic: patronymic,
                        birthday: birthday,
                        classId: classId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            alert('Ученик успешно добавлен!');
                            changeClass()
                            $('#studentSurname').val('');
                            $('#studentName').val('');
                            $('#studentPatronymic').val('');
                            $('#studentBirthday').val('');
                            $('#studentClassSelector').val('');
                            
                        } else {
                            alert('Ошибка: ' + response.error);
                        }
                    },
                    error: function () {
                        alert('Произошла ошибка при отправке данных на сервер.');
                    }
                });


            }
            $(document).on('click', '.delete-btn', function () {

                var studentRow = $(this).closest('tr');
                var studentId = studentRow.data('student-id');

                deleteStudent(studentId);
            });
            $('#addStudentButton').click(addStudent)

            $('#teacherSelector').change(updateTeacher)
            $('#classSelector').change(changeClass)
        })
    </script>
</body>

</html>