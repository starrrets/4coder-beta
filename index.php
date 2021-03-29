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
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/69978256" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript> <!-- /Yandex.Metrika counter -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./static/css/home.min.css" />
    <link rel="shortcut icon" href="favicon.svg" type="image/svg+xml" />
    <title>coderley - образовательный портал</title>
    <meta name="description" content="coderley — это онлайн платформа, где вы найдёте: самоучители по различным языкам программирования, блог и др." />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'JetBrains Mono', monospace;
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
                <input type="email" name="mail" id="mail" placeholder="Введите email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required oninvalid="this.setCustomValidity('Проверьте введенную почту')" oninput="setCustomValidity('')" />
            </div>
            <div class="row row_input">
                <label>Пароль</label>
                <input type="password" name="pass" id="pass" placeholder="Введите пароль" pattern="(?=.*\d)(?=.*[а-яА-ЯёЁa-zA-Z]).{8,}" required oninvalid="this.setCustomValidity('Пароль должен содержать не менее 8 знаков, включать буквы, цифры')" oninput="setCustomValidity('')" />
            </div>
            <div class="row">
                <button type="submit" id="auth_button">
                    Войти / Зарегестрироваться
                </button>
            </div>
            <div class="row confidential">
                Нажимая на кнопку, вы даете согласие на обработку своих персональных
                данных в соответствии с
                <a href="/privacy.pdf" target="_blank">политикой конфиденциальности</a>.
            </div>
        </form>
    </div>
    <header>
        <svg class="logo" width="135" height="40" viewBox="0 0 135 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.7998 3.68445L13.4336 20.1642L20 31.6005L22.7998 36.5618L20.4742 40H11.1172L6.39307 31.6005L3.59325 26.5572L0 20.1642L3.75741 13.4428L11.2813 0H20.7205L22.7998 3.60237V3.68445Z" fill="#FF1545" />
            <path d="M45.5996 20.1642L41.9973 26.5572L39.1974 31.6005L34.4733 40H25.1163L22.7998 36.5618L25.5996 31.6005L32.1569 20.1642L22.7998 3.68445V3.60237L24.9612 0H34.3183L41.8331 13.4428L45.5996 20.1642Z" fill="#FF1545" />
            <path d="M20.7205 0L9.35704 20.1642H0L3.75741 13.4428L11.2813 0H20.7205Z" fill="url(#paint0_linear)" />
            <path d="M20.4742 40H11.1172L6.39307 31.6006L3.59325 26.5573L0 20.1642H9.35705L15.7592 31.6006L20.4742 40Z" fill="url(#paint1_linear)" />
            <path d="M22.7998 3.68445L13.4337 20.1642H9.35706L20.7205 0L22.7998 3.60237V3.68445Z" fill="url(#paint2_linear)" />
            <path d="M22.7998 36.5618L20.4742 40L15.7592 31.6006L9.35706 20.1642H13.4337L20 31.6006L22.7998 36.5618Z" fill="url(#paint3_linear)" />
            <path d="M45.5996 20.1642H36.2335L24.9612 0H34.3183L41.8331 13.4428L45.5996 20.1642Z" fill="url(#paint4_linear)" />
            <path d="M45.5996 20.1642L41.9972 26.5573L39.1974 31.6006L34.4733 40H25.1162L29.8403 31.6006L36.2334 20.1642H45.5996Z" fill="url(#paint5_linear)" />
            <path d="M36.2335 20.1642H32.1569L22.7998 3.68445V3.60237L24.9612 0L36.2335 20.1642Z" fill="url(#paint6_linear)" />
            <path d="M36.2335 20.1642L29.8404 31.6006L25.1163 40L22.7998 36.5618L25.5996 31.6006L32.1569 20.1642H36.2335Z" fill="url(#paint7_linear)" />
            <path class="letters" d="M61.0716 26.18C59.8356 26.18 58.8515 25.838 58.1195 25.154C57.3995 24.47 57.0396 23.534 57.0396 22.346V19.754C57.0396 18.566 57.3995 17.63 58.1195 16.946C58.8515 16.262 59.8356 15.92 61.0716 15.92C62.2596 15.92 63.2136 16.232 63.9336 16.856C64.6536 17.48 65.0315 18.332 65.0675 19.412H63.1235C63.0875 18.848 62.8895 18.41 62.5295 18.098C62.1695 17.786 61.6836 17.63 61.0716 17.63C60.4116 17.63 59.8956 17.816 59.5236 18.188C59.1636 18.548 58.9836 19.064 58.9836 19.736V22.346C58.9836 23.018 59.1636 23.54 59.5236 23.912C59.8956 24.284 60.4116 24.47 61.0716 24.47C61.6836 24.47 62.1695 24.314 62.5295 24.002C62.8895 23.69 63.0875 23.246 63.1235 22.67H65.0675C65.0315 23.75 64.6536 24.608 63.9336 25.244C63.2136 25.868 62.2596 26.18 61.0716 26.18ZM70.8925 26.18C69.6685 26.18 68.7025 25.838 67.9945 25.154C67.2865 24.458 66.9326 23.522 66.9326 22.346V19.754C66.9326 18.578 67.2865 17.648 67.9945 16.964C68.7025 16.268 69.6685 15.92 70.8925 15.92C72.1165 15.92 73.0826 16.262 73.7906 16.946C74.4986 17.63 74.8525 18.56 74.8525 19.736V22.346C74.8525 23.522 74.4986 24.458 73.7906 25.154C73.0826 25.838 72.1165 26.18 70.8925 26.18ZM70.8925 24.47C71.5405 24.47 72.0385 24.29 72.3865 23.93C72.7345 23.57 72.9085 23.042 72.9085 22.346V19.754C72.9085 19.058 72.7345 18.53 72.3865 18.17C72.0385 17.81 71.5405 17.63 70.8925 17.63C70.2445 17.63 69.7465 17.81 69.3985 18.17C69.0505 18.53 68.8766 19.058 68.8766 19.754V22.346C68.8766 23.042 69.0505 23.57 69.3985 23.93C69.7465 24.29 70.2445 24.47 70.8925 24.47ZM82.7294 12.86H84.6734V26H82.7474V24.11H82.7114C82.6154 24.758 82.3334 25.268 81.8654 25.64C81.4094 26 80.8094 26.18 80.0654 26.18C79.0694 26.18 78.2774 25.844 77.6894 25.172C77.1134 24.5 76.8254 23.594 76.8254 22.454V19.664C76.8254 18.512 77.1134 17.6 77.6894 16.928C78.2774 16.256 79.0694 15.92 80.0654 15.92C80.7974 15.92 81.3974 16.106 81.8654 16.478C82.3334 16.85 82.6154 17.354 82.7114 17.99H82.7834L82.7294 15.686V12.86ZM80.7494 24.488C81.3614 24.488 81.8414 24.302 82.1894 23.93C82.5494 23.546 82.7294 23.018 82.7294 22.346V19.754C82.7294 19.082 82.5494 18.56 82.1894 18.188C81.8414 17.804 81.3614 17.612 80.7494 17.612C80.1134 17.612 79.6214 17.798 79.2734 18.17C78.9374 18.53 78.7694 19.058 78.7694 19.754V22.346C78.7694 23.042 78.9374 23.576 79.2734 23.948C79.6214 24.308 80.1134 24.488 80.7494 24.488ZM94.6384 21.518H88.5904V22.346C88.5904 23.078 88.7644 23.636 89.1124 24.02C89.4724 24.404 90.0004 24.596 90.6964 24.596C91.2604 24.596 91.7044 24.5 92.0284 24.308C92.3644 24.104 92.5744 23.81 92.6584 23.426H94.6024C94.4584 24.266 94.0324 24.938 93.3244 25.442C92.6164 25.934 91.7344 26.18 90.6784 26.18C89.4664 26.18 88.5004 25.832 87.7804 25.136C87.0724 24.428 86.7184 23.498 86.7184 22.346V19.754C86.7184 18.59 87.0724 17.66 87.7804 16.964C88.5004 16.268 89.4664 15.92 90.6784 15.92C91.8904 15.92 92.8504 16.268 93.5584 16.964C94.2784 17.66 94.6384 18.59 94.6384 19.754V21.518ZM88.5904 20.204L92.7664 20.186V19.736C92.7664 19.004 92.5864 18.446 92.2264 18.062C91.8784 17.666 91.3624 17.468 90.6784 17.468C89.9944 17.468 89.4724 17.666 89.1124 18.062C88.7644 18.458 88.5904 19.022 88.5904 19.754V20.204ZM101.543 15.92C102.599 15.92 103.433 16.25 104.045 16.91C104.669 17.558 104.981 18.452 104.981 19.592V20.258H102.983V19.754C102.983 19.058 102.803 18.518 102.443 18.134C102.095 17.75 101.603 17.558 100.967 17.558C100.331 17.558 99.8334 17.75 99.4734 18.134C99.1254 18.518 98.9514 19.058 98.9514 19.754V26H97.0074V16.1H98.8794V17.954C99.1914 16.598 100.079 15.92 101.543 15.92ZM111.778 26C110.842 26 110.092 25.73 109.528 25.19C108.976 24.638 108.7 23.906 108.7 22.994V14.624H105.712V12.86H110.644V22.994C110.644 23.378 110.752 23.684 110.968 23.912C111.184 24.128 111.478 24.236 111.85 24.236H115.054V26H111.778ZM124.317 21.518H118.269V22.346C118.269 23.078 118.443 23.636 118.791 24.02C119.151 24.404 119.679 24.596 120.375 24.596C120.939 24.596 121.383 24.5 121.707 24.308C122.043 24.104 122.253 23.81 122.337 23.426H124.281C124.137 24.266 123.711 24.938 123.003 25.442C122.295 25.934 121.413 26.18 120.357 26.18C119.145 26.18 118.179 25.832 117.459 25.136C116.751 24.428 116.397 23.498 116.397 22.346V19.754C116.397 18.59 116.751 17.66 117.459 16.964C118.179 16.268 119.145 15.92 120.357 15.92C121.569 15.92 122.529 16.268 123.237 16.964C123.957 17.66 124.317 18.59 124.317 19.754V21.518ZM118.269 20.204L122.445 20.186V19.736C122.445 19.004 122.265 18.446 121.905 18.062C121.557 17.666 121.041 17.468 120.357 17.468C119.673 17.468 119.151 17.666 118.791 18.062C118.443 18.458 118.269 19.022 118.269 19.754V20.204ZM128 29.24L129.422 25.424L125.75 16.1H127.874L129.89 21.68C130.034 22.1 130.19 22.658 130.358 23.354C130.526 22.646 130.676 22.088 130.808 21.68L132.68 16.1H134.75L130.052 29.24H128Z" fill="white" />
            <defs>
                <linearGradient id="paint0_linear" x1="19.1505" y1="-6.04349" x2="5.93011" y2="18.2091" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="1" stop-color="#FF1545" />
                </linearGradient>
                <linearGradient id="paint1_linear" x1="19.173" y1="46.1789" x2="5.85798" y2="22.1936" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#CC0000" />
                    <stop offset="0.00239069" stop-color="#CC0000" />
                    <stop offset="0.262" stop-color="#E2091E" />
                    <stop offset="0.5175" stop-color="#F21034" />
                    <stop offset="0.7647" stop-color="#FC1441" />
                    <stop offset="0.9944" stop-color="#FF1545" />
                </linearGradient>
                <linearGradient id="paint2_linear" x1="20.6266" y1="-0.882964" x2="13.1938" y2="20.0472" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="0.7545" stop-color="#FF335C" />
                </linearGradient>
                <linearGradient id="paint3_linear" x1="20.2662" y1="40.1427" x2="13.1982" y2="20.2613" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#E00000" />
                    <stop offset="0.1457" stop-color="#E80D18" />
                    <stop offset="0.3665" stop-color="#F21E36" />
                    <stop offset="0.5845" stop-color="#F9294B" />
                    <stop offset="0.7961" stop-color="#FE3158" />
                    <stop offset="0.9944" stop-color="#FF335C" />
                </linearGradient>
                <linearGradient id="paint4_linear" x1="26.3493" y1="-6.13414" x2="39.7099" y2="18.1248" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#CC0000" />
                    <stop offset="0.2524" stop-color="#E1091C" />
                    <stop offset="0.52" stop-color="#F20F33" />
                    <stop offset="0.7745" stop-color="#FC1440" />
                    <stop offset="1" stop-color="#FF1545" />
                </linearGradient>
                <linearGradient id="paint5_linear" x1="26.952" y1="45.1888" x2="39.7655" y2="22.161" gradientUnits="userSpaceOnUse">
                    <stop offset="0.0573" stop-color="white" />
                    <stop offset="1" stop-color="#FF1545" />
                </linearGradient>
                <linearGradient id="paint6_linear" x1="25.2085" y1="-0.127542" x2="32.4132" y2="20.0274" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#E00000" />
                    <stop offset="0.1106" stop-color="#E60A11" />
                    <stop offset="0.3538" stop-color="#F11C32" />
                    <stop offset="0.5877" stop-color="#F92949" />
                    <stop offset="0.8064" stop-color="#FD3057" />
                    <stop offset="0.9944" stop-color="#FF335C" />
                </linearGradient>
                <linearGradient id="paint7_linear" x1="25.0626" y1="40.7506" x2="32.3586" y2="20.4588" gradientUnits="userSpaceOnUse">
                    <stop stop-color="white" />
                    <stop offset="0.8385" stop-color="#FF335C" />
                </linearGradient>
            </defs>
        </svg>
        <button id="header__button">Войти</button>
    </header>
    <main>
        <div class="main__title">
            <h1>
                <span>Разработаем </span><span>приложение</span><span class="highlighted">?</span>
            </h1>
            <p>
                <span class="highlighted">coderley</span> — это онлайн платформа, где
                вы найдёте: самоучители по различным языкам программирования, задачи,
                тесты и новости из мира технологий.
            </p>
            <a class="main__title__cta" href="/home/">Я в деле</a>
        </div>
        <div class="main__video">
            <video width="1280" height="720" src="../static/video/coderley.mp4" type="video/mp4" autoplay="autoplay" loop="" muted=""></video>
        </div>
        <div class="main__features">
            <div class="features__card">
                <div class="card__icon education"></div>
                <p class="card__title">Обучайся</p>
                <p class="card__text">
                    Здесь вы найдете самоучители по самым востребованным языкам програмирования.
                </p>
            </div>
            <div class="features__card">
                <div class="card__icon tasks"></div>
                <p class="card__title">Решай задачи</p>
                <p class="card__text">
                    Закрепляйте полученные знания путем решения разнообразных заданий.
                </p>
            </div>
            <div class="features__card">
                <div class="card__icon tests"></div>
                <p class="card__title">Выполняй тесты</p>
                <p class="card__text">
                    По ходу изучения обучения проходите тестирование. Это поможет вам лучше закрепить материал.
                </p>
            </div>
            <div class="features__card">
                <div class="card__icon blog"></div>
                <p class="card__title">Читай блог</p>
                <p class="card__text">
                    Читайте новости из мира технологий и программирования. Возможно вы узнаете что-то интересное.
                </p>
            </div>
        </div>
        <div class="main__cta-container">
            <h2 class="cta-container__title">Самое время начать развивать себя</h2>
            <button id="cta-container__button">Начни прямо сейчас -></button>
            <img src="../static/img/home.webp" alt="screenshot" class="cta-container__image" loading="lazy" />
        </div>
    </main>
    <div class="alert">
        <p id="alert__title"></p>
        <button id="alert__btn">ок</button>
    </div>
    <div id="page__block"></div>
    <script src="./static/js/alert.js"></script>
    <script src="./static/js/form.js"></script>
    <?php
    if ($_SESSION['message']) {
        echo '<script>popupAlert("' . $_SESSION['message'] . '");</script>';
        unset($_SESSION['message']);
    }
    ?>
</body>

</html>