<?php
require_once '../include/db.php';
require_once '../include/HTMLMinifier.php';
if (isset($_POST)) {
    switch ($_POST['table']) {
        case 'tasks':
            $id = $_POST['id'];
            $topic = $_POST['topic'];
            $subtopic = $_POST['subtopic'];
            $language = $_POST['language'];
            $task = addslashes(HTMLMinifier::process($_POST['task']));
            $solution = addslashes(HTMLMinifier::process($_POST['solution']));
            mysqli_query($link, "UPDATE `tasks` SET `topic` = '$topic', `subtopic` = '$subtopic', `language` = '$language', `task` = '$task', `solution` = '$solution' WHERE  `id` = '$id'");
            break;
        case 'education':
            $id = $_POST['id'];
            $topic = $_POST['topic'];
            $subtopic = $_POST['subtopic'];
            $language = $_POST['language'];
            $html = HTMLMinifier::process($_POST['content']);
            $content = addslashes($html);
            mysqli_query($link, "UPDATE `education` SET `topic`='$topic', `subtopic`='$subtopic', `language`='$language', `content`='$content' WHERE `id`='$id'");
            break;
        case 'blog':
            $id = $_POST['id'];
            $topic = $_POST['topic'];
            $subtopic = $_POST['subtopic'];
            $picture = $_POST['picture'];
            $html = HTMLMinifier::process($_POST['content']);
            $content = addslashes($html);

            mysqli_query($link, "UPDATE blog SET topic = '$topic', subtopic = '$subtopic', preview = '$picture', content = '$content' WHERE id='$id'");

            break;
        case 'tests':
            $id = $_POST['id'];
            $topic = $_POST['topic'];
            $subtopic = $_POST['subtopic'];
            $language = $_POST['language'];
            $timer =  $_POST['timer'];
            $test = $_POST['test'];
            $answers = $_POST['answers'];
            mysqli_query($link, "UPDATE `tests` SET `topic` = '$topic', `subtopic` = '$subtopic', `language` = '$language', `test` = '$test', `answers` = '$answers', `timer` = '$timer' WHERE  `id` = '$id'");
            break;
        default:
            break;
    }
    header('Location: ./list.php?table=' . $_POST['table']);
} else {
    header('Location: ./list.php?table=' . $_POST['table']);
}
