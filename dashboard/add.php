<?php
require_once '../include/db.php';
require_once '../include/HTMLMinifier.php';
if (isset($_POST)) {
    switch ($_POST['table']) {
        case 'tasks':
            $topic = $_POST['topic'];
            $subtopic = $_POST['subtopic'];
            $language = $_POST['language'];
            $task = HTMLMinifier::process($_POST['task']);
            $solution = HTMLMinifier::process($_POST['solution']);;
            mysqli_query($link, "INSERT INTO `tasks` (`topic`, `subtopic`, `language`, `task`, `solution`) VALUES ('$topic', '$subtopic','$language', '$task','$solution')");
            break;
        case 'education':
            $topic = $_POST['topic'];
            $subtopic = $_POST['subtopic'];
            $language = $_POST['language'];
            $content = HTMLMinifier::process($_POST['content']);
            mysqli_query($link, "INSERT INTO `education` (`topic`, `subtopic`, `language`, `content`) VALUES ('$topic', '$subtopic','$language', '$content')");
            break;
        case 'blog':
            $topic = mb_strtolower($_POST['topic']);
            $subtopic = $_POST['subtopic'];
            $content = HTMLMinifier::process($_POST['content']);
            $picture = $_POST['picture'] == NULL ? 'https://picsum.photos/seed/' . $topic . $subtopic . date("d.m.Y") . '/300/300' : $_POST['picture'];
            mysqli_query($link, "INSERT INTO `blog` (`topic`, `subtopic`, `content`,`preview`) VALUES ('$topic', '$subtopic','$content','$picture')");
            break;
        case 'tests':
            $topic = $_POST['topic'];
            $subtopic = $_POST['subtopic'];
            $language = $_POST['language'];
            $timer = $_POST['timer'];
            $test = $_POST['test'];
            $answers = $_POST['answers'];;
            mysqli_query($link, "INSERT INTO `tests` (`topic`, `subtopic`, `language`,`timer`, `test`, `answers`) VALUES ('$topic', '$subtopic','$language', '$timer', '$test','$answers')");
            break;
        default:
            break;
    }
    header('Location: ./list.php?table=' . $_POST['table']);
} else {
    header('Location: ./list.php?table=' . $_POST['table']);
}
