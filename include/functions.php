<?php
require_once 'variables.php';
require_once 'google-api/vendor/autoload.php';
function get_article_list($link, $table)
{
    $sql = "SELECT * FROM `education` WHERE `language`='$table'";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result);
}

function save_test_result($userId, $link, $testId, $testLang, $result, $expireTime)
{
    $userResults = mysqli_query($link, "SELECT * FROM `user_results` WHERE `id`='$userId'");
    if (mysqli_num_rows($userResults) > 0) {
        $userResults = json_decode(mysqli_fetch_assoc($userResults)['results']);
        $match = 0;
        for ($i = 0; $i < sizeof($userResults); $i++) {
            if ($userResults[$i][0] == $testLang . '-' . $testId) {
                $userResults[$i][1] = $result;
                $userResults[$i][2] = $expireTime;
                $match++;
                break;
            }
        }
        if ($match == 0) {
            array_push($userResults, [$testLang . '-' . $testId, $result, $expireTime]);
        }
        $res = json_encode($userResults);
        mysqli_query($link, "UPDATE `user_results` SET `results` = '$res' WHERE `id` = $userId;");
    } else {
        $userRes = [[$testLang . '-' . $testId, $result, $expireTime]];
        $res = json_encode($userRes);
        mysqli_query($link, "INSERT INTO `user_results` (`id`, `results`) VALUES ('$userId', '$res')");
    }
};

function isLanguageExist($link, $table, $language)
{
    $sql = "SELECT 1 FROM `$table` WHERE `language` = '$language' LIMIT 1";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result);
}

function getRowsCount($link, $table, $language)
{
    return mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(1) AS 'count' FROM `$table` WHERE `language` = '$language'"))['count'];
}

function getUsersCount($link)
{
    return mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(1) AS 'count' FROM `users`"))['count'];
}

function getLanguageCount($link, $table)
{
    return mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT( DISTINCT language) as count FROM `$table`;"))['count'];
}

function getTableRowCount($link, $table)
{
    return mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(1) AS 'count' FROM `$table`"))['count'];
}

function getUsersList($link, $role)
{
    switch ($role) {
        case 'owner':
            return mysqli_fetch_all(mysqli_query($link, 'SELECT id, email, username, role, avatar FROM `users` WHERE role != "owner"'));
            break;
        case 'admin':
            return mysqli_fetch_all(mysqli_query($link, 'SELECT id, email, username, role, avatar FROM `users` WHERE role != "owner" AND role != "admin"'));
            break;
        default:
            break;
    }
}

