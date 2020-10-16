<?php
function get_article_list($link, $table)
{
    $sql = "SELECT * FROM $table";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result);
}

function print_sidebar($current)
{
    $education = 'education';
    $tasks = 'tasks';
    $tests = 'tests';
    $blog = 'blog';
    echo '<aside id="sidebar"><a href="/education/" class="sidebar__link ' . ($current == $education ? 'current' : '') . '"><svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 3V4L11 8L20 4V3L9 0L0 3Z"/><path d="M2 7V11V11.267C2 12.888 6.001 15.16 11 15.001C15 14.875 17.586 13.029 18 11.534C18.024 11.445 18.037 11.356 18.037 11.266V11V7L11 10L6 8.333V11.546L5 11.182V8L2 7Z"/></svg><p class="sidebar__link__name">Обучение</p></a> <a href="/tasks" class="sidebar__link ' . ($current == $tasks ? 'current' : '') . '"><svg viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M27 0H3C1.3455 0 0 1.3455 0 3V24C0 25.6545 1.3455 27 3 27H27C28.6545 27 30 25.6545 30 24V3C30 1.3455 28.6545 0 27 0ZM3 24V6H27L27.003 24H3Z"/><path d="M10.9394 9.43958L5.37891 15.0001L10.9394 20.5606L13.0604 18.4396L9.62091 15.0001L13.0604 11.5606L10.9394 9.43958ZM19.0604 9.43958L16.9394 11.5606L20.3789 15.0001L16.9394 18.4396L19.0604 20.5606L24.6209 15.0001L19.0604 9.43958Z"/></svg><p class="sidebar__link__name">Задачи</p></a> <a href="/tests" class="sidebar__link ' . ($current == $tests ? 'current' : '') . '"><svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.2215 17.5317L9.51145 13.8217L7.15479 16.1784L13.4448 22.4684L22.9481 11.0684L20.3848 8.9317L13.2215 17.5317Z"/><path d="M26.6667 0H3.33333C1.495 0 0 1.495 0 3.33333V26.6667C0 28.505 1.495 30 3.33333 30H26.6667C28.505 30 30 28.505 30 26.6667V3.33333C30 1.495 28.505 0 26.6667 0ZM3.33333 26.6667V3.33333H26.6667L26.67 26.6667H3.33333Z"/></svg><p class="sidebar__link__name">Тесты</p></a> <a href="/blog" class="sidebar__link ' . ($current == $blog ? 'current' : '') . '"><svg viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26.8125 0H3.1875C1.4295 0 0 1.3455 0 3V24C0 25.6545 1.4295 27 3.1875 27H26.8125C28.5705 27 30 25.6545 30 24V3C30 1.3455 28.5705 0 26.8125 0ZM26.8125 24H3.1875C3.102 24 3.0435 23.976 3.018 23.976C3.0075 23.976 3.0015 23.979 3 23.988L2.982 3.069C2.9925 3.054 3.06 3 3.1875 3H26.8125C26.931 3.0015 26.9955 3.042 27 3.012L27.018 23.931C27.0075 23.946 26.94 24 26.8125 24Z"/><path d="M6 6H15V15H6V6ZM16.5 18H6V21H16.5H18H24V18H18H16.5ZM18 12H24V15H18V12ZM18 6H24V9H18V6Z"/></svg><p class="sidebar__link__name">Блог</p></a></aside>';
}

