<?php
session_start();
if ($_COOKIE['user']) {
    header('Location: /home/');
}
// google
require_once './include/variables.php';
require_once './include/google-api/vendor/autoload.php';
$client = new Google_Client();
$client->setClientId(GOOGLE_ID);
$client->setClientSecret(GOOGLE_SECRET);
$client->setRedirectUri(GOOGLE_URI);
$client->addScope("email");
$client->addScope("profile");

//yandex
$client_id = YANDEX_ID;
$client_secret = YANDEX_SECRET;
$redirect_uri = YANDEX_SECRET;
$url = 'https://oauth.yandex.ru/authorize';
$params = array(
    'response_type' => 'code',
    'client_id'     => $client_id,
    'display'       => 'popup'
);
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
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./static/css/main.min.css">
    <link rel="shortcut icon" href="favicon.svg" type="image/svg+xml">
    <title>coderley - образовательный портал</title>
    <meta name="description" content="coderley — это онлайн платформа, где вы найдёте: самоучители по различным языкам программирования, блог и др.">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Rubik', sans-serif;
            text-rendering: optimizeLegibility;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body>
    <div id="registration">
        <h3>Авторизация</h3>
        <div class="row" id="social_registration">
            <a id="social_registration__google" href="<?php echo $client->createAuthUrl(); ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.3206 8.18111H10V12.0439H15.3638C14.8646 14.4985 12.7748 15.9089 10 15.9089C6.72823 15.9089 4.09111 13.2718 4.09111 9.99888C4.09111 6.72711 6.72823 4.08999 10 4.08999C11.4092 4.08999 12.683 4.59033 13.6826 5.40855L16.5928 2.49944C14.8198 0.95366 12.5465 0 10 0C4.45489 0 0 4.45377 0 10C0 15.5462 4.45377 20 10 20C15 20 19.5467 16.3633 19.5467 10C19.5467 9.409 19.456 8.77211 19.3206 8.18111Z" fill="#F9F9F9" />
                </svg>
                <span>Войти с помощью Google</span>
            </a>
            <a id="social_registration__yandex" href="<?php echo $url . '?' . urldecode(http_build_query($params)); ?>">
                <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.29716 0H6.4556C3.57673 0 0.671807 2.12586 0.671807 6.87523C0.671807 9.33531 1.71442 11.2513 3.62567 12.3409L0.127549 18.6726C-0.0383099 18.9721 -0.042646 19.3114 0.115909 19.5804C0.270713 19.8431 0.553799 20 0.872861 20H2.64243C3.04442 20 3.35786 19.8057 3.50817 19.4653L6.78806 13.05H7.02743V19.2004C7.02743 19.6339 7.39317 20 7.8261 20H9.37196C9.85747 20 10.1964 19.661 10.1964 19.1756V0.875117C10.1965 0.359883 9.82673 0 9.29716 0ZM7.02743 10.2002H6.60517C4.96786 10.2002 3.9904 8.86375 3.9904 6.62512C3.9904 3.84156 5.22517 2.8498 6.38079 2.8498H7.02743V10.2002Z" fill="black" />
                </svg>
            </a>
        </div>
        <form action="/include/auth.php" method="post">
            <div class="row row_input">
                <label>Email</label>
                <input type="email" name="mail" id="mail" placeholder="Введите email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required required oninvalid="this.setCustomValidity('Проверьте введенную почту')" oninput="setCustomValidity('')">
            </div>
            <div class="row row_input">
                <label>Пароль</label>
                <input type="password" name="pass" id="pass" placeholder="Введите пароль" pattern="(?=.*\d)(?=.*[а-яА-ЯёЁa-zA-Z]).{8,}" required oninvalid="this.setCustomValidity('Пароль должен содержать не менее 8 знаков, включать буквы, цифры')" oninput="setCustomValidity('')">
            </div>
            <div class="row"><button type="submit" id="auth_button">Войти /
                    Зарегестрироваться</button></div>
            <div class="row confidential">Нажимая на кнопку, вы даете согласие на
                обработку своих персональных данных в соответствии с <a href="/confidential.pdf" target="_blank">политикой
                    конфиденциальности</a>.</div>
        </form>
    </div>
    <header>
        <svg class="logo" width="135" height="40" viewBox="0 0 135 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M61.0716 26.18C59.8356 26.18 58.8516 25.838 58.1196 25.154C57.3996 24.47 57.0396 23.534 57.0396 22.346V19.754C57.0396 18.566 57.3996 17.63 58.1196 16.946C58.8516 16.262 59.8356 15.92 61.0716 15.92C62.2596 15.92 63.2136 16.232 63.9336 16.856C64.6536 17.48 65.0316 18.332 65.0676 19.412H63.1236C63.0876 18.848 62.8896 18.41 62.5296 18.098C62.1696 17.786 61.6836 17.63 61.0716 17.63C60.4116 17.63 59.8956 17.816 59.5236 18.188C59.1636 18.548 58.9836 19.064 58.9836 19.736V22.346C58.9836 23.018 59.1636 23.54 59.5236 23.912C59.8956 24.284 60.4116 24.47 61.0716 24.47C61.6836 24.47 62.1696 24.314 62.5296 24.002C62.8896 23.69 63.0876 23.246 63.1236 22.67H65.0676C65.0316 23.75 64.6536 24.608 63.9336 25.244C63.2136 25.868 62.2596 26.18 61.0716 26.18ZM70.8926 26.18C69.6686 26.18 68.7026 25.838 67.9946 25.154C67.2866 24.458 66.9326 23.522 66.9326 22.346V19.754C66.9326 18.578 67.2866 17.648 67.9946 16.964C68.7026 16.268 69.6686 15.92 70.8926 15.92C72.1166 15.92 73.0826 16.262 73.7906 16.946C74.4986 17.63 74.8526 18.56 74.8526 19.736V22.346C74.8526 23.522 74.4986 24.458 73.7906 25.154C73.0826 25.838 72.1166 26.18 70.8926 26.18ZM70.8926 24.47C71.5406 24.47 72.0386 24.29 72.3866 23.93C72.7346 23.57 72.9086 23.042 72.9086 22.346V19.754C72.9086 19.058 72.7346 18.53 72.3866 18.17C72.0386 17.81 71.5406 17.63 70.8926 17.63C70.2446 17.63 69.7466 17.81 69.3986 18.17C69.0506 18.53 68.8766 19.058 68.8766 19.754V22.346C68.8766 23.042 69.0506 23.57 69.3986 23.93C69.7466 24.29 70.2446 24.47 70.8926 24.47ZM82.7295 12.86H84.6735V26H82.7475V24.11H82.7115C82.6155 24.758 82.3335 25.268 81.8655 25.64C81.4095 26 80.8095 26.18 80.0655 26.18C79.0695 26.18 78.2775 25.844 77.6895 25.172C77.1135 24.5 76.8255 23.594 76.8255 22.454V19.664C76.8255 18.512 77.1135 17.6 77.6895 16.928C78.2775 16.256 79.0695 15.92 80.0655 15.92C80.7975 15.92 81.3975 16.106 81.8655 16.478C82.3335 16.85 82.6155 17.354 82.7115 17.99H82.7835L82.7295 15.686V12.86ZM80.7495 24.488C81.3615 24.488 81.8415 24.302 82.1895 23.93C82.5495 23.546 82.7295 23.018 82.7295 22.346V19.754C82.7295 19.082 82.5495 18.56 82.1895 18.188C81.8415 17.804 81.3615 17.612 80.7495 17.612C80.1135 17.612 79.6215 17.798 79.2735 18.17C78.9375 18.53 78.7695 19.058 78.7695 19.754V22.346C78.7695 23.042 78.9375 23.576 79.2735 23.948C79.6215 24.308 80.1135 24.488 80.7495 24.488ZM94.6385 21.518H88.5905V22.346C88.5905 23.078 88.7645 23.636 89.1125 24.02C89.4725 24.404 90.0005 24.596 90.6965 24.596C91.2605 24.596 91.7045 24.5 92.0285 24.308C92.3645 24.104 92.5745 23.81 92.6585 23.426H94.6025C94.4585 24.266 94.0325 24.938 93.3245 25.442C92.6165 25.934 91.7345 26.18 90.6785 26.18C89.4665 26.18 88.5005 25.832 87.7805 25.136C87.0725 24.428 86.7185 23.498 86.7185 22.346V19.754C86.7185 18.59 87.0725 17.66 87.7805 16.964C88.5005 16.268 89.4665 15.92 90.6785 15.92C91.8905 15.92 92.8505 16.268 93.5585 16.964C94.2785 17.66 94.6385 18.59 94.6385 19.754V21.518ZM88.5905 20.204L92.7665 20.186V19.736C92.7665 19.004 92.5865 18.446 92.2265 18.062C91.8785 17.666 91.3625 17.468 90.6785 17.468C89.9945 17.468 89.4725 17.666 89.1125 18.062C88.7645 18.458 88.5905 19.022 88.5905 19.754V20.204ZM101.543 15.92C102.599 15.92 103.433 16.25 104.045 16.91C104.669 17.558 104.981 18.452 104.981 19.592V20.258H102.983V19.754C102.983 19.058 102.803 18.518 102.443 18.134C102.095 17.75 101.603 17.558 100.967 17.558C100.331 17.558 99.8335 17.75 99.4735 18.134C99.1255 18.518 98.9515 19.058 98.9515 19.754V26H97.0075V16.1H98.8795V17.954C99.1915 16.598 100.079 15.92 101.543 15.92ZM111.778 26C110.842 26 110.092 25.73 109.528 25.19C108.976 24.638 108.7 23.906 108.7 22.994V14.624H105.712V12.86H110.644V22.994C110.644 23.378 110.752 23.684 110.968 23.912C111.184 24.128 111.478 24.236 111.85 24.236H115.054V26H111.778ZM124.317 21.518H118.269V22.346C118.269 23.078 118.443 23.636 118.791 24.02C119.151 24.404 119.679 24.596 120.375 24.596C120.939 24.596 121.383 24.5 121.707 24.308C122.043 24.104 122.253 23.81 122.337 23.426H124.281C124.137 24.266 123.711 24.938 123.003 25.442C122.295 25.934 121.413 26.18 120.357 26.18C119.145 26.18 118.179 25.832 117.459 25.136C116.751 24.428 116.397 23.498 116.397 22.346V19.754C116.397 18.59 116.751 17.66 117.459 16.964C118.179 16.268 119.145 15.92 120.357 15.92C121.569 15.92 122.529 16.268 123.237 16.964C123.957 17.66 124.317 18.59 124.317 19.754V21.518ZM118.269 20.204L122.445 20.186V19.736C122.445 19.004 122.265 18.446 121.905 18.062C121.557 17.666 121.041 17.468 120.357 17.468C119.673 17.468 119.151 17.666 118.791 18.062C118.443 18.458 118.269 19.022 118.269 19.754V20.204ZM128 29.24L129.422 25.424L125.75 16.1H127.874L129.89 21.68C130.034 22.1 130.19 22.658 130.358 23.354C130.526 22.646 130.676 22.088 130.808 21.68L132.68 16.1H134.75L130.052 29.24H128Z" fill="black" />
            <path d="M22.7998 3.68445L13.4337 20.1642L20 31.6005L22.7998 36.5618L20.4742 40H11.1172L6.39307 31.6005L3.59325 26.5572L0 20.1642L3.75741 13.4428L11.2813 0H20.7205L22.7998 3.60237V3.68445Z" fill="#01EBD0" />
            <path d="M45.5996 20.1642L41.9972 26.5572L39.1974 31.6005L34.4733 40H25.1163L22.7998 36.5618L25.5996 31.6005L32.1568 20.1642L22.7998 3.68445V3.60237L24.9612 0H34.3183L41.8331 13.4428L45.5996 20.1642Z" fill="#01EBD0" />
            <path d="M20.7205 0L9.35705 20.1642H0L3.75741 13.4428L11.2813 0H20.7205Z" fill="url(#paint0_linear)" />
            <path d="M20.4742 40H11.1172L6.39307 31.6006L3.59325 26.5573L0 20.1642H9.35704L15.7592 31.6006L20.4742 40Z" fill="url(#paint1_linear)" />
            <path d="M22.7997 3.68445L13.4335 20.1642H9.35693L20.7204 0L22.7997 3.60237V3.68445Z" fill="url(#paint2_linear)" />
            <path d="M22.7997 36.5618L20.4741 40L15.7591 31.6006L9.35693 20.1642H13.4335L19.9999 31.6006L22.7997 36.5618Z" fill="url(#paint3_linear)" />
            <path d="M45.5998 20.1642H36.2337L24.9614 0H34.3185L41.8333 13.4428L45.5998 20.1642Z" fill="url(#paint4_linear)" />
            <path d="M45.5996 20.1642L41.9972 26.5573L39.1974 31.6006L34.4733 40H25.1162L29.8403 31.6006L36.2334 20.1642H45.5996Z" fill="url(#paint5_linear)" />
            <path d="M36.2335 20.1642H32.1568L22.7998 3.68445V3.60237L24.9612 0L36.2335 20.1642Z" fill="url(#paint6_linear)" />
            <path d="M36.2335 20.1642L29.8404 31.6006L25.1163 40L22.7998 36.5618L25.5996 31.6006L32.1568 20.1642H36.2335Z" fill="url(#paint7_linear)" />
            <defs>
                <linearGradient id="paint0_linear" x1="22.7086" y1="-4.10397" x2="9.48473" y2="20.155" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-color="#01EBD0" />
                </linearGradient>
                <linearGradient id="paint1_linear" x1="22.8454" y1="44.1405" x2="9.53032" y2="20.1551" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#814EFA" />
                    <stop offset="0.9944" stop-color="#01EBD0" />
                </linearGradient>
                <linearGradient id="paint2_linear" x1="20.9301" y1="-0.775194" x2="13.4974" y2="20.155" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="0.7545" stop-color="#34FEE6" />
                </linearGradient>
                <linearGradient id="paint3_linear" x1="20.5653" y1="40.0365" x2="13.4974" y2="20.1551" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#9C74FB" />
                    <stop offset="0.9944" stop-color="#34FEE6" />
                </linearGradient>
                <linearGradient id="paint4_linear" x1="22.6632" y1="-4.10397" x2="36.0239" y2="20.155" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#814EFA" />
                    <stop offset="1" stop-color="#01EBD0" />
                </linearGradient>
                <linearGradient id="paint5_linear" x1="23.347" y1="43.1829" x2="36.1605" y2="20.1551" gradientUnits="userSpaceOnUse">
                    <stop offset="0.0572917" stop-color="white" />
                    <stop offset="1" stop-color="#01EBD0" />
                </linearGradient>
                <linearGradient id="paint6_linear" x1="24.8518" y1="1.1141e-07" x2="32.0565" y2="20.155" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#9C74FB" />
                    <stop offset="0.9944" stop-color="#34FEE6" />
                </linearGradient>
                <linearGradient id="paint7_linear" x1="24.8518" y1="40.6749" x2="32.1477" y2="20.3831" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="0.838542" stop-color="#34FEE6" />
                </linearGradient>
            </defs>
        </svg>
        <div id="header__links">
            <a href="/about/" class="header__links__item header__links__item--disabled" target="_blank">о нас</a>
            <a href="https://discord.gg/5nczA2d" class="header__links__item" target="_blank">сообщество</a>
            <a href="https://t.me/coderley" class="header__links__item" target="_blank">новости</a>
        </div>
        <button id="header__button">Войти</button>
    </header>
    <main>
        <section class="main__title">
            <h1 class="main__title__h">Разработаем приложение?</h1>
            <p>coderley — это онлайн платформа, где вы найдёте: самоучители по различным
                языкам программирования, блог и др.</p>
            <a class='main__title__cta' href='/home/'>Я в деле</a>
        </section>
    </main>
    <div class="alert">
        <p id="alert__title"></p>
        <button id="alert__btn">ок</button>
    </div>
    <div id="page__block"></div>
    <script src="../static/js/alert.js"></script>
    <script src="../static/js/form.js"></script>
    <?php
    if ($_SESSION['message']) {
        echo '<script>popupAlert("' . $_SESSION['message'] . '");</script>';
        unset($_SESSION['message']);
    }
    ?>
</body>

</html>