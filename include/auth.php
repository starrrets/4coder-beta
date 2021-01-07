<?php
session_start();
require_once 'db.php';
require_once 'auth_functions.php';
require_once 'variables.php';
require 'google-api/vendor/autoload.php';

$users = mysqli_fetch_all(mysqli_query($link, "SELECT * FROM `users`"));
$location = ' /home/ ';
if (isset($_POST['page'])) {
    $location = $_POST['page'];
} elseif (isset($_COOKIE['registrationPage'])) {
    echo $_COOKIE['registrationPage'];
}
if (isset($_GET['code'])) {
    $client = new Google_Client();
    $client->setClientId(GOOGLE_ID);
    $client->setClientSecret(GOOGLE_SECRET);
    $client->setRedirectUri(GOOGLE_URI);
    $client->addScope("email");
    $client->addScope("profile");

    $google_token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($google_token["error"])) {
        $client->setAccessToken($google_token['access_token']);
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $id = mysqli_real_escape_string($link, $google_account_info->id);
        $email = mysqli_real_escape_string($link, $google_account_info->email);
        $profile_pic = mysqli_real_escape_string($link, $google_account_info->picture);

        socialRegistration($link, $users, 'google', $id, $email, $profile_pic, $location);
    } else {
        $client_id = YANDEX_ID;
        $client_secret = YANDEX_SECRET;
        $redirect_uri = YANDEX_URI;
        $params = array(
            'grant_type'    => 'authorization_code',
            'code'          => $_GET['code'],
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri'  => $redirect_uri
        );

        $url = 'https://oauth.yandex.ru/token';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);

        $yandex_token = json_decode($result, true);
        if (!empty($yandex_token['access_token'])) {
            $curl = curl_init('https://login.yandex.ru/info');
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('format' => 'json'));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $yandex_token['access_token']));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $info = curl_exec($curl);
            curl_close($curl);

            $info = json_decode($info, true);
            $id = $info['id'];
            $email = $info['default_email'];
            $profile_pic = 'https://avatars.yandex.net/get-yapic/' . $info['default_avatar_id'] . '/islands-200';

            socialRegistration($link, $users, 'yandex', $id, $email, $profile_pic, $location);
        } else {
            $_SESSION['message'] = 'Ошибка при входе, попробуйте еще раз.';
            header('Location: ' . $location);
        }
    }
} else {
    $mail = $_POST['mail'];
    $password = md5($_POST['pass'] . 'sU#9Ud%7@fWB^JBo^MCo');
    $check_user = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$mail' AND `password` = '$password'");
    if (mysqli_num_rows($check_user) > 0) {
        $user = mysqli_fetch_assoc($check_user);
        setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
        header('Location: ' . $location);
    } else {
        $check_email = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$mail'");
        if (mysqli_num_rows($check_email) > 0) {
            $user = mysqli_fetch_assoc($check_email);
            if ($user["password"] == NULL) {
                $_SESSION['message'] = 'Пароль не установлен. Войдите через аккаунт социальной сети, которым вы воспользовались в прошлый раз, и установите пароль в профиле.';
            } else {
                $_SESSION['message'] = 'Введен неверный пароль';
            }
            header('Location: ' . $location);
        } else {
            $username = 'user-' . (sizeof($users) + 1);
            if (mysqli_query($link, "INSERT INTO `users` (`id`, `email`, `password`, `username`) VALUES (NULL, '$mail', '$password','$username')")) {
                $user = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$mail'"));
                setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
                header('Location: ' . $location);
            } else {
                $_SESSION['message'] = 'Ошибка при регистрации, попробуйте еще раз.';
                header('Location: ' . $location);
            }
        }
    }
}
