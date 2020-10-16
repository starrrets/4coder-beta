<?php
if (!$_COOKIE['user']) {
    header('Location: /');
}
$user = json_decode($_COOKIE['user'], true);
require_once '../includes/db.php';
require_once '../includes/functions.php';
$list = get_article_list($link, 'python');
$id = $_GET['id'];
$list_size = sizeof($list) - 1;
if ($id > $list_size || $id < 0) {
    header('HTTP/1.1 404 Not Found', true, 404);
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../static/css/secondary.min.css">
    <link rel="stylesheet" href="../static/css/prism.min.css">
    <title><?php echo $list[$id][2]; ?> - coderley</title>
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
        <main id="main" class="article">
            <?php print__article($list, $id);
            ?>
        </main>
    </div>
    <script src="../static/js/theme.js"></script>
    <script src="../static/js/menu.js"></script>
    <script src="../static/js/prism.js"></script>
    <script>
        const backBtn = window.error__button;
        if (backBtn) {
            backBtn.onclick = () => {
                window.history.back();
            }
        }
    </script>
</body>

</html>