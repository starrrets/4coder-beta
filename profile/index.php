<?php
if (!$_COOKIE['user']) {
    header('Location: /');
}
require_once '../includes/functions.php';
$user = json_decode($_COOKIE['user'], true);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../static/css/secondary.min.css">
    <title>Профиль - coderley</title>
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
        <?php
        print_sidebar('');
        ?>
        <div id="wrapper__block-elements"></div>
        <?php print_header($user); ?>
        <main id="main" class="profile">
            <div id="user_info">
                <img id="user_info__avatar" src="/static/img/user.png">
                <h2 id="user_info__username">user-n</h2>
                <p id="user_info__email">asd@asdasd.asd</p>
                <div id="user_info__social_buttons"><button class="user_info__social_registration" id="google">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.3206 8.18111H10V12.0439H15.3638C14.8646 14.4985 12.7748 15.9089 10 15.9089C6.72823 15.9089 4.09111 13.2718 4.09111 9.99888C4.09111 6.72711 6.72823 4.08999 10 4.08999C11.4092 4.08999 12.683 4.59033 13.6826 5.40855L16.5928 2.49944C14.8198 0.95366 12.5465 0 10 0C4.45489 0 0 4.45377 0 10C0 15.5462 4.45377 20 10 20C15 20 19.5467 16.3633 19.5467 10C19.5467 9.409 19.456 8.77211 19.3206 8.18111Z" fill="white" />
                        </svg>
                    </button>
                    <button class="user_info__social_registration" id="vk">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.07 0H6.93C1.33 0 0 1.33 0 6.93V13.07C0 18.67 1.33 20 6.93 20H13.07C18.67 20 20 18.67 20 13.07V6.93C20 1.33 18.67 0 13.07 0ZM16.15 14.27H14.69C14.14 14.27 13.97 13.82 13 12.83C12.12 12 11.74 11.88 11.53 11.88C11.24 11.88 11.15 11.96 11.15 12.38V13.69C11.15 14.04 11.04 14.26 10.11 14.26C8.57 14.26 6.86 13.32 5.66 11.59C3.85 9.05 3.36 7.13 3.36 6.75C3.36 6.54 3.43 6.34 3.85 6.34H5.32C5.69 6.34 5.83 6.5 5.97 6.9C6.69 9 7.89 10.8 8.38 10.8C8.57 10.8 8.65 10.71 8.65 10.25V8.1C8.6 7.12 8.07 7.03 8.07 6.68C8.07 6.5 8.21 6.34 8.44 6.34H10.73C11.04 6.34 11.15 6.5 11.15 6.88V9.77C11.15 10.08 11.28 10.19 11.38 10.19C11.56 10.19 11.72 10.08 12.05 9.74C13.1 8.57 13.85 6.76 13.85 6.76C13.95 6.55 14.11 6.35 14.5 6.35H15.93C16.37 6.35 16.47 6.58 16.37 6.89C16.19 7.74 14.41 10.25 14.43 10.25C14.27 10.5 14.21 10.61 14.43 10.9C14.58 11.11 15.09 11.55 15.43 11.94C16.05 12.65 16.53 13.24 16.66 13.65C16.77 14.06 16.57 14.27 16.15 14.27Z" fill="#808080" />
                        </svg>
                    </button>
                </div>
                <div id="user_info__buttons"><button id="user_info__change_button">Редактировать профиль</button>
                    <a id="user_info__logout" href="/includes/logout.php">Выйти</a></div>

            </div>
            <div class="user_progrss"></div>
        </main>
    </div>
    <script src="../static/js/theme.js"></script>
    <script src="../static/js/menu.js"></script>
</body>

</html>