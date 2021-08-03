<?php
session_start();
include('functions.php');
$mysql->query("UPDATE `users` SET `time` = CURRENT_TIMESTAMP WHERE `login` = '$_SESSION[login]'");
$_SESSION =array();
unset($_COOKIE['count']);
setcookie('count', null, -1, '/');
header('Location:index.php')
?>
