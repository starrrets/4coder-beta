<?php
session_start();
require_once 'db.php';

$users = mysqli_fetch_all(mysqli_query($link, "SELECT * FROM `users`"));

$mail = $_POST['mail'];
$password = md5($_POST['pass'] . 'sU#9Ud%7@fWB^JBo^MCo');

$check_user = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$mail' AND `password` = '$password'");
if (mysqli_num_rows($check_user) > 0) {
    $user = mysqli_fetch_assoc($check_user);
    setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
    header('Location: ../education/index.php');
} else {
    $check_email = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$mail'");
    if (mysqli_num_rows($check_email) > 0) {
        $_SESSION['message'] = 'Введен неверный пароль';
        header('Location: ../index.php');
    } else {
        $username = 'user-' . (sizeof($users) + 1);
        if (mysqli_query($link, "INSERT INTO `users` (`id`, `email`, `password`, `username`) VALUES (NULL, '$mail', '$password','$username')")) {
            $user = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$mail'");
            setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
            header('Location: ../education/index.php');
        } else {
            $_SESSION['message'] = 'Ошибка при регистрации, попробуйте еще раз.';
            header('Location: ../index.php');
        }
    }
}