function print_header($user)
{
    echo '<header id="header"><button id="header__hamburger"><svg width="20" height="15" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 0H20V2.34457H0V0ZM0 5.86141H20V8.20598H0V5.86141ZM0 11.7228H20V14.0674H0V11.7228Z"/></svg></button> <svg version="1.1" class="logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 165 50" style="enable-background:new 0 0 165 50" xml:space="preserve"><path class="st0" d="M71.2,29.8c-0.9-0.8-1.3-2-1.3-3.4v-3.2c0-1.5,0.4-2.6,1.3-3.4c0.9-0.8,2.1-1.3,3.6-1.3c1.5,0,2.6,0.4,3.5,1.1
	c0.9,0.8,1.3,1.8,1.4,3.1h-2.4c0-0.7-0.3-1.2-0.7-1.6c-0.4-0.4-1-0.6-1.8-0.6c-0.8,0-1.4,0.2-1.9,0.7c-0.4,0.4-0.7,1.1-0.7,1.9v3.2
	c0,0.8,0.2,1.5,0.7,1.9c0.4,0.5,1.1,0.7,1.9,0.7c0.7,0,1.3-0.2,1.8-0.6c0.4-0.4,0.7-0.9,0.7-1.6h2.4c0,1.3-0.5,2.4-1.4,3.1
	c-0.9,0.8-2,1.2-3.5,1.2C73.3,31.1,72.1,30.7,71.2,29.8z"/><path class="st0" d="M83.2,29.8c-0.9-0.8-1.3-2-1.3-3.4v-3.2c0-1.4,0.4-2.6,1.3-3.4c0.9-0.8,2-1.3,3.5-1.3s2.7,0.4,3.5,1.3
	c0.9,0.8,1.3,2,1.3,3.4v3.2c0,1.4-0.4,2.6-1.3,3.4c-0.9,0.8-2,1.3-3.5,1.3S84.1,30.7,83.2,29.8z M88.6,28.3c0.4-0.4,0.6-1.1,0.6-1.9
	v-3.2c0-0.8-0.2-1.5-0.6-1.9c-0.4-0.4-1-0.7-1.8-0.7s-1.4,0.2-1.8,0.7c-0.4,0.4-0.6,1.1-0.6,1.9v3.2c0,0.8,0.2,1.5,0.6,1.9
	c0.4,0.4,1,0.7,1.8,0.7S88.2,28.8,88.6,28.3z"/><path class="st0" d="M101.2,14.8h2.4v16h-2.4v-2.3h0c-0.1,0.8-0.5,1.4-1,1.9c-0.6,0.4-1.3,0.7-2.2,0.7c-1.2,0-2.2-0.4-2.9-1.2
	C94.4,29,94,27.9,94,26.5v-3.4c0-1.4,0.4-2.5,1.1-3.3c0.7-0.8,1.7-1.2,2.9-1.2c0.9,0,1.6,0.2,2.2,0.7c0.6,0.5,0.9,1.1,1,1.8h0.1
	l-0.1-2.8V14.8z M100.6,28.3c0.4-0.5,0.6-1.1,0.6-1.9v-3.2c0-0.8-0.2-1.5-0.6-1.9c-0.4-0.5-1-0.7-1.8-0.7c-0.8,0-1.4,0.2-1.8,0.7
	c-0.4,0.4-0.6,1.1-0.6,1.9v3.2c0,0.8,0.2,1.5,0.6,1.9c0.4,0.4,1,0.7,1.8,0.7C99.6,29,100.2,28.8,100.6,28.3z"/><path class="st0" d="M115.8,25.4h-7.4v1c0,0.9,0.2,1.6,0.6,2c0.4,0.5,1.1,0.7,1.9,0.7c0.7,0,1.2-0.1,1.6-0.4
	c0.4-0.2,0.7-0.6,0.8-1.1h2.4c-0.2,1-0.7,1.8-1.6,2.5c-0.9,0.6-1.9,0.9-3.2,0.9c-1.5,0-2.7-0.4-3.5-1.3c-0.9-0.9-1.3-2-1.3-3.4v-3.2
	c0-1.4,0.4-2.6,1.3-3.4c0.9-0.8,2-1.3,3.5-1.3c1.5,0,2.7,0.4,3.5,1.3c0.9,0.8,1.3,2,1.3,3.4V25.4z M108.4,23.8l5.1,0v-0.5
	c0-0.9-0.2-1.6-0.6-2.1c-0.4-0.5-1.1-0.7-1.9-0.7s-1.5,0.2-1.9,0.7c-0.4,0.5-0.6,1.2-0.6,2.1V23.8z"/><path class="st0" d="M127.3,19.8c0.8,0.8,1.1,1.9,1.1,3.3v0.8H126v-0.6c0-0.8-0.2-1.5-0.6-2c-0.4-0.5-1-0.7-1.8-0.7
	c-0.8,0-1.4,0.2-1.8,0.7c-0.4,0.5-0.6,1.1-0.6,2v7.6h-2.4V18.8h2.3V21c0.4-1.7,1.5-2.5,3.3-2.5C125.5,18.6,126.5,19,127.3,19.8z"/><path class="st0" d="M136.7,30.9c-1.1,0-2.1-0.3-2.7-1c-0.7-0.7-1-1.6-1-2.7V17h-3.6v-2.2h6v12.4c0,0.5,0.1,0.8,0.4,1.1
	c0.3,0.3,0.6,0.4,1.1,0.4h3.9v2.2H136.7z"/><path class="st0" d="M152,25.4h-7.4v1c0,0.9,0.2,1.6,0.6,2c0.4,0.5,1.1,0.7,1.9,0.7c0.7,0,1.2-0.1,1.6-0.4c0.4-0.2,0.7-0.6,0.8-1.1
	h2.4c-0.2,1-0.7,1.8-1.6,2.5c-0.9,0.6-1.9,0.9-3.2,0.9c-1.5,0-2.7-0.4-3.5-1.3c-0.9-0.9-1.3-2-1.3-3.4v-3.2c0-1.4,0.4-2.6,1.3-3.4
	c0.9-0.8,2-1.3,3.5-1.3c1.5,0,2.7,0.4,3.5,1.3c0.9,0.8,1.3,2,1.3,3.4V25.4z M144.7,23.8l5.1,0v-0.5c0-0.9-0.2-1.6-0.6-2.1
	c-0.4-0.5-1.1-0.7-1.9-0.7c-0.8,0-1.5,0.2-1.9,0.7c-0.4,0.5-0.6,1.2-0.6,2.1V23.8z"/><path class="st0" d="M156.6,34.8l1.7-4.7l-4.5-11.4h2.6l2.5,6.8c0.2,0.5,0.4,1.2,0.6,2c0.2-0.9,0.4-1.5,0.5-2l2.3-6.8h2.5l-5.7,16
	H156.6z"/><g><polygon class="st1" points="28.3,4.4 16.6,25 24.8,39.3 28.3,45.5 25.4,49.8 13.7,49.8 7.8,39.3 4.3,33 -0.2,25 4.5,16.6 
		13.9,-0.2 25.7,-0.2 28.3,4.3 	"/><polygon class="st1" points="56.8,25 52.3,33 48.8,39.3 42.9,49.8 31.2,49.8 28.3,45.5 31.8,39.3 40,25 28.3,4.4 28.3,4.3 31,-0.2 
		42.7,-0.2 52.1,16.6 	"/></g><linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="21.6196" y1="-741.2739" x2="1.0423" y2="-783.3615" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#FFF"/><stop offset="2.062309e-02" style="stop-color:#F6FEFD"/><stop offset="0.1816" style="stop-color:#B6F9F2"/><stop offset="0.3406" style="stop-color:#7FF5E7"/><stop offset="0.4934" style="stop-color:#52F1DF"/><stop offset="0.6391" style="stop-color:#2FEFD8"/><stop offset="0.7757" style="stop-color:#15EDD4"/><stop offset="0.8997" style="stop-color:#06EBD1"/><stop offset="1" style="stop-color:#01EBD0"/></linearGradient><polygon class="st2" points="25.7,-0.2 11.5,25 -0.2,25 4.5,16.6 13.9,-0.2 "/><linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="19.3811" y1="-806.5702" x2="8.9082" y2="-772.3841" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0.2391" style="stop-color:#814EFA"/><stop offset="0.3118" style="stop-color:#745DF6"/><stop offset="0.9944" style="stop-color:#01EBD0"/></linearGradient><polygon class="st3" points="25.4,49.8 13.7,49.8 7.8,39.3 4.3,33 -0.2,25 11.5,25 19.5,39.3 "/><linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="27.6471" y1="-746.4598" x2="12.1406" y2="-776.9958" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#FFF"/><stop offset="5.151084e-02" style="stop-color:#E8FFFC"/><stop offset="0.1863" style="stop-color:#B2FFF6"/><stop offset="0.318" style="stop-color:#85FEF0"/><stop offset="0.4435" style="stop-color:#62FEEC"/><stop offset="0.5612" style="stop-color:#49FEE9"/><stop offset="0.6681" style="stop-color:#39FEE7"/><stop offset="0.7545" style="stop-color:#34FEE6"/></linearGradient><polygon class="st4" points="28.3,4.4 28.3,4.4 16.6,25 11.5,25 25.7,-0.2 28.3,4.3 "/><linearGradient id="SVGID_4_" gradientUnits="userSpaceOnUse" x1="22.816" y1="-791.0574" x2="17.5068" y2="-775.0839" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#9C74FB"/><stop offset="0.3148" style="stop-color:#78A4F4"/><stop offset="0.7764" style="stop-color:#47E5EA"/><stop offset="0.9944" style="stop-color:#34FEE6"/></linearGradient><polygon class="st5" points="28.3,45.5 28.3,45.5 28.3,45.5 25.4,49.8 19.5,39.3 11.5,25 16.6,25 24.8,39.3 "/><polygon class="st6" points="28.3,4.4 28.3,4.4 28.3,4.4 28.3,4.3 "/><linearGradient id="SVGID_5_" gradientUnits="userSpaceOnUse" x1="26.544" y1="-731.8248" x2="51.593" y2="-771.6646" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0.1396" style="stop-color:#814EFA"/><stop offset="0.4763" style="stop-color:#4B91E8"/><stop offset="0.832" style="stop-color:#16D2D7"/><stop offset="1" style="stop-color:#01EBD0"/></linearGradient><polygon class="st7" points="56.8,25 45.1,25 31,-0.2 42.7,-0.2 52.1,16.6 "/><linearGradient id="SVGID_6_" gradientUnits="userSpaceOnUse" x1="30.901" y1="-809.2589" x2="51.6558" y2="-769.8961" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0.1925" style="stop-color:#FFF"/><stop offset="0.2092" style="stop-color:#F6FEFD"/><stop offset="0.3392" style="stop-color:#B6F9F2"/><stop offset="0.4675" style="stop-color:#7FF5E7"/><stop offset="0.591" style="stop-color:#52F1DF"/><stop offset="0.7086" style="stop-color:#2FEFD8"/><stop offset="0.8189" style="stop-color:#15EDD4"/><stop offset="0.919" style="stop-color:#06EBD1"/><stop offset="1" style="stop-color:#01EBD0"/></linearGradient><polygon class="st8" points="56.8,25 52.3,33 48.8,39.3 42.9,49.8 31.2,49.8 37.1,39.3 45.1,25 "/><linearGradient id="SVGID_7_" gradientUnits="userSpaceOnUse" x1="27.9981" y1="-744.4692" x2="42.5503" y2="-773.3351" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#9C74FB"/><stop offset="0.3148" style="stop-color:#78A4F4"/><stop offset="0.7764" style="stop-color:#47E5EA"/><stop offset="0.9944" style="stop-color:#34FEE6"/></linearGradient><polygon class="st9" points="45.1,25 40,25 28.3,4.4 28.3,4.4 28.3,4.3 31,-0.2 "/><polygon class="st6" points="28.3,4.4 28.3,4.4 28.3,4.3 "/><linearGradient id="SVGID_8_" gradientUnits="userSpaceOnUse" x1="29.8296" y1="-797.0052" x2="42.9505" y2="-768.8549" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#FFF"/><stop offset="5.151084e-02" style="stop-color:#E8FFFC"/><stop offset="0.1863" style="stop-color:#B2FFF6"/><stop offset="0.318" style="stop-color:#85FEF0"/><stop offset="0.4435" style="stop-color:#62FEEC"/><stop offset="0.5612" style="stop-color:#49FEE9"/><stop offset="0.6681" style="stop-color:#39FEE7"/><stop offset="0.7545" style="stop-color:#34FEE6"/></linearGradient><polygon class="st10" points="45.1,25 37.1,39.3 31.2,49.8 28.3,45.5 28.3,45.5 28.3,45.5 31.8,39.3 40,25 "/></svg>
    <form id="header__search">
        <input type="search" name="search" id="search" placeholder="Введите запрос">
        <button type="submit" id="header__search__button"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.68323 11.2298C2.54447 11.2298 0 8.8435 0 5.89989C0 2.95628 2.54447 0.570007 5.68323 0.570007C8.82199 0.570007 11.3665 2.95628 11.3665 5.89989C11.3665 7.13156 10.921 8.26566 10.1729 9.1682L14 12.7574L12.9953 13.6996L9.16821 10.1104C8.20584 10.812 6.99656 11.2298 5.68323 11.2298ZM9.94568 5.89989C9.94568 8.1076 8.03734 9.8973 5.68329 9.8973C3.32924 9.8973 1.4209 8.1076 1.4209 5.89989C1.4209 3.69218 3.32924 1.90248 5.68329 1.90248C8.03734 1.90248 9.94568 3.69218 9.94568 5.89989Z" fill="#606060" />
            </svg>
        </button>
    </form>
    <div id="toggle" class="light">
        <svg width="24" height="24" viewBox="0 0 24 24" id="moon" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.1168 11.8832C8.90454 8.66977 8.11814 3.95138 9.75274 0C7.48078 0.448332 5.31061 1.5522 3.54879 3.31402C-1.18293 8.04574 -1.18293 15.7183 3.54879 20.45C8.28172 25.1829 15.953 25.1817 20.686 20.45C22.4478 18.6882 23.5505 16.5192 24 14.2473C20.0474 15.8806 15.329 15.0955 12.1168 11.8832Z" fill="#606060" />
        </svg>
    </div>
    <a href="/profile/" id="header__avatar" style="background-image: url(\'' . $user['avatar'] . '\');background-size:cover;"></a></header>';
}

