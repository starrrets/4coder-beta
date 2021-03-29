<?php
session_start();
if (!$_COOKIE['user']) {
    header('Location: /home/');
}
require_once '../include/db.php';
require_once '../include/variables.php';
require_once '../include/functions.php';
$user = json_decode($_COOKIE['user'], true);

// google
require_once '../include/google-api/vendor/autoload.php';
$client = new Google_Client();
$client->setClientId(GOOGLE_ID);
$client->setClientSecret(GOOGLE_SECRET);
$client->setRedirectUri(GOOGLE_SECONDARY_URI);
$client->addScope("email");
$client->addScope("profile");

//yandex
$client_id = YANDEX_ID;
$client_secret = YANDEX_SECRET;
$redirect_uri = YANDEX_SECONDARY_URI;
$url = 'https://oauth.yandex.ru/authorize';
$params = array(
    'client_id'     => $client_id,
    'redirect_uri'  => $redirect_uri,
    'response_type' => 'code'
);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RHYX25Y164"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-RHYX25Y164');
    </script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(69978256, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true
        });
    </script> <noscript>
        <div><img src="https://mc.yandex.ru/watch/69978256" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript> <!-- /Yandex.Metrika counter -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../static/css/secondary.min.css">
    <title>Профиль - coderley</title>
    <meta name="description" content="Профиль <?php echo $user['username']; ?> - coderley">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Rubik', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body>
    <div id="wrapper" class="light">

        <form id="user-edit" action="/include/change.php" method="post">
            <h2>Редактирование профиля</h2>
            <div class="user-edit__avatar-edit">
                <div id="current-avatar" style="background-image: url('<?php echo $user['avatar']; ?>');"></div>
                <button class="edit__input" id="user-edit__avatar-edit__change">Изменить изображение</button>
                <button type="button" id="user-edit__avatar-edit__delete">
                    <svg width="20px" height="20px" version="1.1" viewBox="0 0 20 20" x="0px" y="0px">
                        <g>
                            <path d="M12 2H8v1H3v2h14V3h-5V2zM4 7v9a2 2 0 002 2h8a2 2 0 002-2V7h-2v9H6V7H4z"></path>
                            <path d="M11 7H9v7h2V7z"></path>
                        </g>
                    </svg>
                </button>
            </div>
            <input type="text" name="avatar" id="avatar" class="edit__input" value='<?php echo $user['avatar']; ?>'>
            <p>
                <label>Имя пользователя</label>
                <input type="text" name="username" id="username" class="edit__input" pattern=".{1,}" required value='<?php echo $user['username']; ?>'>
            </p>
            <?php if ($user['password'] == NULL) {
                echo '<button type="button" class="edit__input" id="password_add">Добавить пароль</button>';
            } else {
                echo '<button type="button" class="edit__input" id="password_change">Обновить пароль</button>';
            } ?>
            <div class="buttons-row">
                <button type="submit" id="user-edit__submit" name="user-edit__submit" class="submit-button">Сохранить изменения</button>
                <button type="button" id="cancel" class="cancel-button">Отмена</button>
            </div>
        </form>

        <div id="avatar-edit">
            <h2>Изменение изображения</h2>
            <p>
                <label>Url</label>
                <input type="url" id="new-avatar" class="edit__input" placeholder='Введите ссылку на изображение'>
            </p>
            <div class="buttons-row">
                <button id="avatar-edit__submit" class="submit-button">Загрузить изображение</button>
                <button id="avatar-cancel" type="button" class="cancel-button">Отмена</button>
            </div>
        </div>

        <form id="password-change" action="/include/change.php" method="post">
            <h2>Изменение пароля</h2>
            <p>
                <label>Старый пароль</label>
                <input type="password" name="old-password" id="old-password" class="edit__input" placeholder='Введите старый пароль' pattern="(?=.*\d)(?=.*[а-яА-ЯёЁa-zA-Z]).{8,}" required>
            </p>
            <p>
                <label>Новый пароль</label>
                <input type="password" name="new-password" id="new-password" class="edit__input" placeholder='Введите новый пароль' pattern="(?=.*\d)(?=.*[а-яА-ЯёЁa-zA-Z]).{8,}" required>
            </p>
            <div class="buttons-row">
                <button id="password-edit__submit" name="password-edit__submit" class="submit-button">Обновить пароль</button>
                <button id="password-cancel" type="button" class="cancel-button">Отмена</button>
            </div>
        </form>
        <form id="password-add" action="/include/change.php" method="post">
            <h2>Добавление пароля</h2>
            <p>
                <label>Пароль</label>
                <input type="password" name="add-password" id="add-password" class="edit__input" placeholder='Введите пароль' pattern="(?=.*\d)(?=.*[а-яА-ЯёЁa-zA-Z]).{8,}" required>
            </p>
            <div class="buttons-row">
                <button id="password-add__submit" name="password-add__submit" class="submit-button">Добавить пароль</button>
                <button id="password-add-cancel" type="button" class="cancel-button">Отмена</button>
            </div>
        </form>
        <?php
        printCookieMessage();
        print_sidebar('');
        ?>
        <div id="wrapper__block-elements"></div>
        <?php print_header($user); ?>
        <main id="main" class="profile">
            <div id="user-info">
                <div id="user-info__avatar" style="background-image: url('<?php echo $user['avatar']; ?>');"></div>

                <div id="user-info__buttons">
                    <?php echo $user['role'] != 'user' ? '<a id="user-info__admin" href="/dashboard/">ADMIN</a>' : ''; ?>
                    <button id="user-info__change_button">Редактировать профиль</button>
                    <a id="user-info__logout" href="/include/logout.php">Выйти</a>
                </div>

            </div>
            <div class="user-info__block">
                <h2 id="user-info__username"><?php echo $user['username']; ?></h2>
                <p id="user-info__email"><?php echo $user['email']; ?></p>
                <p id="user-info__accounts">Подключённые аккаунты:</p>
                <div id="user-info__social_buttons">
                    <a href="<?php if ($user["google_id"] == NULL) {
                                    echo $client->createAuthUrl();
                                } else {
                                    $_SESSION['delete-social'] = 'google';
                                    echo '/include/social.php';
                                } ?>" class="user-info__social_registration <?php echo ($user["google_id"] != NULL ? 'user-info__social_registration__connected' : ''); ?>" id="google">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.3206 8.18111H10V12.0439H15.3638C14.8646 14.4985 12.7748 15.9089 10 15.9089C6.72823 15.9089 4.09111 13.2718 4.09111 9.99888C4.09111 6.72711 6.72823 4.08999 10 4.08999C11.4092 4.08999 12.683 4.59033 13.6826 5.40855L16.5928 2.49944C14.8198 0.95366 12.5465 0 10 0C4.45489 0 0 4.45377 0 10C0 15.5462 4.45377 20 10 20C15 20 19.5467 16.3633 19.5467 10C19.5467 9.409 19.456 8.77211 19.3206 8.18111Z" fill="white" />
                        </svg>
                    </a>
                    <a href="<?php if ($user["yandex_id"] == NULL) {
                                    echo $url . '?' . urldecode(http_build_query($params));
                                } else {
                                    $_SESSION['delete-social'] = 'yandex';
                                    echo '/include/social.php';
                                } ?>" class="user-info__social_registration <?php echo ($user["yandex_id"] != NULL ? 'user-info__social_registration__connected' : ''); ?>" id="yandex">
                        <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.29716 0H6.4556C3.57673 0 0.671807 2.12586 0.671807 6.87523C0.671807 9.33531 1.71442 11.2513 3.62567 12.3409L0.127549 18.6726C-0.0383099 18.9721 -0.042646 19.3114 0.115909 19.5804C0.270713 19.8431 0.553799 20 0.872861 20H2.64243C3.04442 20 3.35786 19.8057 3.50817 19.4653L6.78806 13.05H7.02743V19.2004C7.02743 19.6339 7.39317 20 7.8261 20H9.37196C9.85747 20 10.1964 19.661 10.1964 19.1756V0.875117C10.1965 0.359883 9.82673 0 9.29716 0ZM7.02743 10.2002H6.60517C4.96786 10.2002 3.9904 8.86375 3.9904 6.62512C3.9904 3.84156 5.22517 2.8498 6.38079 2.8498H7.02743V10.2002Z" fill="black" />
                        </svg>

                    </a>
                </div>
            </div>
            <div class="user-progress">
                <div class="user-progress__navbar">
                    <button class="user-progress__navbar__button current">Тесты</button>
                </div>
                <div class="user-progress__block">
                    <?php print_user_results($user, $link); ?>
                </div>
                <div class="user-progress__block"></div>
                <div class="user-progress__block"></div>
            </div>
        </main>
    </div>
    <script src="../static/js/theme.js"></script>
    <script src="../static/js/menu.js"></script>
    <script src="../static/js/alert.js"></script>
    <script src="../static/js/change.js"></script>
    <?php
    if ($_SESSION['message']) {
        echo '<script src="../static/js/alert.js"></script><script>popupAlert("' . $_SESSION['message'] . '");</script>';
        unset($_SESSION['message']);
    }
    ?>
    <?php printCookieMessageScript(); ?>
    <?php
    if ($_SESSION['message']) {
        echo '<script>popupAlert("' . $_SESSION['message'] . '");</script>';
        unset($_SESSION['message']);
    }
    ?>
</body>

</html>