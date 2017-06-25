<?php

session_start();

unset($_SESSION['user_id']);
session_destroy();
$flag = false;

header('Location: index.php');
exit;
