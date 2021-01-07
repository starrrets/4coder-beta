<?php
require_once 'variables.php';
function socialRegistration($link, $users, $social_name, $id, $email, $avatar, $location)
{
    $social_id = $social_name . '_id';
    $social_email = $social_name . '_email';
    $get_user = mysqli_query($link, "SELECT * FROM `users` WHERE `$social_id`='$id'");

    if (mysqli_num_rows($get_user) > 0) {
        $user = mysqli_fetch_assoc($get_user);
        setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
        header('Location: ' . $location);
        exit;
    } else {
        $get_user_by_email = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$email'");
        if (mysqli_num_rows($get_user_by_email) > 0) {
            $user = mysqli_fetch_assoc($get_user_by_email);
            $user_id = $user["id"];
            if (mysqli_query($link, "UPDATE `users` SET `$social_id` = '$id', `$social_email` = '$email' , `avatar` = '$avatar'  WHERE `users`.`id` = $user_id;")) {
                $user[$social_id] = $id;
                $user[$social_email] = $email;
                setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
                header('Location: ' . $location);
            } else {
                $_SESSION['message'] = 'Ошибка при авторизации, попробуйте еще раз.';
                header('Location: ' . $location);
            }
        } else {
            $username = 'user-' . (sizeof($users) + 1);
            if (mysqli_query($link, "INSERT INTO `users` (`id`, `email`,`$social_id`,`$social_email` , `username`, `avatar`) VALUES (NULL, '$email', '$id','$email','$username','$avatar')")) {
                $user = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$email'"));
                setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
                header('Location: ' . $location);
            } else {
                $_SESSION['message'] = 'Ошибка при входе, попробуйте еще раз.';
                header('Location: ' . $location);
            }
        }
    }
}

function addSocialAccount($link, $user, $social_name, $id, $email, $avatar)
{
    $social_id = $social_name . '_id';
    $social_email = $social_name . '_email';
    $user_id = $user["id"];
    if (mysqli_query($link, "UPDATE `users` SET `$social_id` = '$id', `$social_email` = '$email' , `avatar` = '$avatar'  WHERE `users`.`id` = $user_id;")) {
        $user[$social_id] = $id;
        $user[$social_email] = $email;
        $user['avatar'] = $avatar;
        setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
        header('Location: /profile/');
    } else {
        $_SESSION['message'] = 'Ошибка при подключении аккаунта, попробуйте еще раз.';
        header('Location: /profile/');
    }
}

function deleteSocialAccount($link, $user, $social_name)
{
    $social_id = $social_name . '_id';
    $social_email = $social_name . '_email';
    $user_id = $user["id"];
    if (mysqli_query($link, "UPDATE `users` SET `$social_id` = 'NULL', `$social_email` = 'NULL'  WHERE `users`.`id` = $user_id;")) {
        $user[$social_id] = NULL;
        $user[$social_email] = NULL;
        setcookie('user', json_encode($user), time() + 3600 * 24 * 7, "/");
        header('Location: /profile/');
    } else {
        $_SESSION['message'] = 'Ошибка при отключении аккаунта, попробуйте еще раз.';
        header('Location: ../profile/index.php');
    }
}
