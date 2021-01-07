<?php
session_start();
if (!$_GET['lang'] || !isset($_GET['tId'])) {
    header('Location: /tasks/');
}
require_once '../include/db.php';
require_once '../include/functions.php';
$user = '';
if ($_COOKIE['user']) {
    $user = json_decode($_COOKIE['user'], true);
}
$list = get_tt_list($link, 'tasks', $_GET['lang']);
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
    <link rel="stylesheet" href="../static/css/prism.min.css">
    <title><?php echo $list[$_GET['tId']][3]; ?> - coderley</title>
    <meta name="description" content="<?php echo $list[$_GET['tId']][3]; ?> - coderley">
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
        print_sidebar('tasks');
        ?>
        <div id="wrapper__block-elements"></div>
        <?php print_header($user); ?>
        <main id="main" class="task">
            <a href="/tasks/list.php?lang=<?php echo $_GET['lang'] ?>" id="back">
                &lt;- назад</a>
            <h2 class="task__title"><?php echo $list[$_GET['tId']][3]; ?></h2>

            <div class="task__problem">
                <h3>Условие:</h3>
                <?php echo $list[$_GET['tId']][4]; ?>
            </div>
            <div class="task__solution">
                <button class="task__solution__toggle">Показать решение</button>
                <div class="task__solution__content">
                    <?php echo $list[$_GET['tId']][5]; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="../static/js/theme.js"></script>
    <script src="../static/js/menu.js"></script>
    <script src="../static/js/prism.js"></script>
    <script src="../static/js/form.js"></script>
    <script>
        let solutionBtn = document.querySelector('.task__solution__toggle');
        let solution = document.querySelector('.task__solution');
        solutionBtn.onclick = () => {
            solution.classList.toggle('task__solution--opened')
        }
    </script>
    <?php
    if ($_SESSION['message']) {
        echo '<script src="../static/js/alert.js"></script><script>popupAlert("' . $_SESSION['message'] . '");</script>';
        unset($_SESSION['message']);
    }
    ?>
    <?php printCookieMessageScript(); ?>
</body>

</html>