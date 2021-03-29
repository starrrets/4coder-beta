<?php
session_start();
require_once '../include/db.php';
if (isset($_GET)) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    if (mysqli_query($link, "DELETE FROM `$table` WHERE `id` = '$id'")) {
        header('Location: ./list.php?table=' . $table);
    } else {
        $_SESSION['message'] = 'Ошибка при удалении!';
        header('Location: ./list.php?table=' . $table);
    }
} else {
    $_SESSION['message'] = 'Ошибка!';
    header('Location: ./');
}
