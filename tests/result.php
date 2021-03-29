<?php
session_start();
if (!$_POST && !$_COOKIE['last-test-result']) {
    header('Location: /tests/');
}
require_once '../include/db.php';
require_once '../include/functions.php';
$testLang = '';
$testId = '';
$testResult = '';
$testTime = '';
$success = '';
$testSize = '';
$questions = '';
$userAnswers = '';
$user = '';
if ($_POST) {
    $testLang = $_POST['test-lang'];
    $testId = $_POST['test-id'];
    $test = get_tt_item($link, 'tests', $testLang, $testId);
    $rightAnswers = 0;
    $userAnswers = json_decode($_POST['test-result']);
    $testAnswers = json_decode($test['answers']);
    $questions = json_decode($test['test']);
    $testSize = sizeof($testAnswers);
    for ($i = 0; $i < $testSize; $i++) {
        if ($userAnswers[$i] == $testAnswers[$i])
            $rightAnswers++;
    };
    $testResult = $rightAnswers;
    $testTime = $_POST['test-time'];
    setcookie('last-test-lang', $testLang, time() + 3600 * 24, '/');
    setcookie('last-test-id', $testId, time() + 3600 * 24, '/');
    setcookie('last-test-result', $testResult, time() + 3600 * 24, '/');
    setcookie('last-test-time', $testTime, time() + 3600 * 24, '/');
    setcookie('last-test-answers', json_encode($userAnswers), time() + 3600 * 24, '/');
    setcookie('test-' . $testLang . '-' . $test['id'], 'day_block', time() + 3600 * 24, '/');
} else {
    $testLang = $_COOKIE['last-test-lang'];
    $testId = $_COOKIE['last-test-id'];
    $testResult = $_COOKIE['last-test-result'];
    $testTime = $_COOKIE['last-test-time'];
    $userAnswers = json_decode($_COOKIE['last-test-answers']);
    $test = get_tt_item($link, 'tests', $testLang, $testId);
    $testAnswers = json_decode($test['answers']);
    $questions = json_decode($test['test']);
    $testSize = sizeof($testAnswers);
    setcookie('last-test-lang', $testLang, time() + 3600, '/');
    setcookie('last-test-id', $testId, time() + 3600, '/');
    setcookie('last-test-result', $testResult, time() + 3600, '/');
    setcookie('last-test-time', $testTime, time() + 3600, '/');
    setcookie('last-test-answers', json_encode($userAnswers), time() + 3600, '/');
    setcookie('test-' . $testLang . '-' . $test['id'], 'day_block', time() + 3600, '/');
}
$success = $testResult >= $testSize * 0.8 ? 'success' : 'failure';
if ($_COOKIE['user']) {
    $user = json_decode($_COOKIE['user'], true);
    if ($testResult >= $testSize * 0.8) {
        save_test_result($user['id'], $link, $testId, $testLang, $testResult . ' / ' . $testSize, time() + 3600 * 24);
    }
}
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
    <title>Результат теста - coderley</title>
    <meta name="description" content="Результат теста - coderley">
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
        <main id="main" class="result">
            <h1 class="result-title result-title--<?php echo $success; ?>">Тест <?php echo $success == 'success' ? 'сдан' : 'не сдан'; ?></h1>
            <div class="result-data">
                <div class="result-data__item">
                    <p class="result-data__item__content"><?php echo '<span class="' . $success . '">' . $testResult . '</span> из ' . $testSize ?></p>
                    <p class="result-data__item__title">результат</p>
                </div>
                <div class="result-data__item">
                    <p class="result-data__item__content"><?php echo $testTime ?></p>
                    <p class="result-data__item__title">время</p>
                </div>
            </div>
            <p><?php if ($_COOKIE['user']) {
                    echo 'Повторная попытка возможна через 1 день';
                } else {
                    echo 'Чтобы сохранить результат необходимо авторизоваться!';
                } ?></p>
            <a href="<?php echo '/tests/list.php?lang=' . $testLang ?>" class="result__back-button">Назад</a>
            <div class="result-failures">
                <div class="result-failures__title">Неправильные ответы: <?php echo $testSize - $testResult; ?></div>
                <div class="result-failures__wrapper">
                    <?php
                    for ($i = 0; $i < $testSize; $i++) {
                        switch ($questions[$i]->answerType) {
                            case 'radio':
                                $temp = $questions[$i]->answerVariants[$userAnswers[$i] - 1];
                                $userAnswer = $temp == '' ? 'Нет ответа' : $temp;
                                break;
                            case 'text':
                                $userAnswer = $userAnswers[$i] == '' ? 'Нет ответа' : $userAnswers[$i];
                                break;
                            case 'checkbox':
                                $temp = $questions[$i]->answerVariants[$userAnswers[$i] - 1];
                                if ($userAnswers[$i] > 0) {
                                    $temp = explode(',', $userAnswers[$i]);
                                    $answers = $questions[$i]->answerVariants;
                                    for ($j = 0; $j < sizeof($temp); $j++) {
                                        $userAnswer .= $answers[($temp[$j] - 1)] . ',';
                                    }
                                    $userAnswer = substr($userAnswer, 0, -1);
                                } else {
                                    $userAnswer = 'Нет ответа';
                                }
                                break;
                            default:

                                break;
                        }
                        if ($userAnswers[$i] != $testAnswers[$i])
                            echo '<div class="result-failures__wrapper__item">
                            <p class="result-failures__wrapper__item__question">' . $questions[$i]->question . '</p>
                            <p class="result-failures__wrapper__item__text">Ваш ответ:</p>
                            <p class="result-failures__wrapper__item__answer">' . $userAnswer . '</p>
                        </div>';
                    };
                    ?>
                </div>
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
    <?php printCookieMessageScript(); ?>
</body>

</html>