<?php
require_once 'variables.php';
require_once 'google-api/vendor/autoload.php';
function get_article_list($link, $table)
{
    $sql = "SELECT * FROM `education` WHERE `language`='$table'";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result);
}

function save_test_result($userId, $link, $testId, $result, $expireTime)
{
    $userResults = mysqli_query($link, "SELECT * FROM `user_results` WHERE `id`='$userId'");
    if (mysqli_num_rows($userResults) > 0) {
        $userResults = json_decode(mysqli_fetch_assoc($userResults)['results']);
        $match = 0;
        for ($i = 0; $i < sizeof($userResults); $i++) {
            if ($userResults[$i][0] == $testId) {
                $userResults[$i][1] = $result;
                $userResults[$i][2] = $expireTime;
                $match++;
                break;
            }
        }
        if ($match == 0) {
            array_push($userResults, [$testId, $result, $expireTime]);
        }
        $res = json_encode($userResults);
        mysqli_query($link, "UPDATE `user_results` SET `results` = '$res' WHERE `id` = $userId;");
    } else {
        $userRes = [[$testId, $result, $expireTime]];
        $res = json_encode($userRes);
        mysqli_query($link, "INSERT INTO `user_results` (`id`, `results`) VALUES ('$userId', '$res')");
    }
};

function print_articles_list($list)
{
    $title = $list[0][2];
    echo '<h2 class="main__list__subtitle">' . $title . '</h2>';
    $j = 1;
    $k = 1;
    for ($i = 0; $i < sizeof($list);) {
        if ($title == $list[$i][2]) {
            echo '<div class="main__list__article"><span class="main__list__article__id">' . $k . '.' . $j . ' ' . '</span><a href="./article.php?lang=' . $list[0][1] . '&id=' . $i . '" class="main__list__article__link">' . $list[$i][3] . '</a></div>';
            $i++;
            $j++;
        } else {
            $title = $list[$i][2];
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
        echo '<h2 class="article__title">' . $list[$id][3] . '</h2><div class="article__content">' . $list[$id][4] . '</div>';
        echo '<div class="article__navigation"><a href="./article.php?lang=' . $list[0][1] . '&id=' . ($id - 1) . '" class="article__navigation__cta ' . $prev . '" id="left"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.47577 12.5242L3.99523 8.04369H14V5.95631H3.99523L8.47577 1.47577L7 0L0 7L7 14L8.47577 12.5242Z" fill="#212121" /></svg><span>Предыдущий урок</span></a><a href="./article.php?lang=' . $list[0][1] . '&id=' . ($id + 1) . '" class="article__navigation__cta ' . $next . '" id="right"><span>Следующий урок</span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.52423 12.5242L7 14L14 7L7 0L5.52423 1.47577L10.0048 5.95631H0V8.04369H10.0048L5.52423 12.5242Z" fill="#212121" /></svg></a></div>';
    }
}
function get_tt_list($link, $page, $language)
{
    $sql = "SELECT * FROM `$page` WHERE `language`='$language'";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result);
}
function print_task_list($list, $page, $difficulty)
{
    $number = 1;
    for ($i = 0; $i < sizeof($list); $i++) {
        if ($list[$i][2] == $difficulty) {
            echo '<a href="/' . $page . '/' . substr($page, 0, -1) . '.php?lang=' . $list[$i][1] . '&tId=' . ($list[$i][0] - 1) . '" class="list__section__link">' . $number . '. ' . $list[$i][3] . '</a>';
            $number++;
        }
    }
}
function print_test_list($user, $link, $list, $page, $difficulty)
{
    $userId = $user == '' ? -1 : $user['id'];
    $userResults = mysqli_query($link, "SELECT * FROM `user_results` WHERE `id`='$userId'");
    $number = 1;
    if (mysqli_num_rows($userResults) > 0) {
        $userResults = json_decode(mysqli_fetch_assoc($userResults)['results']);
        for ($i = 0; $i < sizeof($list); $i++) {
            if ($list[$i][2] == $difficulty) {
                echo '<a href="/' . $page . '/' . substr($page, 0, -1) . '.php?lang=' . $list[$i][1] . '&tId=' . ($list[$i][0] - 1) . '" class="list__section__link ' . ($_COOKIE['test-' . $i] == '' ? '' : 'list__section__link--block') . ' ' . ($userResults[$i][2] < time() ? '' : 'list__section__link--block') . ' ' . ($userResults[$i][0] == $i ? 'list__section__link--completed' : '') . '">' . $number . '. ' . $list[$i][3] . '</a>';
                $number++;
            }
        }
    } else {
        for ($i = 0; $i < sizeof($list); $i++) {
            if ($list[$i][2] == $difficulty) {
                echo '<a href="/' . $page . '/' . substr($page, 0, -1) . '.php?lang=' . $list[$i][1] . '&tId=' . ($list[$i][0] - 1) . '" class="list__section__link ' . ($_COOKIE['test-' . $i] == '' ? '' : 'list__section__link--block') . '">' . $number . '. ' . $list[$i][3] . '</a>';
                $number++;
            }
        }
    }
}

