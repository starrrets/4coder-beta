<?php
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
    <title>Обучение - 4coder</title>
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
        <aside id="sidebar">
            <a href="/" class="sidebar__link">
                <svg viewBox="0 0 30 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.73371 11.0193L15 2.75299L23.2663 11.0193H23.2646V24.7934H6.73567V11.0193H6.73371ZM3.98085 13.7721L1.94655 15.8064L0 13.8599L13.0537 0.806184C14.1286 -0.268728 15.8714 -0.268728 16.9463 0.806184L30 13.8599L28.0534 15.8064L26.0194 13.7724V24.7934C26.0194 26.3148 24.786 27.5482 23.2646 27.5482H6.73567C5.21423 27.5482 3.98085 26.3148 3.98085 24.7934V13.7721Z" />
                </svg>
                <p class="sidebar__link__name">Главная</p>
            </a>
            <a href="/education/" class="sidebar__link current">
                <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 3V4L11 8L20 4V3L9 0L0 3Z" />
                    <path d="M2 7V11V11.267C2 12.888 6.001 15.16 11 15.001C15 14.875 17.586 13.029 18 11.534C18.024 11.445 18.037 11.356 18.037 11.266V11V7L11 10L6 8.333V11.546L5 11.182V8L2 7Z" />
                </svg>
                <p class="sidebar__link__name">Обучение</p>
            </a>
            <a href="/tasks" class="sidebar__link">
                <svg viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M27 0H3C1.3455 0 0 1.3455 0 3V24C0 25.6545 1.3455 27 3 27H27C28.6545 27 30 25.6545 30 24V3C30 1.3455 28.6545 0 27 0ZM3 24V6H27L27.003 24H3Z" />
                    <path d="M10.9394 9.43958L5.37891 15.0001L10.9394 20.5606L13.0604 18.4396L9.62091 15.0001L13.0604 11.5606L10.9394 9.43958ZM19.0604 9.43958L16.9394 11.5606L20.3789 15.0001L16.9394 18.4396L19.0604 20.5606L24.6209 15.0001L19.0604 9.43958Z" />
                </svg>
                <p class="sidebar__link__name">Задачи</p>
            </a>
            <a href="/tests" class="sidebar__link">
                <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.2215 17.5317L9.51145 13.8217L7.15479 16.1784L13.4448 22.4684L22.9481 11.0684L20.3848 8.9317L13.2215 17.5317Z" />
                    <path d="M26.6667 0H3.33333C1.495 0 0 1.495 0 3.33333V26.6667C0 28.505 1.495 30 3.33333 30H26.6667C28.505 30 30 28.505 30 26.6667V3.33333C30 1.495 28.505 0 26.6667 0ZM3.33333 26.6667V3.33333H26.6667L26.67 26.6667H3.33333Z" />
                </svg>
                <p class="sidebar__link__name">Тесты</p>
            </a>
            <a href="/blog" class="sidebar__link">
                <svg viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26.8125 0H3.1875C1.4295 0 0 1.3455 0 3V24C0 25.6545 1.4295 27 3.1875 27H26.8125C28.5705 27 30 25.6545 30 24V3C30 1.3455 28.5705 0 26.8125 0ZM26.8125 24H3.1875C3.102 24 3.0435 23.976 3.018 23.976C3.0075 23.976 3.0015 23.979 3 23.988L2.982 3.069C2.9925 3.054 3.06 3 3.1875 3H26.8125C26.931 3.0015 26.9955 3.042 27 3.012L27.018 23.931C27.0075 23.946 26.94 24 26.8125 24Z" />
                    <path d="M6 6H15V15H6V6ZM16.5 18H6V21H16.5H18H24V18H18H16.5ZM18 12H24V15H18V12ZM18 6H24V9H18V6Z" />
                </svg>
                <p class="sidebar__link__name">Блог</p>
            </a>
        </aside>
        <div id="wrapper__block-elements"></div>
        <header id="header">
            <button id="header__hamburger">
                <svg width="20" height="15" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0H20V2.34457H0V0ZM0 5.86141H20V8.20598H0V5.86141ZM0 11.7228H20V14.0674H0V11.7228Z" />
                </svg>
            </button>
            <svg version="1.1" class="logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 150 50" style="enable-background:new 0 0 150 50;" xml:space="preserve">
                <g>
                    <polygon class="st0" points="28.4,4.6 16.7,25.2 24.9,39.5 28.4,45.7 25.5,50 13.8,50 7.9,39.5 4.4,33.2 -0.1,25.2 4.6,16.8 14,0 
    25.8,0 28.4,4.5 	" />
                    <polygon class="st0" points="56.9,25.2 52.4,33.2 48.9,39.5 43,50 31.3,50 28.4,45.7 31.9,39.5 40.1,25.2 28.4,4.6 28.4,4.5 
    31.1,0 42.8,0 52.2,16.8 	" />
                </g>
                <g>
                    <path class="st1" d="M79,26.9V22h2.9v12.7H79v-4.9h-9.8l5.3-15.1h3L73.2,27H79V26.9z" />
                    <path class="st1" d="M93.8,20.4c1,0.5,1.8,1.4,2.3,2.4L93.6,24c-0.5-1.1-1.7-1.7-3.1-1.7c-2.1,0-3.5,1.3-3.5,3.5v2.5
    c0,2.1,1.3,3.5,3.5,3.5c1.4,0,2.5-0.6,3.1-1.7l2.5,1.3c-0.5,1-1.3,1.8-2.3,2.4c-1,0.5-2.1,0.8-3.3,0.8c-0.9,0-1.7-0.1-2.5-0.5
    c-0.8-0.3-1.5-0.7-2-1.3c-0.6-0.6-1-1.3-1.3-2c-0.3-0.8-0.5-1.6-0.5-2.5v-2.5c0-0.9,0.1-1.7,0.5-2.5c0.3-0.8,0.7-1.5,1.3-2.1
    c0.6-0.6,1.3-1,2-1.3c0.8-0.3,1.6-0.5,2.5-0.5C91.7,19.5,92.9,19.8,93.8,20.4z" />
                    <path class="st1" d="M106.7,20c0.8,0.3,1.5,0.7,2.1,1.3c0.6,0.6,1,1.3,1.3,2.1c0.3,0.8,0.5,1.6,0.5,2.5v2.5c0,0.9-0.1,1.7-0.5,2.5
    c-0.3,0.8-0.7,1.5-1.3,2c-0.6,0.6-1.3,1-2.1,1.3c-0.8,0.3-1.6,0.5-2.5,0.5s-1.7-0.1-2.5-0.5c-0.8-0.3-1.5-0.7-2-1.3
    c-0.6-0.6-1-1.3-1.3-2c-0.3-0.8-0.5-1.6-0.5-2.5v-2.5c0-0.9,0.1-1.7,0.5-2.5c0.3-0.8,0.7-1.5,1.3-2.1c0.6-0.6,1.3-1,2-1.3
    c0.8-0.3,1.6-0.5,2.5-0.5C105.1,19.5,105.9,19.7,106.7,20z M100.7,25.8v2.5c0,2.1,1.3,3.5,3.5,3.5c2.1,0,3.5-1.3,3.5-3.5v-2.5
    c0-2.1-1.3-3.5-3.5-3.5C102,22.4,100.7,23.7,100.7,25.8z" />
                    <path class="st1" d="M122,34.6c0,0-2.6,0-3.5,0s-1.7-0.1-2.5-0.5c-0.8-0.3-1.5-0.7-2.1-1.3s-1-1.3-1.3-2c-0.3-0.8-0.5-1.6-0.5-2.5
    v-2.5c0-0.9,0.1-1.7,0.5-2.5c0.3-0.8,0.7-1.5,1.3-2.1c0.6-0.6,1.3-1,2.1-1.3c0.8-0.3,1.6-0.5,2.5-0.5s1.7,0.2,2.5,0.5
    c0.3,0.1,0.7,0.3,0.9,0.5v-5.8h2.9v20H122z M115.1,25.8v2.5c0,2.1,1.3,3.5,3.5,3.5c2.1,0,3.5-1.3,3.5-3.5v-2.5
    c0-2.1-1.3-3.5-3.5-3.5C116.4,22.4,115.1,23.7,115.1,25.8z" />
                    <path class="st1" d="M136,20c0.8,0.3,1.5,0.7,2.1,1.3c0.6,0.6,1,1.3,1.3,2.1c0.3,0.8,0.5,1.6,0.5,2.5v2.7H130
    c0.1,2,1.4,3.3,3.5,3.3c1,0,1.9-0.3,2.5-0.9l1.9,2.1c-1.1,1.1-2.7,1.6-4.4,1.6c-0.9,0-1.7-0.1-2.5-0.5c-0.8-0.3-1.5-0.7-2-1.3
    c-0.6-0.6-1-1.3-1.3-2c-0.3-0.8-0.5-1.6-0.5-2.5v-2.5c0-0.9,0.1-1.7,0.5-2.5c0.3-0.8,0.7-1.5,1.3-2.1s1.3-1,2-1.3
    c0.8-0.3,1.6-0.5,2.5-0.5C134.4,19.5,135.2,19.7,136,20z M130,25.6h6.9c-0.1-2-1.4-3.3-3.5-3.3C131.4,22.4,130.1,23.6,130,25.6z" />
                    <path class="st1" d="M144.9,22.3v3.5v8.8H142V19.5h2.9c0,0,2.6,0,3.5,0h1.4v2.9L144.9,22.3z" />
                </g>
                <g>
                    <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="21.7234" y1="793.4509" x2="1.1461" y2="835.5385" gradientTransform="matrix(1 0 0 1 0 -799)">
                        <stop offset="0" style="stop-color:#FFFFFF" />
                        <stop offset="2.062309e-02" style="stop-color:#F6FEFD" />
                        <stop offset="0.1816" style="stop-color:#B6F9F2" />
                        <stop offset="0.3406" style="stop-color:#7FF5E7" />
                        <stop offset="0.4934" style="stop-color:#52F1DF" />
                        <stop offset="0.6391" style="stop-color:#2FEFD8" />
                        <stop offset="0.7757" style="stop-color:#15EDD4" />
                        <stop offset="0.8997" style="stop-color:#06EBD1" />
                        <stop offset="1" style="stop-color:#01EBD0" />
                    </linearGradient>
                    <polygon class="st2" points="25.8,0 11.6,25.2 -0.1,25.2 4.6,16.8 14,0 	" />
                    <linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="19.4848" y1="858.7472" x2="9.0119" y2="824.5611" gradientTransform="matrix(1 0 0 1 0 -799)">
                        <stop offset="0.2391" style="stop-color:#814EFA" />
                        <stop offset="0.3118" style="stop-color:#745DF6" />
                        <stop offset="0.9944" style="stop-color:#01EBD0" />
                    </linearGradient>
                    <polygon class="st3" points="25.5,50 13.8,50 7.9,39.5 4.4,33.2 -0.1,25.2 11.6,25.2 19.6,39.5 	" />
                    <linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="27.7508" y1="798.6368" x2="12.2443" y2="829.1728" gradientTransform="matrix(1 0 0 1 0 -799)">
                        <stop offset="0" style="stop-color:#FFFFFF" />
                        <stop offset="5.151084e-02" style="stop-color:#E8FFFC" />
                        <stop offset="0.1863" style="stop-color:#B2FFF6" />
                        <stop offset="0.318" style="stop-color:#85FEF0" />
                        <stop offset="0.4435" style="stop-color:#62FEEC" />
                        <stop offset="0.5612" style="stop-color:#49FEE9" />
                        <stop offset="0.6681" style="stop-color:#39FEE7" />
                        <stop offset="0.7545" style="stop-color:#34FEE6" />
                    </linearGradient>
                    <polygon class="st4" points="28.4,4.6 28.4,4.6 16.7,25.2 11.6,25.2 25.8,0 28.4,4.5 	" />
                    <linearGradient id="SVGID_4_" gradientUnits="userSpaceOnUse" x1="22.9197" y1="843.2344" x2="17.6105" y2="827.2609" gradientTransform="matrix(1 0 0 1 0 -799)">
                        <stop offset="0" style="stop-color:#9C74FB" />
                        <stop offset="0.3148" style="stop-color:#78A4F4" />
                        <stop offset="0.7764" style="stop-color:#47E5EA" />
                        <stop offset="0.9944" style="stop-color:#34FEE6" />
                    </linearGradient>
                    <polygon class="st5" points="28.4,45.7 28.4,45.7 28.4,45.7 25.5,50 19.6,39.5 11.6,25.2 16.7,25.2 24.9,39.5 	" />
                    <polygon class="st6" points="28.4,4.6 28.4,4.6 28.4,4.6 28.4,4.5 	" />
                    <linearGradient id="SVGID_5_" gradientUnits="userSpaceOnUse" x1="26.6478" y1="784.0018" x2="51.6968" y2="823.8415" gradientTransform="matrix(1 0 0 1 0 -799)">
                        <stop offset="0.1396" style="stop-color:#814EFA" />
                        <stop offset="0.4763" style="stop-color:#4B91E8" />
                        <stop offset="0.832" style="stop-color:#16D2D7" />
                        <stop offset="1" style="stop-color:#01EBD0" />
                    </linearGradient>
                    <polygon class="st7" points="56.9,25.2 45.2,25.2 31.1,0 42.8,0 52.2,16.8 	" />
                    <linearGradient id="SVGID_6_" gradientUnits="userSpaceOnUse" x1="31.0048" y1="861.4359" x2="51.7596" y2="822.0731" gradientTransform="matrix(1 0 0 1 0 -799)">
                        <stop offset="0.1925" style="stop-color:#FFFFFF" />
                        <stop offset="0.2092" style="stop-color:#F6FEFD" />
                        <stop offset="0.3392" style="stop-color:#B6F9F2" />
                        <stop offset="0.4675" style="stop-color:#7FF5E7" />
                        <stop offset="0.591" style="stop-color:#52F1DF" />
                        <stop offset="0.7086" style="stop-color:#2FEFD8" />
                        <stop offset="0.8189" style="stop-color:#15EDD4" />
                        <stop offset="0.919" style="stop-color:#06EBD1" />
                        <stop offset="1" style="stop-color:#01EBD0" />
                    </linearGradient>
                    <polygon class="st8" points="56.9,25.2 52.4,33.2 48.9,39.5 43,50 31.3,50 37.2,39.5 45.2,25.2 	" />
                    <linearGradient id="SVGID_7_" gradientUnits="userSpaceOnUse" x1="28.1019" y1="796.6462" x2="42.6541" y2="825.5121" gradientTransform="matrix(1 0 0 1 0 -799)">
                        <stop offset="0" style="stop-color:#9C74FB" />
                        <stop offset="0.3148" style="stop-color:#78A4F4" />
                        <stop offset="0.7764" style="stop-color:#47E5EA" />
                        <stop offset="0.9944" style="stop-color:#34FEE6" />
                    </linearGradient>
                    <polygon class="st9" points="45.2,25.2 40.1,25.2 28.4,4.6 28.4,4.6 28.4,4.5 31.1,0 	" />
                    <polygon class="st6" points="28.4,4.6 28.4,4.6 28.4,4.5 	" />
                    <linearGradient id="SVGID_8_" gradientUnits="userSpaceOnUse" x1="29.9333" y1="849.1822" x2="43.0542" y2="821.0319" gradientTransform="matrix(1 0 0 1 0 -799)">
                        <stop offset="0" style="stop-color:#FFFFFF" />
                        <stop offset="5.151084e-02" style="stop-color:#E8FFFC" />
                        <stop offset="0.1863" style="stop-color:#B2FFF6" />
                        <stop offset="0.318" style="stop-color:#85FEF0" />
                        <stop offset="0.4435" style="stop-color:#62FEEC" />
                        <stop offset="0.5612" style="stop-color:#49FEE9" />
                        <stop offset="0.6681" style="stop-color:#39FEE7" />
                        <stop offset="0.7545" style="stop-color:#34FEE6" />
                    </linearGradient>
                    <polygon class="st10" points="45.2,25.2 37.2,39.5 31.3,50 28.4,45.7 28.4,45.7 28.4,45.7 31.9,39.5 40.1,25.2 	" />
                </g>
            </svg>
            <form id="header__search">
                <input type="search" name="search" id="search" placeholder="Введите запрос">
                <button id="header__search__button"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.68323 11.2298C2.54447 11.2298 0 8.8435 0 5.89989C0 2.95628 2.54447 0.570007 5.68323 0.570007C8.82199 0.570007 11.3665 2.95628 11.3665 5.89989C11.3665 7.13156 10.921 8.26566 10.1729 9.1682L14 12.7574L12.9953 13.6996L9.16821 10.1104C8.20584 10.812 6.99656 11.2298 5.68323 11.2298ZM9.94568 5.89989C9.94568 8.1076 8.03734 9.8973 5.68329 9.8973C3.32924 9.8973 1.4209 8.1076 1.4209 5.89989C1.4209 3.69218 3.32924 1.90248 5.68329 1.90248C8.03734 1.90248 9.94568 3.69218 9.94568 5.89989Z" fill="#606060" />
                    </svg>
                </button>
            </form>
            <div id="toggle" class="light">
                <svg width="24" height="24" viewBox="0 0 24 24" id="moon" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.1168 11.8832C8.90454 8.66977 8.11814 3.95138 9.75274 0C7.48078 0.448332 5.31061 1.5522 3.54879 3.31402C-1.18293 8.04574 -1.18293 15.7183 3.54879 20.45C8.28172 25.1829 15.953 25.1817 20.686 20.45C22.4478 18.6882 23.5505 16.5192 24 14.2473C20.0474 15.8806 15.329 15.0955 12.1168 11.8832Z" fill="#606060" />
                </svg>
            </div>
            <a href="/profile/" id="header__avatar">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 0C7.3264 0 0 7.3264 0 16C0 24.6736 7.3264 32 16 32C24.6736 32 32 24.6736 32 16C32 7.3264 24.6736 0 16 0ZM16 8C18.7632 8 20.8 10.0352 20.8 12.8C20.8 15.5648 18.7632 17.6 16 17.6C13.2384 17.6 11.2 15.5648 11.2 12.8C11.2 10.0352 13.2384 8 16 8ZM7.8304 23.6352C9.2656 21.5232 11.6592 20.1152 14.4 20.1152H17.6C20.3424 20.1152 22.7344 21.5232 24.1696 23.6352C22.1248 25.824 19.224 27.2 16 27.2C12.776 27.2 9.8752 25.824 7.8304 23.6352Z" fill="#606060" />
                </svg>
            </a>
        </header>
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