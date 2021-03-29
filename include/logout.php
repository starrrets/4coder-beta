<?php
setcookie('user', '', time() - 3600 * 24 * 30, "/");
header('Location: /');
exit;
