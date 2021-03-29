<?php
session_start();
require_once '../include/Mobile_Detect.php';
$detect = new Mobile_Detect;
if ($detect->isMobile() || $detect->isTablet()) {
    $_SESSION['message'] = 'Данная панель доступна только с компьютера.';
    header('Location: ./');
} else {
    require_once '../include/db.php';
    require_once '../include/functions.php';

    $user = '';
    if ($_COOKIE['user']) {
        $user = json_decode($_COOKIE['user'], true);
    }
    if ($user['role'] == 'user') {
        header('Location: /home/');
    }
    $table = $_GET['table'];
    $title = $table == 'education' ? 'Обучение' : ($table == 'tasks' ? 'Задачи' : ($table == 'tests' ? 'Тесты' : 'Блог'));
}

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RHYX25Y164"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-RHYX25Y164');
    </script>
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
    </noscript>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../static/css/secondary.min.css">
    <link rel="stylesheet" href="../static/css/admin.min.css">
    <title><?php echo $title; ?> | администрирование - coderley</title>
    <meta name="description" content="Панель администратора - coderley">
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
        print_sidebar('');
        ?>
        <div id="wrapper__block-elements"></div>
        <?php print_header($user); ?>
        <main id="main" class='dashboard-list'>
            <a href="./" class='back-button'>назад</a>
            <h1 class="dashboard-list__title">
                <?php echo $title; ?>
            </h1>
            <a href="./addcontent.php?table=<?php echo $table; ?>" class="dashboard-list__add">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.66667 15H13.3333C14.2533 15 15 14.2533 15 13.3333V1.66667C15 0.746667 14.2533 0 13.3333 0H1.66667C0.746667 0 0 0.746667 0 1.66667V13.3333C0 14.2533 0.746667 15 1.66667 15ZM3.33333 6.66667H6.66667V3.33333H8.33333V6.66667H11.6667V8.33333H8.33333V11.6667H6.66667V8.33333H3.33333V6.66667Z" fill="black" />
                </svg>
                добавить
            </a>
            <?php printDashboardTableList($link, $table, $user['role']); ?>
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