function print_user_results($user, $link)
{
    $userId = $user['id'];
    $tests = mysqli_fetch_all(mysqli_query($link, "SELECT * FROM `tests`"));
    $userResults = mysqli_query($link, "SELECT * FROM `user_results` WHERE `id`='$userId'");
    if (mysqli_num_rows($userResults) > 0) {
        $userResults = json_decode(mysqli_fetch_assoc($userResults)['results']);
        for ($i = 0; $i < sizeof($userResults); $i++) {
            echo '<div class="user-progress__block__item ' . $tests[$userResults[$i][0]][2] . '">
 <div class="user-progress__block__item__img ' . $tests[$userResults[$i][0]][1] . '"></div>
 <h3 class="user-progress__block__item__title">' . $tests[$userResults[$i][0]][3] . '</h3>
 <p class="user-progress__block__item__result"><span>Результат: ' . $userResults[$i][1] . '</span></p>
 </div>';
        }
    }
}

function print_sidebar($current)
{
    $home = 'home';
    $education = 'education';
    $tasks = 'tasks';
    $tests = 'tests';
    $blog = 'blog';
    echo '<aside id="sidebar"><a href="/home/" class="sidebar__link ' . ($current == $home ? 'current' : '') . '"><svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.48914 7.34619L10 1.83533L15.5109 7.34619H15.5093V16.5289H4.49004V7.34619H4.48914ZM2.65349 9.18184L1.2977 10.5376L0 9.23992L8.70247 0.537456C9.41907 -0.179152 10.5809 -0.179152 11.2975 0.537456L20 9.23992L18.7023 10.5376L17.3459 9.18119V16.5289C17.3459 17.5432 16.5236 18.3655 15.5093 18.3655H4.49004C3.47574 18.3655 2.65349 17.5432 2.65349 16.5289V9.18184Z" fill="black"/>
    </svg><p class="sidebar__link__name">Главная</p></a><a href="/education/" class="sidebar__link ' . ($current == $education ? 'current' : '') . '"><svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 3V4L11 8L20 4V3L9 0L0 3Z"/><path d="M2 7V11V11.267C2 12.888 6.001 15.16 11 15.001C15 14.875 17.586 13.029 18 11.534C18.024 11.445 18.037 11.356 18.037 11.266V11V7L11 10L6 8.333V11.546L5 11.182V8L2 7Z"/></svg><p class="sidebar__link__name">Обучение</p></a> <a href="/tasks" class="sidebar__link ' . ($current == $tasks ? 'current' : '') . '"><svg viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M27 0H3C1.3455 0 0 1.3455 0 3V24C0 25.6545 1.3455 27 3 27H27C28.6545 27 30 25.6545 30 24V3C30 1.3455 28.6545 0 27 0ZM3 24V6H27L27.003 24H3Z"/><path d="M10.9394 9.43958L5.37891 15.0001L10.9394 20.5606L13.0604 18.4396L9.62091 15.0001L13.0604 11.5606L10.9394 9.43958ZM19.0604 9.43958L16.9394 11.5606L20.3789 15.0001L16.9394 18.4396L19.0604 20.5606L24.6209 15.0001L19.0604 9.43958Z"/></svg><p class="sidebar__link__name">Задачи</p></a> <a href="/tests" class="sidebar__link ' . ($current == $tests ? 'current' : '') . '"><svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.2215 17.5317L9.51145 13.8217L7.15479 16.1784L13.4448 22.4684L22.9481 11.0684L20.3848 8.9317L13.2215 17.5317Z"/><path d="M26.6667 0H3.33333C1.495 0 0 1.495 0 3.33333V26.6667C0 28.505 1.495 30 3.33333 30H26.6667C28.505 30 30 28.505 30 26.6667V3.33333C30 1.495 28.505 0 26.6667 0ZM3.33333 26.6667V3.33333H26.6667L26.67 26.6667H3.33333Z"/></svg><p class="sidebar__link__name">Тесты</p></a> <a href="/blog" class="sidebar__link ' . ($current == $blog ? 'current' : '') . ' sidebar__link__disabled"><svg viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26.8125 0H3.1875C1.4295 0 0 1.3455 0 3V24C0 25.6545 1.4295 27 3.1875 27H26.8125C28.5705 27 30 25.6545 30 24V3C30 1.3455 28.5705 0 26.8125 0ZM26.8125 24H3.1875C3.102 24 3.0435 23.976 3.018 23.976C3.0075 23.976 3.0015 23.979 3 23.988L2.982 3.069C2.9925 3.054 3.06 3 3.1875 3H26.8125C26.931 3.0015 26.9955 3.042 27 3.012L27.018 23.931C27.0075 23.946 26.94 24 26.8125 24Z"/><path d="M6 6H15V15H6V6ZM16.5 18H6V21H16.5H18H24V18H18H16.5ZM18 12H24V15H18V12ZM18 6H24V9H18V6Z"/></svg><p class="sidebar__link__name">Блог</p></a><div class="sidebar__link__wrapper"><a href="https://t.me/coderley" class="sidebar__link " target="_blank"><svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.65 0.110289L0.933877 6.94195C-0.275178 7.42757 -0.268184 8.10204 0.71205 8.40281L5.2605 9.8217L15.7843 3.18189C16.2819 2.87912 16.7365 3.042 16.3628 3.37374L7.83648 11.0687H7.83448L7.83648 11.0697L7.52273 15.7581C7.98237 15.7581 8.18521 15.5472 8.44301 15.2984L10.6523 13.1501L15.2477 16.5444C16.095 17.0111 16.7036 16.7713 16.9144 15.7601L19.931 1.54317C20.2398 0.305136 19.4584 -0.255426 18.65 0.110289Z" fill="black"/>
</svg></a><a href="https://discord.gg/5nczA2d" class="sidebar__link " target="_blank"><svg width="20" height="23" viewBox="0 0 20 23" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.93134 9.58887C7.27931 9.58887 6.76562 10.1603 6.76562 10.858C6.76562 11.5534 7.29134 12.1272 7.93134 12.1272C8.58217 12.1272 9.09585 11.5534 9.09585 10.858C9.10908 10.1591 8.58217 9.58887 7.93134 9.58887ZM12.1034 9.58887C11.4513 9.58887 10.9377 10.1603 10.9377 10.858C10.9377 11.5534 11.4634 12.1272 12.1034 12.1272C12.7542 12.1272 13.2679 11.5534 13.2679 10.858C13.2667 10.1591 12.7542 9.58887 12.1034 9.58887Z" fill="black"/><path d="M17.6577 0H2.34226C1.05143 0 0 1.05143 0 2.35429V17.8057C0 19.1086 1.05143 20.16 2.34226 20.16H15.3023L14.6947 18.0439L16.1588 19.4045L17.5411 20.6845L20 22.8571V2.35429C20 1.05143 18.9486 0 17.6577 0ZM13.2451 14.9257C13.2451 14.9257 12.8337 14.4349 12.492 13.9982C13.9886 13.5747 14.56 12.6388 14.56 12.6388C14.0908 12.9468 13.6457 13.1657 13.2451 13.3149C12.6737 13.5531 12.1263 13.7131 11.5886 13.8057C10.4914 14.0102 9.48571 13.9537 8.62797 13.7937C7.97714 13.6674 7.41774 13.4845 6.94857 13.3005C6.68632 13.1982 6.3988 13.0743 6.11489 12.9143C6.08 12.889 6.04632 12.8794 6.01263 12.8565C5.98857 12.8445 5.97774 12.8337 5.96571 12.8205C5.76 12.7074 5.64571 12.628 5.64571 12.628C5.64571 12.628 6.19429 13.5423 7.64632 13.9765C7.30346 14.4096 6.88 14.9257 6.88 14.9257C4.35489 14.8451 3.39609 13.1886 3.39609 13.1886C3.39609 9.50857 5.0418 6.52632 5.0418 6.52632C6.68752 5.29083 8.25263 5.32571 8.25263 5.32571L8.36692 5.46286C6.30977 6.05835 5.3606 6.9606 5.3606 6.9606C5.3606 6.9606 5.61323 6.82346 6.03549 6.62977C7.25774 6.09323 8.22857 5.94406 8.62917 5.90917C8.69774 5.89835 8.75549 5.88632 8.82406 5.88632C9.5218 5.79489 10.3098 5.77203 11.1314 5.86346C12.2177 5.98977 13.3847 6.30978 14.572 6.9606C14.572 6.9606 13.6686 6.10406 11.7257 5.50977L11.8857 5.32692C11.8857 5.32692 13.452 5.29203 15.0965 6.52752C15.0965 6.52752 16.7423 9.50977 16.7423 13.1898C16.7423 13.1886 15.7714 14.8451 13.2451 14.9257Z" fill="black"/></svg></a></div></aside>';
}

