<?php
if (!$_COOKIE['user']) {
    header('Location: /');
}
$user = json_decode($_COOKIE['user'], true);
require_once '../includes/db.php';
require_once '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../static/css/secondary.min.css">
    <title>Python - coderley</title>
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
        print_sidebar('education');
        ?>
        <div id="wrapper__block-elements"></div>
        <?php print_header($user); ?>
        <main id="main" class="list">
            <h1 id="main__title">Python</h1>
            <div id="main__list">
                <?php
                $list = get_article_list($link, 'python');
                print_articles_list($list);
                ?>
            </div>
        </main>
    </div>
    <script src="../static/js/theme.js"></script>
    <script src="../static/js/menu.js"></script>
</body>

</html>