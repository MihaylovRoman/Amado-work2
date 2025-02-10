<?php 
    session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/index.css">
    <title>PHP</title>
</head>
<body>
    <div class="wrapper">
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="error"><p>' . $_SESSION['error'] . '</p></div>';
        unset($_SESSION['error']);
    }
    ?>
        <form method="POST" class="form-auth" action="./api/auth.php">
            <p class="form-tag">Авторизация</p>
            <div class="block-input">
                <p>Логин:</p>
                <input class="block-input_input" name="username" type="text" required>
            </div>

            <div class="block-input">
                <p>Пароль:</p>
                <input class="block-input_input" name="password" type="password" required>
            </div>

            <button type="submit" class="form-button">Войти</button>
        </form>
    </div>
</body>
</html>