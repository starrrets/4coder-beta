<?php
session_start();

require_once '../include/db.php';
require_once '../include/functions.php';
$user = '';
if ($_COOKIE['user']) {
    $user = json_decode($_COOKIE['user'], true);
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RDEBLZCRX1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-RDEBLZCRX1');
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
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../static/css/secondary.min.css">
    <title>Тесты - coderley</title>
    <meta name="description" content="Тесты - coderley">
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
        printCookieMessage();
        print_sidebar('tests');
        ?>
        <div id="wrapper__block-elements"></div>
        <?php print_header($user); ?>
        <main id="main" class="tests">
            <a href="/tests/list.php?lang=python" class="card python">
                <svg class="card__icon" width="50" height="50" viewBox="0 0 50 50" fill="none">
                    <path d="M42.85 12.5C44.7463 12.5 46.5649 13.2533 47.9058 14.5942C49.2467 15.9351 50 17.7537 50 19.65V29.1C50 30.9963 49.2467 32.8149 47.9058 34.1558C46.5649 35.4967 44.7463 36.25 42.85 36.25H25C25 37.225 25.8 38.65 26.775 38.65H37.5V42.85C37.5 44.7463 36.7467 46.5649 35.4058 47.9058C34.0649 49.2467 32.2463 50 30.35 50H19.65C17.7537 50 15.9351 49.2467 14.5942 47.9058C13.2533 46.5649 12.5 44.7463 12.5 42.85V33.475C12.5 29.525 15.7 26.35 19.65 26.35H32.775C36.725 26.35 39.9 23.15 39.9 19.2V12.5H42.85ZM32.15 41.975C31.15 41.975 30.35 42.725 30.35 44.2C30.35 45.675 31.15 45.975 32.15 45.975C32.3831 45.975 32.6139 45.9291 32.8293 45.8399C33.0446 45.7507 33.2403 45.6199 33.4051 45.4551C33.5699 45.2903 33.7007 45.0946 33.7899 44.8793C33.8791 44.6639 33.925 44.4331 33.925 44.2C33.925 42.725 33.125 41.975 32.15 41.975ZM7.15 37.5C3.2 37.5 0 34.3 0 30.35V20.9C0 16.95 3.2 13.75 7.15 13.75H25C25 12.775 24.2 11.35 23.225 11.35H12.5V7.15C12.5 3.2 15.7 0 19.65 0H30.35C34.3 0 37.5 3.2 37.5 7.15V16.525C37.5 20.475 34.3 23.65 30.35 23.65H17.225C13.275 23.65 10.1 26.85 10.1 30.8V37.5H7.15ZM17.85 8.025C18.85 8.025 19.65 7.275 19.65 5.8C19.65 4.325 18.85 4.025 17.85 4.025C16.875 4.025 16.075 4.325 16.075 5.8C16.075 7.275 16.875 8.025 17.85 8.025Z" fill="#212121" />
                </svg>
                <div class="card__title">Python</div>
                <div class="card__subtitle">Тестов: <?php echo sizeof(get_tt_list($link, 'tests', 'python')); ?></div>
            </a>
        </main>
    </div>
    <script src="../static/js/theme.js"></script>
    <script src="../static/js/menu.js"></script>
    <script src="../static/js/form.js"></script>
    <?php
    if ($_SESSION['message']) {
        echo '<script src="../static/js/alert.js"></script><script>popupAlert("' . $_SESSION['message'] . '");</script>';
        unset($_SESSION['message']);
    }
    ?>
    <?php printCookieMessageScript(); ?>
</body>

</html>