function printUsers($users, $role)
{
    switch ($role) {
        case 'owner':
            for ($i = 0; $i < sizeof($users); $i++) {
                switch ($users[$i][3]) {
                    case 'admin':
                        $secondRole = 'editor';
                        $thirdRole = 'user';
                        break;
                    case 'user':
                        $secondRole = 'editor';
                        $thirdRole = 'admin';
                        break;
                    case 'editor':
                        $secondRole = 'admin';
                        $thirdRole = 'user';
                        break;
                    default:
                        break;
                }
                echo '<div class="user"><div class = "user__avatar" style="background-image:url(\'' . $users[$i][4] . '\')"></div><span class="user__email">email: ' . $users[$i][1] . '</span><span class="user__username">username: ' . $users[$i][2] . '</span><select class="user__role"><option>' . $users[$i][3] . '</option><option>' . $secondRole . '</option><option>' . $thirdRole . '</option></select><a href="../include/changerole.php?id=' . $users[$i][0] . '&role=" class="user__change ' . $users[$i][3] . '" style="filter:grayscale(1);pointer-events:none;"><svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.59514 11.4989L0 6.90381L2.09408 4.80973L4.59514 7.31818L11.9059 0L14 2.09408L4.59514 11.4989Z" fill="black"/></svg></a></div>';
            }
            break;
        case 'admin':
            for ($i = 0; $i < sizeof($users); $i++) {
                echo '<div class="user"><div class = "user__avatar" style="background-image:url(\'' . $users[$i][4] . '\')"></div><span class="user__email">email: ' . $users[$i][1] . '</span><span class="user__username">username: ' . $users[$i][2] . '</span><select class="user__role"><option>' . $users[$i][3] . '</option><option>' . ($users[$i][3] == 'user' ? 'editor' : 'user') . '</option></select><a href="../include/changerole.php?id=' . $users[$i][0] . '&role=" class="user__change ' . $users[$i][3] . '" style="filter:grayscale(1);pointer-events:none;"><svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.59514 11.4989L0 6.90381L2.09408 4.80973L4.59514 7.31818L11.9059 0L14 2.09408L4.59514 11.4989Z" fill="black"/></svg></a></div>';
            }
            break;
        default:
            break;
    }
}
function printDashboardTableList($link, $table, $role)
{
    $args = 'id, language, subtopic';
    if ($table == 'blog') {
        $args = 'id, topic, subtopic';
    }
    $list = [];
    if ($table == 'blog') {
        $list = mysqli_fetch_all(mysqli_query($link, "SELECT " . $args . " FROM `$table` ORDER BY `topic`"));
    } else {
        $list = mysqli_fetch_all(mysqli_query($link, "SELECT " . $args . " FROM `$table` ORDER BY `language`"));
    }
    if (sizeof($list) == 0) {
        echo '<h2><i>Этот раздел пустой<i></h2>';
    } else {
        $summary = $list[0][1];
        echo '<details class="details" open><summary class="deatails__summury">' . ucfirst($summary) . '</summary><div class="details__container">';
        for ($i = 0; $i < sizeof($list);) {
            if ($summary == $list[$i][1]) {
                echo '<div class="deatails__row ' . ($role == 'editor' ? 'editor' : '') . '">' . $list[$i][2] . '<a href="./changecontent.php?table=' . $table . '&id=' . $list[$i][0] . '" class="details__change"><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.60595 13.9565L12.106 6.45636L8.54364 2.89395L1.04354 10.3941C0.940237 10.4974 0.866793 10.6273 0.831282 10.7685L0 15L4.23066 14.1687C4.3727 14.1332 4.50264 14.0598 4.60595 13.9565ZM14.5273 4.03515C15.1576 3.40483 15.1576 2.38308 14.5273 1.75275L13.2472 0.472741C12.6169 -0.15758 11.5952 -0.15758 10.9649 0.472741L9.68484 1.75275L13.2472 5.31516L14.5273 4.03515Z" fill="black"/>
                </svg></a>' . ($role == 'editor' ? '' : '<a href="./deletecontent.php?table=' . $table . '&id=' . $list[$i][0] . '" class="details__delete"  onclick="return confirm(\'Уверены?\');"><svg width="20px" height="20px" version="1.1" viewBox="0 0 20 20" x="0px" y="0px">
                    <path d="M12 2H8v1H3v2h14V3h-5V2zM4 7v9a2 2 0 002 2h8a2 2 0 002-2V7h-2v9H6V7H4z"></path><path d="M11 7H9v7h2V7z"></path></svg></a></div>');
                $i++;
            } else {
                $summary = $list[$i][1];
                echo '</div></details><details class="details"><summary class="deatails__summury">' . ucfirst($summary) . '</summary><div class="details__container">';
            }
        }
        echo '</details>';
    }
}
function print_articles_list($list)
{
    $title = $list[0][2];
    echo '<h2 class="main__list__subtitle">' . $title . '</h2>';
    $j = 1;
    $k = 1;
    for ($i = 0; $i < sizeof($list);) {
        if ($title == $list[$i][2]) {
            echo '<div class="main__list__article"><span class="main__list__article__id">' . $k . '.' . $j . ' ' . '</span><a href="./article.php?lang=' . $list[0][1] . '&id=' . ($list[$i][0] - 1) . '" class="main__list__article__link">' . $list[$i][3] . '</a></div>';
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
    $prev = '';
    $next = '';
    if ($id == 0) {
        $prev = 'article__navigation__cta--disabled';
    } elseif ($id == $list_size) {
        $next = 'article__navigation__cta--disabled';
    }
    echo '<h2 class="article__title">' . $list[$id][3] . '</h2><div class="article__content">' . $list[$id][4] . '</div>';
    echo '<div class="article__navigation"><a href="./article.php?lang=' . $list[0][1] . '&id=' . ($list[$id - 1][0] - 1) . '" class="article__navigation__cta ' . $prev . '" id="left"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.47577 12.5242L3.99523 8.04369H14V5.95631H3.99523L8.47577 1.47577L7 0L0 7L7 14L8.47577 12.5242Z" fill="#212121" /></svg><span>Предыдущий урок</span></a><a href="./article.php?lang=' . $list[0][1] . '&id=' . ($list[$id + 1][0] - 1) . '" class="article__navigation__cta ' . $next . '" id="right"><span>Следующий урок</span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.52423 12.5242L7 14L14 7L7 0L5.52423 1.47577L10.0048 5.95631H0V8.04369H10.0048L5.52423 12.5242Z" fill="#212121" /></svg></a></div>';
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
            echo '<a href="/' . $page . '/' . substr($page, 0, -1) . '.php?lang=' . $list[$i][1] . '&tId=' . ($list[$i][0]) . '" class="list__section__link">' . $number . '. ' . $list[$i][3] . '</a>';
            $number++;
        }
    }
}
function isEqualTestId($item1, $item2, $num)
{
    echo $num;
    if ($item1 == $item2) {

        return $num++;
    } else return false;
}

