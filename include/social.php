<?php
session_start();
require_once 'db.php';
require_once 'auth_functions.php';
require 'google-api/vendor/autoload.php';
$user = json_decode($_COOKIE['user'], true);

if (isset($_GET['code'])) {
    $client = new Google_Client();
    $client->setClientId(GOOGLE_ID);
    $client->setClientSecret(GOOGLE_SECRET);
    $client->setRedirectUri(GOOGLE_SECONDARY_URI);
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

        addSocialAccount($link, $user, 'google', $id, $email, $profile_pic);
    } else {
        $client_id = YANDEX_ID;
        $client_secret = YANDEX_SECRET;
        $redirect_uri = YANDEX_SECONDARY_URI;
        $params = array(
            'grant_type'    => 'authorization_code',
            'code'          => $_GET['code'],
            'client_id'     => $client_id,
            'client_secret' => $client_secret
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

            addSocialAccount($link, $user, 'yandex', $id, $email, $profile_pic);
        } else {
            $_SESSION['message'] = 'Ошибка при подключении аккаунта, попробуйте еще раз.';
            header('Location: ../profile/index.php');
        }
    }
} elseif (isset($_SESSION['delete-social'])) {
    if ($user['password'] != NULL) {
        switch ($_SESSION['delete-social']) {
            case 'google':
                deleteSocialAccount($link, $user, 'google');
                break;

            case 'yandex':
                deleteSocialAccount($link, $user, 'yandex');
                break;
            default:
                break;
        }
    } else {
        $_SESSION['message'] = 'Ошибка при отключении аккаунта. Добавите пароль перед отключением аккаунта.';
    }
    unset($_SESSION['delete-social']);
    header('Location: ../profile/index.php');
} else {
    $_SESSION['message'] = 'Ошибка при подключении аккаунта, попробуйте еще раз.';
    header('Location: ../profile/index.php');
}
