<?php
session_start();
require_once '../include/db.php';
require_once '../include/functions.php';
$user = '';
if ($_COOKIE['user']) {
	$user = json_decode($_COOKIE['user'], true);
}
if ($user['role'] == 'user' || $user['role'] == 'editor') {
	header('Location: /home/');
}

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
	</noscript>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
	<link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">
	<link rel="stylesheet" href="../static/css/secondary.min.css">
	<link rel="stylesheet" href="../static/css/admin.min.css">
	<title>Панель администратора - coderley</title>
	<meta name="description" content="Панель администратора - coderley">
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
		print_sidebar('');
		?>
		<div id="wrapper__block-elements"></div>
		<?php print_header($user); ?>
		<main id="main" class='dashboard'>
			<div class="dashboard-card role">
				<h2 class="dashboard-card__title">Роль:</h2>
				<p class="dashboard-card__role"><?php echo strtoupper($user['role']); ?></p>
			</div>
			<a href="./users.php" class="<?php echo $user['role'] == 'editor' ? 'disabled' : ''; ?>">
				<div class="dashboard-card users">
					<h2 class="dashboard-card__title">Пользователей:</h2>
					<p class="dashboard-card__count"><?php echo getUsersCount($link); ?></p>
				</div>
			</a>
			<a href="./list.php?table=education">
				<div class="dashboard-card education">
					<h2 class="dashboard-card__title">Обучение:</h2>
					<p class="dashboard-card__info">Языков: <?php echo getLanguageCount($link, 'education'); ?></p>
					<p class="dashboard-card__info">Уроков: <?php echo getTableRowCount($link, 'education'); ?></p>
				</div>
			</a>
			<a href="./list.php?table=tasks">
				<div class="dashboard-card tasks">
					<h2 class="dashboard-card__title">Задачи:</h2>
					<p class="dashboard-card__info">Языков: <?php echo getLanguageCount($link, 'tasks'); ?></p>
					<p class="dashboard-card__info">Задач: <?php echo getTableRowCount($link, 'tasks'); ?></p>
				</div>
			</a>
			<a href="./list.php?table=tests">
				<div class="dashboard-card tests">
					<h2 class="dashboard-card__title">Тесты:</h2>
					<p class="dashboard-card__info">Языков: <?php echo getLanguageCount($link, 'tests'); ?></p>
					<p class="dashboard-card__info">Тестов: <?php echo getTableRowCount($link, 'tests'); ?></p>
				</div>
			</a>
			<a href="./list.php?table=blog">
				<div class="dashboard-card blog">
					<h2 class="dashboard-card__title">Блог:</h2>
					<p class="dashboard-card__info">Статей: <?php echo getTableRowCount($link, 'blog'); ?></p>
				</div>
			</a>
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