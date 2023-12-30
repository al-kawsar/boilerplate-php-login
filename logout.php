<?php
require_once __DIR__ . './helper.php';
session_start();

session_unset();
session_destroy();
setcookie("X-LOGIN", "", time() - 3600);
setcookie("X-USER", "", time() - 3600);
$_SESSION = [];

redirectTo('index.php');
