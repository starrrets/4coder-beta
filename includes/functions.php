<?php
function get_article_list($link, $table)
{
    $sql = "SELECT * FROM $table";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result);
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
