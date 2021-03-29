<?php
session_start();
require_once './db.php';

if (isset($_GET['role'])) {
    $id = $_GET['id'];
    $role = $_GET['role'];
    if (mysqli_query($link, "UPDATE `users` SET `role` = '$role' WHERE `users`.`id` = $id;")) {
        header('Location: ../dashboard/users.php');
    } else {
        $_SESSION['message'] = 'Ошибка при изменении прав доступа';
        header('Location: ../dashboard/users.php');
    }
}
