<?php

$pdo = new PDO('mysql:host=mysql-8.2;dbname=db_application;charset=utf8', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
?>