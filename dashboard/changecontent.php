<?php
session_start();
require_once '../include/Mobile_Detect.php';
$detect = new Mobile_Detect;
if ($detect->isMobile() || $detect->isTablet()) {
    $_SESSION['message'] = 'Данная панель доступна только с компьютера.';
    header('Location: ./');
} else {
    if (!isset($_GET)) {
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
        $id = $_GET['id'];
        $item = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM `$table` WHERE `id` = '$id'"));
        $title = $table == 'education' ? 'Обучение' : ($table == 'tasks' ? 'Задачи' : ($table == 'tests' ? 'Тесты' : 'Блог'));
    }
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
    <link rel="stylesheet" href="../static/css/prism.min.css">
    <title><?php echo $title; ?> | добавление - coderley</title>
    <meta name="description" content="Панель администратора - coderley">

    <script src="./ckeditor/ckeditor.js"></script>

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
        <main id="main" class='dashboard-add'>
            <a href="./list.php?table=<?php echo $_GET['table']; ?>" class='back-button'>назад</a>
            <h1 class="dashboard-list__title">
                <?php echo $title; ?>
            </h1>
            <form action="change.php" method="post">

                <input type="text" name="table" id="table" style="display: none;" value="<?php echo $table; ?>">
                <input type="text" name="id" id="id" style="display: none;" value="<?php echo $id; ?>">
                <?php printDashboardChangeContent($table, $item); ?>

                <button type="submit">ИЗМЕНИТЬ</button>

            </form>
        </main>
    </div>
    <script src="../static/js/theme.js"></script>
    <script src="../static/js/menu.js"></script>
    <script src="../static/js/form.js"></script>
    <script src="../static/js/prism.js"></script>
    <?php if ($table == 'tests') {
        echo '<script src="../static/js/changetest.js"></script>';
    } else if ($table == 'tasks') {
        echo "<script>CKEDITOR.replace('task')</script>;
        CKEDITOR.replace('solution');";
    } else if ($table != 'tests') {
        echo "<script>CKEDITOR.replace('content');</script>";
    } ?>
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