<?php
$link = mysqli_connect('localhost', 'root', '', '4coder-beta');
mysqli_set_charset($link, 'utf8');
if (mysqli_connect_errno()) {
    echo 'Ошибка в подключении в бд';
    exit();
}
