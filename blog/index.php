<?php
session_start();
require_once '../include/db.php';
require_once '../include/functions.php';
$user = '';
if ($_COOKIE['user']) {
    $user = json_decode($_COOKIE['user'], true);
}

$page = ((isset($_GET['page'])) && ($_GET['page'] != '')) ? $_GET['page'] : 1;
$limit = 12;
$total_records = 0;

if (isset($_GET['filter']) && $_GET['filter'] != '') {
    $filter = $_GET['filter'];
    $total_records = mysqli_fetch_row(mysqli_query($link, "SELECT COUNT(*) FROM `blog` WHERE topic = '$filter'"))[0];
} else {
    $total_records = mysqli_fetch_row(mysqli_query($link, "SELECT COUNT(*) FROM `blog`"))[0];
}
$total_pages = ceil($total_records / $limit);
$start_from = ($page - 1) * $limit;
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
    <title>Новости - coderley</title>
    <meta name="description" content="Новости - coderley">
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
        print_sidebar('blog');
        ?>
        <div id="wrapper__block-elements"></div>
        <?php print_header($user); ?>
        <main id="main" class='blog'>

            <?php printBlogFilter($link, $_GET['filter']); ?>

            <section class="cards-section">

                <?php printBlogCards($link, $_GET['filter'], $start_from, $limit);  ?>

            </section>

            <?php echo '<ul class="pagination" data-columns="' . ($total_pages >= 9 ? '9' : $total_pages) . '">';

            if ($page > 1) {
                echo "<li class='pagination__text'><a href='index.php?" . ($_GET['filter'] == '' ? '' : 'filter=' . $_GET['filter'] . '&') . "page=" . ($page - 1) . "' class='button'><- НАЗАД</a></li>";
            }
            $pList = getPageList($total_pages, $page, 9);
            for ($i = 0; $i < sizeof($pList); $i++) {
                if ($page == $pList[$i]) {
                    echo "<li class='active " . ($i == sizeof($pList) - 1 ? 'last' : '') . "'>" . $pList[$i] . "</li>";
                } elseif ($pList[$i] != 0) {
                    echo "<li class='pagination-item " . ($i == sizeof($pList) - 1 ? 'last' : '') . "'><a href='index.php?" . ($_GET['filter'] == '' ? '' : 'filter=' . $_GET['filter'] . '&') . "page=" . $pList[$i] . "'>" . $pList[$i] . "</a></li>";
                } else {
                    echo "<li class='dot'>...</li>";
                }
            }
            if ($total_pages > $page) {
                echo "<li class='pagination__text'><a href='index.php?" . ($_GET['filter'] == '' ? '' : 'filter=' . $_GET['filter'] . '&') . "page=" . ($page + 1) . "' class='button'>ВПЕРЁД -></a></li>";
            }
            echo "</ul>";  ?>
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