function print_header($user)
{
    $logged = '';
    $form = '';
    if ($user == '') {
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
        $form = '<div id="registration">
        <h3>Авторизация</h3>
        <div class="row" id="social_registration">
            <a id="social_registration__google" href="' . $client->createAuthUrl() . '" onclick="document.cookie = "registrationPage="+location.pathname+"; path=/; expires="+new Date(Date.now() + 300e3).toUTCString();">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.3206 8.18111H10V12.0439H15.3638C14.8646 14.4985 12.7748 15.9089 10 15.9089C6.72823 15.9089 4.09111 13.2718 4.09111 9.99888C4.09111 6.72711 6.72823 4.08999 10 4.08999C11.4092 4.08999 12.683 4.59033 13.6826 5.40855L16.5928 2.49944C14.8198 0.95366 12.5465 0 10 0C4.45489 0 0 4.45377 0 10C0 15.5462 4.45377 20 10 20C15 20 19.5467 16.3633 19.5467 10C19.5467 9.409 19.456 8.77211 19.3206 8.18111Z" fill="#F9F9F9" />
                </svg>
                <span>Войти с помощью Google</span>
            </a>
            <a id="social_registration__yandex" href="' . $url . '?' . urldecode(http_build_query($params)) . '" onclick="document.cookie = \'registrationPage=\'+location.pathname+\'; path=/; expires=\'+new Date(Date.now()+300e3).toUTCString();">
                <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.29716 0H6.4556C3.57673 0 0.671807 2.12586 0.671807 6.87523C0.671807 9.33531 1.71442 11.2513 3.62567 12.3409L0.127549 18.6726C-0.0383099 18.9721 -0.042646 19.3114 0.115909 19.5804C0.270713 19.8431 0.553799 20 0.872861 20H2.64243C3.04442 20 3.35786 19.8057 3.50817 19.4653L6.78806 13.05H7.02743V19.2004C7.02743 19.6339 7.39317 20 7.8261 20H9.37196C9.85747 20 10.1964 19.661 10.1964 19.1756V0.875117C10.1965 0.359883 9.82673 0 9.29716 0ZM7.02743 10.2002H6.60517C4.96786 10.2002 3.9904 8.86375 3.9904 6.62512C3.9904 3.84156 5.22517 2.8498 6.38079 2.8498H7.02743V10.2002Z" fill="black" />
                </svg>
            </a>
        </div>
        <form action="/include/auth.php" method="post">
            <div class="row row_input">
                <label>Email</label>
                <input type="email" name="mail" id="mail" placeholder="Введите email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required required oninvalid="this.setCustomValidity(\'Проверьте введенную почту\')" oninput="setCustomValidity(\'\')">
            </div>
            <div class="row row_input">
                <label>Пароль</label>
                <input type="password" name="pass" id="pass" placeholder="Введите пароль" pattern="(?=.*\d)(?=.*[а-яА-ЯёЁa-zA-Z]).{8,}" required oninvalid="this.setCustomValidity(\'Пароль должен содержать не менее 8 знаков, включать буквы, цифры\')" oninput="setCustomValidity(\'\')">
            </div>
            <div class="row"><button type="submit" id="auth_button">Войти /
                    Зарегестрироваться</button></div>
            <div class="row confidential">Нажимая на кнопку, вы даете согласие на
                обработку своих персональных данных в соответствии с <a href="/confidential.pdf" target="_blank">политикой
                    конфиденциальности</a>.</div>
                    <input type="text" name="page" id="page" value="' . $_SERVER['REQUEST_URI'] . '">
        </form>
    </div><div class="alert">
    <p id="alert__title"></p>
    <button id="alert__btn">ок</button>
</div><div id="page__block"></div>';
        $logged  = '<button id="header__button">Войти</button>';
    } else {
        $logged = '<a href="/profile/" id="header__avatar" style="background-image: url(\'' . $user['avatar'] . '\');background-size:cover;background-position: center center;"></a></header>';
    }

    echo $form . '<header id="header" ' . ($form == '' ? '' : 'class="header--login"') . ' ><button id="header__hamburger"><svg width="20" height="15" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 0H20V2.34457H0V0ZM0 5.86141H20V8.20598H0V5.86141ZM0 11.7228H20V14.0674H0V11.7228Z"/></svg></button> <a href="/home/"><svg version="1.1" class="logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 165 50" style="enable-background:new 0 0 165 50" xml:space="preserve"><path class="st0" d="M71.2,29.8c-0.9-0.8-1.3-2-1.3-3.4v-3.2c0-1.5,0.4-2.6,1.3-3.4c0.9-0.8,2.1-1.3,3.6-1.3c1.5,0,2.6,0.4,3.5,1.1
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
		42.7,-0.2 52.1,16.6 	"/></g><linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="21.6196" y1="-741.2739" x2="1.0423" y2="-783.3615" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#FFF"/><stop offset="2.062309e-02" style="stop-color:#F6FEFD"/><stop offset="0.1816" style="stop-color:#B6F9F2"/><stop offset="0.3406" style="stop-color:#7FF5E7"/><stop offset="0.4934" style="stop-color:#52F1DF"/><stop offset="0.6391" style="stop-color:#2FEFD8"/><stop offset="0.7757" style="stop-color:#15EDD4"/><stop offset="0.8997" style="stop-color:#06EBD1"/><stop offset="1" style="stop-color:#01EBD0"/></linearGradient><polygon class="st2" points="25.7,-0.2 11.5,25 -0.2,25 4.5,16.6 13.9,-0.2 "/><linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="19.3811" y1="-806.5702" x2="8.9082" y2="-772.3841" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0.2391" style="stop-color:#814EFA"/><stop offset="0.3118" style="stop-color:#745DF6"/><stop offset="0.9944" style="stop-color:#01EBD0"/></linearGradient><polygon class="st3" points="25.4,49.8 13.7,49.8 7.8,39.3 4.3,33 -0.2,25 11.5,25 19.5,39.3 "/><linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="27.6471" y1="-746.4598" x2="12.1406" y2="-776.9958" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#FFF"/><stop offset="5.151084e-02" style="stop-color:#E8FFFC"/><stop offset="0.1863" style="stop-color:#B2FFF6"/><stop offset="0.318" style="stop-color:#85FEF0"/><stop offset="0.4435" style="stop-color:#62FEEC"/><stop offset="0.5612" style="stop-color:#49FEE9"/><stop offset="0.6681" style="stop-color:#39FEE7"/><stop offset="0.7545" style="stop-color:#34FEE6"/></linearGradient><polygon class="st4" points="28.3,4.4 28.3,4.4 16.6,25 11.5,25 25.7,-0.2 28.3,4.3 "/><linearGradient id="SVGID_4_" gradientUnits="userSpaceOnUse" x1="22.816" y1="-791.0574" x2="17.5068" y2="-775.0839" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#9C74FB"/><stop offset="0.3148" style="stop-color:#78A4F4"/><stop offset="0.7764" style="stop-color:#47E5EA"/><stop offset="0.9944" style="stop-color:#34FEE6"/></linearGradient><polygon class="st5" points="28.3,45.5 28.3,45.5 28.3,45.5 25.4,49.8 19.5,39.3 11.5,25 16.6,25 24.8,39.3 "/><polygon class="st6" points="28.3,4.4 28.3,4.4 28.3,4.4 28.3,4.3 "/><linearGradient id="SVGID_5_" gradientUnits="userSpaceOnUse" x1="26.544" y1="-731.8248" x2="51.593" y2="-771.6646" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0.1396" style="stop-color:#814EFA"/><stop offset="0.4763" style="stop-color:#4B91E8"/><stop offset="0.832" style="stop-color:#16D2D7"/><stop offset="1" style="stop-color:#01EBD0"/></linearGradient><polygon class="st7" points="56.8,25 45.1,25 31,-0.2 42.7,-0.2 52.1,16.6 "/><linearGradient id="SVGID_6_" gradientUnits="userSpaceOnUse" x1="30.901" y1="-809.2589" x2="51.6558" y2="-769.8961" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0.1925" style="stop-color:#FFF"/><stop offset="0.2092" style="stop-color:#F6FEFD"/><stop offset="0.3392" style="stop-color:#B6F9F2"/><stop offset="0.4675" style="stop-color:#7FF5E7"/><stop offset="0.591" style="stop-color:#52F1DF"/><stop offset="0.7086" style="stop-color:#2FEFD8"/><stop offset="0.8189" style="stop-color:#15EDD4"/><stop offset="0.919" style="stop-color:#06EBD1"/><stop offset="1" style="stop-color:#01EBD0"/></linearGradient><polygon class="st8" points="56.8,25 52.3,33 48.8,39.3 42.9,49.8 31.2,49.8 37.1,39.3 45.1,25 "/><linearGradient id="SVGID_7_" gradientUnits="userSpaceOnUse" x1="27.9981" y1="-744.4692" x2="42.5503" y2="-773.3351" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#9C74FB"/><stop offset="0.3148" style="stop-color:#78A4F4"/><stop offset="0.7764" style="stop-color:#47E5EA"/><stop offset="0.9944" style="stop-color:#34FEE6"/></linearGradient><polygon class="st9" points="45.1,25 40,25 28.3,4.4 28.3,4.4 28.3,4.3 31,-0.2 "/><polygon class="st6" points="28.3,4.4 28.3,4.4 28.3,4.3 "/><linearGradient id="SVGID_8_" gradientUnits="userSpaceOnUse" x1="29.8296" y1="-797.0052" x2="42.9505" y2="-768.8549" gradientTransform="matrix(1 0 0 -1 0 -747)"><stop offset="0" style="stop-color:#FFF"/><stop offset="5.151084e-02" style="stop-color:#E8FFFC"/><stop offset="0.1863" style="stop-color:#B2FFF6"/><stop offset="0.318" style="stop-color:#85FEF0"/><stop offset="0.4435" style="stop-color:#62FEEC"/><stop offset="0.5612" style="stop-color:#49FEE9"/><stop offset="0.6681" style="stop-color:#39FEE7"/><stop offset="0.7545" style="stop-color:#34FEE6"/></linearGradient><polygon class="st10" points="45.1,25 37.1,39.3 31.2,49.8 28.3,45.5 28.3,45.5 28.3,45.5 31.8,39.3 40,25 "/></svg></a>
 <form id="header__search" action="/search/" method="post">
 <input type="search" name="search" id="search" placeholder="Введите запрос" value="' . ($_POST["search"] == "" ? "" : $_POST["search"]) . '" pattern=".{3,}" required oninvalid="this.setCustomValidity(\'Запрос должен быть длиннее 3‑х символов\')" oninput="setCustomValidity(\'\')">
 <button type="submit" id="header__search__button"><svg width="60%" height="60%" version="1.1" viewBox="0 0 20 20" x="0px" y="0px"><g><path fill-rule="evenodd" d="M13.192 14.606a7 7 0 111.414-1.414l3.101 3.1-1.414 1.415-3.1-3.1zM14 9A5 5 0 114 9a5 5 0 0110 0z" clip-rule="evenodd"></path></g></svg>
 </button>
 </form>
 <div id="toggle" class="light">
 <svg width="24" height="24" viewBox="0 0 24 24" id="moon" fill="none" xmlns="http://www.w3.org/2000/svg">
 <path d="M12.1168 11.8832C8.90454 8.66977 8.11814 3.95138 9.75274 0C7.48078 0.448332 5.31061 1.5522 3.54879 3.31402C-1.18293 8.04574 -1.18293 15.7183 3.54879 20.45C8.28172 25.1829 15.953 25.1817 20.686 20.45C22.4478 18.6882 23.5505 16.5192 24 14.2473C20.0474 15.8806 15.329 15.0955 12.1168 11.8832Z" fill="#606060" />
 </svg>
 </div>' . $logged . '</header>';
}

function search($link, $userId, $request)
{

    $request = trim($request);
    $request = $link->real_escape_string($request);
    $request = htmlspecialchars($request);
    $education = mysqli_fetch_all(mysqli_query($link, "SELECT `id`, `language`, `topic`, `subtopic` FROM `education` WHERE `content` LIKE '%$request%' OR `subtopic` LIKE '%$request%' OR `topic` LIKE '%$request%'"));
    $tasks = mysqli_fetch_all(mysqli_query($link, "SELECT `id`, `language`, `topic`, `subtopic` FROM `tasks` WHERE `task` LIKE '%$request%' OR `solution` LIKE '%$request%' OR `subtopic` LIKE '%$request%'"));
    $tests = mysqli_fetch_all(mysqli_query($link, "SELECT `id`, `language`, `topic`, `subtopic` FROM `tests` WHERE `test` LIKE '%$request%' OR `subtopic` LIKE '%$request%'"));
    if (!sizeof($education) && !sizeof($tasks) && !sizeof($tests)) {
        echo 'Извините, мы ничего не нашли.';
    } else {
        for ($i = 0; $i < sizeof($education); $i++) {
            echo '<a href="/' . $education[$i][1] . '/article.php?id=' . $education[$i][0] . '" class="search-result__link education">
 <div class="search-result__link__content">
 <p class="search-result__link__content__theme">Обучение: <span class="text-black">' . ucwords($education[$i][1]) . ' -> ' . $education[$i][2] . '</span></p>
 <p class="search-result__link__content__title">' . $education[$i][3] . '</p>
 </div>
 </a>';
        }
        for ($i = 0; $i < sizeof($tasks); $i++) {
            $topic = '';
            switch ($tasks[$i][2] == 'easy') {
                case 'easy':
                    $topic = 'Начальный';
                    break;
                case 'normal':
                    $topic = 'Средний';
                    break;
                case 'hard':
                    $topic = 'Продвинутый';
                    break;
                default:
                    break;
            }
            echo '<a href="/tasks/task.php?lang=' . $tasks[$i][1] . '&tId=' . ($tasks[$i][0] - 1) . '" class="search-result__link tasks">
 <div class="search-result__link__content">
 <p class="search-result__link__content__theme">Задачи: <span class="text-black">' . ucwords($tasks[$i][1]) . ' -> ' . $topic . ' уровень</span></p>
 <p class="search-result__link__content__title">' . $tasks[$i][3] . '</p>
 </div>
 </a>';
        }
        $userResults = mysqli_query($link, "SELECT * FROM `user_results` WHERE `id`='$userId'");
        if (mysqli_num_rows($userResults) > 0) {
            $userResults = json_decode(mysqli_fetch_assoc($userResults)['results']);
            for ($i = 0; $i < sizeof($tests); $i++) {
                $topic = '';
                switch ($tasks[$i][2] == 'easy') {
                    case 'easy':
                        $topic = 'Начальный';
                        break;
                    case 'normal':
                        $topic = 'Средний';
                        break;
                    case 'hard':
                        $topic = 'Продвинутый';
                        break;
                    default:
                        break;
                }
                echo '<a href="/tests/test.php?lang=' . $tests[$i][1] . '&tId=' . ($tests[$i][0] - 1) . '" class="search-result__link tests ' . ($userResults[$i][0] == $i ? 'search-result__link--completed' : '') . ' ' . ($_COOKIE['test-' . $i] == '' ? '' : 'search-result__link--block') . ' ' . ($userResults[$i][2] < time() ? '' : 'list__section__link--block') . '">
 <div class="search-result__link__content">
 <p class="search-result__link__content__theme">Тесты: <span class="text-black">' . ucwords($tests[$i][1]) . ' -> ' . $topic . ' уровень</span></p>
 <p class="search-result__link__content__title">' . $tests[$i][3] . '</p>
 </div>
 </a>';
            }
        } else {
            for ($i = 0; $i < sizeof($tests); $i++) {
                $topic = '';
                switch ($tasks[$i][2] == 'easy') {
                    case 'easy':
                        $topic = 'Начальный';
                        break;
                    case 'normal':
                        $topic = 'Средний';
                        break;
                    case 'hard':
                        $topic = 'Продвинутый';
                        break;
                    default:
                        break;
                }
                echo '<a href="/tests/test.php?lang=' . $tests[$i][1] . '&tId=' . ($tests[$i][0] - 1) . '" class="search-result__link tests ' . ($_COOKIE['test-' . $i] == '' ? '' : 'search-result__link--block') . '">
 <div class="search-result__link__content">
 <p class="search-result__link__content__theme">Тесты: <span class="text-black">' . ucwords($tests[$i][1]) . ' -> ' . $topic . ' уровень</span></p>
 <p class="search-result__link__content__title">' . $tests[$i][3] . '</p>
 </div>
 </a>';
            }
        }
    }
}

function printCookieMessage()
{
    if (!isset($_COOKIE['cookie-message'])) {
        echo '<div id="cookie-message">
 <p>Псст! Мы используем файлы cookie для предоставления лучшего пользовательского опыта. Без них coderley просто не сможет работать 😭</p>
 <button id="cookie-message__button">хорошо</button>
 </div>';
    }
}

function printCookieMessageScript()
{
    if (!isset($_COOKIE['cookie-message'])) {
        echo '<script>
 window["cookie-message__button"].onclick = () => {
 window["cookie-message"].style = "display:none;";
 let date = new Date();
 date.setTime(date.getTime() + (7*86400e3));
 let expires = date.toUTCString();
 document.cookie = "cookie-message=true; expires=" + expires + "; path=/";
 }
 </script>';
    }
}
