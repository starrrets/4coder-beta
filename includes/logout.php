<?php
setcookie('user', '', time() - 3600 * 24 * 7, "/");
header('Location: /');
exit;
