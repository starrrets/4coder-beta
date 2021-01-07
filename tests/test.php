<?php
session_start();
if (!$_GET['lang'] || !isset($_GET['tId'])) {
    header('Location: /tests/');
} elseif ($_COOKIE['test-' . $_GET['tId']]) {
    header('Location: /tests/list.php?lang=' . $_GET['lang']);
}
require_once '../include/db.php';
require_once '../include/functions.php';
$user = '';
if ($_COOKIE['user']) {
    $user = json_decode($_COOKIE['user'], true);
}
$list = get_tt_list($link, 'tests', $_GET['lang']);
$test = $list[$_GET['tId']];
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
    <title><?php echo $test[3]; ?> - coderley</title>
    <meta name="description" content="<?php echo $test[3]; ?> - coderley">
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
        <main id="main" class="test">
            <div id="test-question__text"></div>
            <div id="test-question__answers"></div>
            <div id="test-controls">
                <div id="timer"><span id="timer_minutes"><?php echo $test[4]; ?></span> : <span id="timer_seconds">00</span></div>
                <div class="progress"><span id="progress-current">1</span> / <span id="progress-total"><?php echo sizeof(json_decode($test[5])); ?></span></div>
                <form id="test-form" action="result.php" method="post" onsubmit="return false;"><button type="submit" id="answer" class="disabled">Ответить</button><input type="text" name="test-id" id="test-id" value="<?php echo $_GET['tId']; ?>"><input type="text" name="test-lang" id="test-lang" value="<?php echo $_GET['lang']; ?>"><input type="text" name="test-result" id="test-result"><input type="text" name="test-time" id="test-time"></form>
            </div>
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
    <script>
        if (!localStorage.getItem('testQuestions')) {
            localStorage.setItem('testQuestions', JSON.stringify(<?php echo $test[5]; ?>))
        }
    </script>
    <script src="../static/js/test.js"></script>
    <?php printCookieMessageScript(); ?>
</body>

</html>