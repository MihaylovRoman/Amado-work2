<?php
session_start();
require_once ("../db/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM User WHERE login = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();
    
    if ($user) {
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['login'];

        
        header('Location: ../pages/main.php');
        exit;
    } else {
    
        $_SESSION['error'] = 'Неверный логин или пароль';

        header('Location: ../index.php');
        exit;
    }
}





?>