function print_articles_list($list)
{
    $title = $list[0][1];
    echo '<h2 class="main__list__subtitle">' . $title . '</h2>';
    $j = 1;
    $k = 1;
    for ($i = 0; $i < sizeof($list);) {
        if ($title == $list[$i][1]) {
            echo '<div class="main__list__article"><span class="main__list__article__id">' . $k . '.' . $j . ' ' . '</span><a href="./article.php?id=' . $i . '" class="main__list__article__link">' . $list[$i][2] . '</a></div>';
            $i++;
            $j++;
        } else {
            $title = $list[$i][1];
            $j = 1;
            $k += 1;
            echo '<h2 class="main__list__subtitle">' . $title . '</h2>';
        }
    }
}
function print__article($list, $id)
{
    $list_size = sizeof($list) - 1;
    if ($id < 0 || $id > $list_size) {
        echo '<div class="error" id="error__404">
        <div class="error__text">Ошибка 404</div>
        <button id="error__button">Назад</button></div>';
    } else {
        $prev = '';
        $next = '';
        if ($id == 0) {
            $prev = 'article__navigation__cta--disabled';
        } elseif ($id == $list_size) {
            $next = 'article__navigation__cta--disabled';
        }
        echo '<h2 class="article__title">' . $list[$id][2] . '</h2><div class="article__content">' . $list[$id][3] . '</div>';
        echo '<div class="article__navigation"><a href="article.php?id=' . ($id - 1) . '" class="article__navigation__cta ' . $prev . '" id="left"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.47577 12.5242L3.99523 8.04369H14V5.95631H3.99523L8.47577 1.47577L7 0L0 7L7 14L8.47577 12.5242Z" fill="#212121" /></svg><span>Предыдущий урок</span></a><a href="article.php?id=' . ($id + 1) . '" class="article__navigation__cta ' . $next . '" id="right"><span>Следующий урок</span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.52423 12.5242L7 14L14 7L7 0L5.52423 1.47577L10.0048 5.95631H0V8.04369H10.0048L5.52423 12.5242Z" fill="#212121" /></svg></a></div>';
    }
}
