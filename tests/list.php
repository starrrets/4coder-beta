<?php
session_start();
if (!$_GET['lang']) {
    header('Location: /tests/');
}
require_once '../include/db.php';
require_once '../include/functions.php';
if (!isLanguageExist($link, 'tests', $_GET['lang'])) {
    header('Location: /tests/');
}
$user = '';
if ($_COOKIE['user']) {
    $user = json_decode($_COOKIE['user'], true);
}

$list = get_tt_list($link, 'tests', $_GET['lang']);
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
    <title>Тесты по <?php echo $_GET['lang']; ?> - coderley</title>
    <meta name="description" content="Тесты по <?php echo $_GET['lang']; ?> - coderley">
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
        <main id="main" class="tt-list">
            <div class="list__difficulty">
                <p class="list__difficulty__title">Уровень сложности</p>
                <div class="list__difficulty__buttons"><button class="list__difficulty__button list__difficulty__button--current" id="easy">Начальный</button>
                    <button class="list__difficulty__button" id="normal">Средний</button>
                    <button class="list__difficulty__button" id="hard">Продвинутый</button>
                </div>
            </div>
            <div class="list__section list__section--current" id="list__section__easy">
                <?php print_test_list($user, $link, $list, 'tests', 'easy');  ?>
            </div>
            <div class="list__section" id="list__section__normal">
                <?php print_test_list($user, $link, $list, 'tests', 'normal');  ?>
            </div>
            <div class="list__section" id="list__section__hard">
                <?php print_test_list($user, $link, $list, 'tests', 'hard');  ?>
            </div>
        </main>
    </div>
    <script src="../static/js/list.js"></script>
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