function print_test_list($user, $link, $list, $page, $difficulty)
{
    $userId = $user == '' ? -1 : $user['id'];
    $userResults = mysqli_query($link, "SELECT * FROM `user_results` WHERE `id`='$userId'");
    $number = 1;
    $uR = 0;
    if (mysqli_num_rows($userResults) > 0) {
        $userResults = json_decode(mysqli_fetch_assoc($userResults)['results']);
        for ($i = 0; $i < sizeof($list); $i++) {
            $blocked = false;
            $completed = false;
            if ($list[$i][2] == $difficulty) {
                $completed = $userResults[$uR][0] == ($list[$i][1] . '-' . ($list[$i][0]));
                $blocked = $_COOKIE['test-' . $list[$i][1] . '-' . ($list[$i][0])] != '' ? true : ($completed && ($userResults[$uR][2] >= time()));
                if ($completed || $blocked) {
                    $uR++;
                }
                echo '<a href="/' . $page . '/' . substr($page, 0, -1) . '.php?lang=' . $list[$i][1] . '&tId=' . ($list[$i][0]) . '" class="list__section__link ' . ($blocked ? 'list__section__link--block' : '') . ' '
                    . ($completed ? 'list__section__link--completed' : '') . '">' . $number . '. ' . $list[$i][3] . '</a>';
                $number++;
            }
        }
    } else {
        for ($i = 0; $i < sizeof($list); $i++) {
            if ($list[$i][2] == $difficulty) {
                echo '<a href="/' . $page . '/' . substr($page, 0, -1) . '.php?lang=' . $list[$i][1] . '&tId=' . ($list[$i][0]) . '" class="list__section__link ' . ($_COOKIE['test-' . $list[$i][1] . '-' . ($list[$i][0])] == '' ? '' : 'list__section__link--block') . '">' . $number . '. ' . $list[$i][3] . '</a>';
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
    $icons = getIcons($link);
    if (mysqli_num_rows($userResults) > 0) {
        $userResults = json_decode(mysqli_fetch_assoc($userResults)['results']);
        for ($i = 0; $i < sizeof($userResults); $i++) {
            $id = explode('-', $userResults[$i][0])[1];
            echo '<div class="user-progress__block__item ' . $tests[$id - 1][2] . '">
 <div class="user-progress__block__item__img">' . $icons[$tests[$id - 1][1]] . '</div>
 <h3 class="user-progress__block__item__title">' . $tests[$id - 1][3] . '</h3>
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
    echo '<aside id="sidebar"><div class="sidebar__container"><button id="sidebar__hamburger"><svg width="20" height="15" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 0H20V2.34457H0V0ZM0 5.86141H20V8.20598H0V5.86141ZM0 11.7228H20V14.0674H0V11.7228Z"/></svg></button> <a href="/home/"><svg class="logo" width="135" height="40" viewBox="0 0 135 40" fill="none" xmlns="http://www.w3.org/2000/svg">
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
</svg></a></div><a href="/home/" class="sidebar__link ' . ($current == $home ? 'current' : '') . '"><svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.48914 7.34619L10 1.83533L15.5109 7.34619H15.5093V16.5289H4.49004V7.34619H4.48914ZM2.65349 9.18184L1.2977 10.5376L0 9.23992L8.70247 0.537456C9.41907 -0.179152 10.5809 -0.179152 11.2975 0.537456L20 9.23992L18.7023 10.5376L17.3459 9.18119V16.5289C17.3459 17.5432 16.5236 18.3655 15.5093 18.3655H4.49004C3.47574 18.3655 2.65349 17.5432 2.65349 16.5289V9.18184Z" fill="black"/>
    </svg><p class="sidebar__link__name">Главная</p></a><a href="/education/" class="sidebar__link ' . ($current == $education ? 'current' : '') . '"><svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 3V4L11 8L20 4V3L9 0L0 3Z"/><path d="M2 7V11V11.267C2 12.888 6.001 15.16 11 15.001C15 14.875 17.586 13.029 18 11.534C18.024 11.445 18.037 11.356 18.037 11.266V11V7L11 10L6 8.333V11.546L5 11.182V8L2 7Z"/></svg><p class="sidebar__link__name">Обучение</p></a> <a href="/tasks" class="sidebar__link ' . ($current == $tasks ? 'current' : '') . '"><svg viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M27 0H3C1.3455 0 0 1.3455 0 3V24C0 25.6545 1.3455 27 3 27H27C28.6545 27 30 25.6545 30 24V3C30 1.3455 28.6545 0 27 0ZM3 24V6H27L27.003 24H3Z"/><path d="M10.9394 9.43958L5.37891 15.0001L10.9394 20.5606L13.0604 18.4396L9.62091 15.0001L13.0604 11.5606L10.9394 9.43958ZM19.0604 9.43958L16.9394 11.5606L20.3789 15.0001L16.9394 18.4396L19.0604 20.5606L24.6209 15.0001L19.0604 9.43958Z"/></svg><p class="sidebar__link__name">Задачи</p></a> <a href="/tests" class="sidebar__link ' . ($current == $tests ? 'current' : '') . '"><svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.2215 17.5317L9.51145 13.8217L7.15479 16.1784L13.4448 22.4684L22.9481 11.0684L20.3848 8.9317L13.2215 17.5317Z"/><path d="M26.6667 0H3.33333C1.495 0 0 1.495 0 3.33333V26.6667C0 28.505 1.495 30 3.33333 30H26.6667C28.505 30 30 28.505 30 26.6667V3.33333C30 1.495 28.505 0 26.6667 0ZM3.33333 26.6667V3.33333H26.6667L26.67 26.6667H3.33333Z"/></svg><p class="sidebar__link__name">Тесты</p></a> <a href="/blog" class="sidebar__link ' . ($current == $blog ? 'current' : '') . '"><svg viewBox="0 0 30 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26.8125 0H3.1875C1.4295 0 0 1.3455 0 3V24C0 25.6545 1.4295 27 3.1875 27H26.8125C28.5705 27 30 25.6545 30 24V3C30 1.3455 28.5705 0 26.8125 0ZM26.8125 24H3.1875C3.102 24 3.0435 23.976 3.018 23.976C3.0075 23.976 3.0015 23.979 3 23.988L2.982 3.069C2.9925 3.054 3.06 3 3.1875 3H26.8125C26.931 3.0015 26.9955 3.042 27 3.012L27.018 23.931C27.0075 23.946 26.94 24 26.8125 24Z"/><path d="M6 6H15V15H6V6ZM16.5 18H6V21H16.5H18H24V18H18H16.5ZM18 12H24V15H18V12ZM18 6H24V9H18V6Z"/></svg><p class="sidebar__link__name">Новости</p></a><div class="sidebar__link__wrapper"><a href="https://t.me/coderley" class="sidebar__link " target="_blank"><svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.65 0.110289L0.933877 6.94195C-0.275178 7.42757 -0.268184 8.10204 0.71205 8.40281L5.2605 9.8217L15.7843 3.18189C16.2819 2.87912 16.7365 3.042 16.3628 3.37374L7.83648 11.0687H7.83448L7.83648 11.0697L7.52273 15.7581C7.98237 15.7581 8.18521 15.5472 8.44301 15.2984L10.6523 13.1501L15.2477 16.5444C16.095 17.0111 16.7036 16.7713 16.9144 15.7601L19.931 1.54317C20.2398 0.305136 19.4584 -0.255426 18.65 0.110289Z" fill="black"/>
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
                обработку своих персональных данных в соответствии с <a href="/privacy.pdf" target="_blank">политикой
                    конфиденциальности</a>.</div>
                    <input type="text" name="page" id="page" value="' . $_SERVER['REQUEST_URI'] . '">
        </form>
    </div>';
        $logged  = '<button id="header__button">Войти</button>';
    } else {
        $logged = '<a href="/profile/" id="header__avatar" style="background-image: url(\'' . $user['avatar'] . '\');background-size:cover;background-position: center center;"></a></header>';
    }

    echo $form . '<header id="header" ' . ($form == '' ? '' : 'class="header--login"') . ' ><button id="header__hamburger"><svg width="20" height="15" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 0H20V2.34457H0V0ZM0 5.86141H20V8.20598H0V5.86141ZM0 11.7228H20V14.0674H0V11.7228Z"/></svg></button> <a href="/home/"><svg class="logo" width="135" height="40" viewBox="0 0 135 40" fill="none" xmlns="http://www.w3.org/2000/svg">
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
    </svg></a>
 <form id="header__search" action="/search/" method="post">
 <input type="search" name="search" id="search" placeholder="Введите запрос" value="' . ($_POST["search"] == "" ? "" : $_POST["search"]) . '" pattern=".{3,}" required oninvalid="this.setCustomValidity(\'Запрос должен быть длиннее 3‑х символов\')" oninput="setCustomValidity(\'\')">
 <button type="submit" id="header__search__button"><svg width="60%" height="60%" version="1.1" viewBox="0 0 20 20" x="0px" y="0px"><g><path fill-rule="evenodd" d="M13.192 14.606a7 7 0 111.414-1.414l3.101 3.1-1.414 1.415-3.1-3.1zM14 9A5 5 0 114 9a5 5 0 0110 0z" clip-rule="evenodd"></path></g></svg>
 </button>
 </form>
 <div id="toggle" class="light">
 <svg width="24" height="24" viewBox="0 0 24 24" id="moon" fill="none" xmlns="http://www.w3.org/2000/svg">
 <path d="M12.1168 11.8832C8.90454 8.66977 8.11814 3.95138 9.75274 0C7.48078 0.448332 5.31061 1.5522 3.54879 3.31402C-1.18293 8.04574 -1.18293 15.7183 3.54879 20.45C8.28172 25.1829 15.953 25.1817 20.686 20.45C22.4478 18.6882 23.5505 16.5192 24 14.2473C20.0474 15.8806 15.329 15.0955 12.1168 11.8832Z" fill="#606060" />
 </svg>
 </div>' . $logged . '</header><div class="alert"> <p id="alert__title"></p> <button id="alert__btn">ок</button></div><div id="page__block"></div>';
}

function get_tt_item($link, $table, $language, $id)
{
    $result = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM `$table` WHERE `id` = '$id'"));
    if ($result) {
        return $result;
    } else {
        header('Location: /' . $table . '/list.php?lang=' . $language);
    }
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
            echo '<a href="/education/article.php?lang=' . $education[$i][1] . '&id=' . ($education[$i][0] - 1) . '" class="search-result__link education">
 <div class="search-result__link__content">
 <p class="search-result__link__content__theme">Обучение: <span class="text-black">' . ucwords($education[$i][1]) . ' -> ' . $education[$i][2] . '</span></p>
 <p class="search-result__link__content__title">' . $education[$i][3] . '</p>
 </div>
 </a>';
        }
        for ($i = 0; $i < sizeof($tasks); $i++) {
            $topic = '';
            switch ($tasks[$i][2]) {
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
            echo '<a href="/tasks/task.php?lang=' . $tasks[$i][1] . '&tId=' . ($tasks[$i][0]) . '" class="search-result__link tasks">
 <div class="search-result__link__content">
 <p class="search-result__link__content__theme">Задачи: <span class="text-black">' . ucwords($tasks[$i][1]) . ' -> ' . $topic . ' уровень</span></p>
 <p class="search-result__link__content__title">' . $tasks[$i][3] . '</p>
 </div>
 </a>';
        }
        $userResults = mysqli_query($link, "SELECT * FROM `user_results` WHERE `id`='$userId'");
        if (mysqli_num_rows($userResults) > 0) {
            $userResults = json_decode(mysqli_fetch_assoc($userResults)['results']);
            $uR = 0;
            for ($i = 0; $i < sizeof($tests); $i++) {
                $topic = '';
                $completed = $userResults[$uR][0] == ($tests[$i][1] . '-' . ($tests[$i][0]));
                $blocked = $_COOKIE['test-' . $tests[$i][1] . '-' . ($tests[$i][0])] != '' ? true : ($completed && ($userResults[$uR][2] >= time()));
                if ($completed || $blocked) {
                    $uR++;
                }
                switch ($tests[$i][2]) {
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
                $tId = $tests[$i][0];
                echo '<a href="/tests/test.php?lang=' . $tests[$i][1] . '&tId=' . $tId . '" class="search-result__link tests ' .
                    ($completed ? 'search-result__link--completed' : '') . ' ' .
                    ($blocked ? 'search-result__link--block' : '') . '">
 <div class="search-result__link__content">
 <p class="search-result__link__content__theme">Тесты: <span class="text-black">' . ucwords($tests[$i][1]) . ' -> ' . $topic . ' уровень</span></p>
 <p class="search-result__link__content__title">' . $tests[$i][3] . '</p>
 </div>
 </a>';
            }
        } else {
            for ($i = 0; $i < sizeof($tests); $i++) {
                $topic = '';
                switch ($tests[$i][2]) {
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
                $tId = $tests[$i][0];
                echo '<a href="/tests/test.php?lang=' . $tests[$i][1] . '&tId=' . $tId . '" class="search-result__link tests ' .
                    ($_COOKIE['test-' . $tests[$i][1] . '-' . $tId] == '' ? '' : 'search-result__link--block') . '">
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

function printDashboardAddContent($table)
{
    $languages = ['python', 'c', 'c#', 'c++', 'css', 'go', 'html', 'java', 'javascript', 'kotlin', 'php', 'r', 'ruby', 'sql', 'swift'];
    $levels = ['easy', 'normal', 'hard'];
    $languageOptions = '';
    $levelOptions = '';
    foreach ($languages as $language) {
        $languageOptions .= '<option>' . $language . '</option>';
    }
    foreach ($levels as $level) {
        $levelOptions .= '<option value="' . $level . '">' . $level . '</option>';
    }
    switch ($table) {
        case 'tasks':
            echo '<div class="inputs"><h3>Заголовок:</h3><input name="subtopic" id="subtopic" required><h3>ЯП:</h3><select name="language" id="language">' . $languageOptions . '</select><h3>Сложность:</h3><select name="topic" id="topic">' . $levelOptions . '</select></div><div class="editor"><h3>Условие задачи:</h3><textarea name="task" id="task" cols="30" rows="10" required></textarea></div><div class="editor"><h3>Решение задачи:</h3><textarea name="solution" id="solution" cols="30" rows="10" required></textarea></div>';
            break;
        case 'tests':
            echo '<textarea name="test" id="test"  style="display: none;"></textarea><textarea name="answers" id="answers"  style="display: none;"></textarea><div class="inputs"><h3>Название:</h3><input name="subtopic" id="subtopic" required><h3>ЯП:</h3><select name="language" id="language">' . $languageOptions . '</select><h3>Сложность:</h3><select name="topic" id="topic">' . $levelOptions . '</select><h3>Таймер:</h3><input type="number" name="timer" id="timer" min="3" value="3" required></div><h2 class="questions-h">Вопросы</h2><div class="questions"><button type="button" id="questions-add">+</button></div>';
            break;
        case 'blog':
            echo '<div class="inputs"><h3>Раздел:</h3><input name="topic" id="topic" required><h3>Заголовок:</h3><input name="subtopic" id="subtopic" required><h3>Превью:</h3><input type="url" name="picture" id="picture" placeholder="URL картинки для превью" ></div><div class="editor"><h3>Контент:</h3><textarea name="content" id="content" cols="30" rows="10" required></textarea></div>';
            break;
        case 'education':
            echo '<div class="inputs"><h3>Заголовок:</h3><input name="subtopic" id="subtopic" required><h3>Раздел:</h3><input name="topic" id="topic" required><h3>ЯП:</h3><select name="language" id="language">' . $languageOptions . '</select></div><div class="editor"><h3>Контент:</h3><textarea name="content" id="content" cols="30" rows="10" required></textarea></div>';
            break;
        default:
            break;
    }
}
function printDashboardChangeContent($table, $item)
{
    $languages = ['python', 'c', 'c#', 'c++', 'css', 'go', 'html', 'java', 'javascript', 'kotlin', 'php', 'r', 'ruby', 'sql', 'swift'];
    $levels = ['easy', 'normal', 'hard'];
    $languageOptions = '';
    $levelOptions = '';
    foreach ($languages as $language) {
        $languageOptions .= '<option ' . ($item['language'] == $language ? 'selected' : '') . '>' . $language . '</option>';
    }
    foreach ($levels as $level) {
        $levelOptions .= '<option value="' . $level . '" ' . ($item['topic'] == $level ? 'selected' : '') . '>' . $level . '</option>';
    }
    switch ($table) {
        case 'tasks':
            echo '<div class="inputs"><h3>Заголовок:</h3><input name="subtopic" id="subtopic" required value="' . $item['subtopic'] . '"><h3>ЯП:</h3><select name="language" id="language">' . $languageOptions . '</select><h3>Сложность:</h3><select name="topic" id="topic">' . $levelOptions . '</select></div><div class="editor"><h3>Условие задачи:</h3><textarea name="task" id="task" cols="30" rows="10" required>' . $item['task'] . '</textarea></div><div class="editor"><h3>Решение задачи:</h3><textarea name="solution" id="solution" cols="30" rows="10" required>' . $item['solution'] . '</textarea></div>';
            break;
        case 'tests':
            echo '<textarea name="test" id="test"  style="display: none;">' . $item['test'] . '</textarea><textarea name="answers" id="answers"  style="display: none;">' . $item['answers'] . '</textarea><div class="inputs"><h3>Название:</h3><input name="subtopic" id="subtopic" required value="' . $item['subtopic'] . '"><h3>ЯП:</h3><select name="language" id="language">' . $languageOptions . '</select><h3>Сложность:</h3><select name="topic" id="topic">' . $levelOptions . '</select><h3>Таймер:</h3><input type="number" name="timer" id="timer" min="3" value="' . $item['timer'] . '" required></div><h2 class="questions-h">Вопросы</h2><div class="questions"><button type="button" id="questions-add">+</button></div>';
            break;
        case 'blog':
            echo '<div class="inputs"><h3>Раздел:</h3><input name="topic" id="topic" required value="' . $item['topic'] . '"><h3>Заголовок:</h3><input name="subtopic" id="subtopic" required value="' . $item['subtopic'] . '"><h3>Превью:</h3><input type="url" name="picture" id="picture" placeholder="URL картинки для превью"  value="' . $item['preview'] . '"></div><div class="editor"><h3>Контент:</h3><textarea name="content" id="content" cols="30" rows="10" required>' . $item['content'] . '</textarea></div>';
            break;
        case 'education':
            echo '<div class="inputs"><h3>Заголовок:</h3><input name="subtopic" id="subtopic" required value="' . $item['subtopic'] . '><h3>Раздел:</h3><input name="topic" id="topic" required value="' . $item['topic'] . '><h3>ЯП:</h3><select name="language" id="language">' . $languageOptions . '</select></div><div class="editor"><h3>Контент:</h3><textarea name="content" id="content" cols="30" rows="10" required>' . $item['content'] . '</textarea></div>';
            break;
        default:
            break;
    }
}
function getIcons($link)
{
    $query = mysqli_query($link, "SELECT language, icon FROM `language_icons` WHERE 1");
    $result = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $result[$row['language']] = $row['icon'];
    }
    return $result;
}

function getAllLanguagesInTable($link, $table)
{
    return mysqli_fetch_all(mysqli_query($link, "SELECT DISTINCT language FROM `$table`"));
}

function printBlogFilter($link, $filter)
{
    $topics = mysqli_fetch_all(mysqli_query($link, "SELECT DISTINCT topic FROM `blog`"));
    echo '<nav class="filter">
    <a href="./" class="filter-link ' . ($filter == '' ? 'filter-link__current' : '') . '">ВСЕ СТАТЬИ</a>';
    for ($i = 0; $i < sizeof($topics); $i++) {
        echo '<a href="./index.php?filter=' . $topics[$i][0] . '" class="filter-link ' . ($filter == $topics[$i][0] ? 'filter-link__current' : '') . '">' . mb_strtoupper($topics[$i][0]) . '</a>';
    }
    echo '</nav>';
}

function printBlogCards($link, $filter, $start_from, $limit)
{
    $articles = [];
    if ($filter == '') {
        $articles = mysqli_fetch_all(mysqli_query($link, "SELECT * FROM `blog` ORDER BY `date` DESC LIMIT $start_from,$limit"));
    } else {
        $articles = mysqli_fetch_all(mysqli_query($link, "SELECT * FROM `blog`  WHERE `topic` = '$filter' ORDER BY `date` DESC LIMIT $start_from,$limit"));
    }
    if (sizeof($articles) == 0) {
        echo 'Пока что статей нет.';
    } else {
        for ($i = 0; $i < sizeof($articles); $i++) {
            echo '<a href="./article.php?id=' . $articles[$i][0] . '" class="card">
        <div class="card__image" style="background-image: url(\'' . $articles[$i][4] . '\');"></div>
        <p class="card__topic">' . mb_strtoupper($articles[$i][1]) . '</p>
        <p class="card__date">' . (new DateTime($articles[$i][3]))->format('d.m.Y')  . '</p>
        <p class="card__title">' . $articles[$i][2] . '</p></a>';
        }
    }
}
function getPageList($tpages, $page, $max_length)
{
    if ($tpages <= $max_length) {
        return range(1, $tpages);
    }
    if ($page <= $max_length - 5) {
        return array_merge(range(1, $max_length - 3), array(0, $tpages - 1, $tpages));
    }
    if ($page >= $tpages - $max_length + 6) {
        return array_merge(array(1, 2, 0), range($tpages - $max_length + 4, $tpages));
    }
    $size = $max_length - 6;
    $first = $page - floor(($size - 1) / 2);
    return array_merge(
        array(1, 2, 0),
        range($first, $first + $size - 1),
        array(0, $tpages - 1, $tpages)
    );
}

function getBlogArticle($link, $id)
{
    return mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM `blog` WHERE `id` = '$id'"));
}
