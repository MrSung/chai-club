<?php

$user_name = $_POST['username'];
$password = $_POST['password'];
$user_name = input_validate($user_name);
$password = input_validate($password);
try {
  $sql = 'SELECT user_id, user_name, password FROM ec_user WHERE user_name = :username AND password = :password';
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':username', $user_name);
  $stmt->bindValue(':password', $password);
  $stmt->execute();
  $num = $stmt->rowCount();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($num > 0) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['logged_in'] = time();
    $scs_msg[] = 'ログインできました！';
    $flag = true;
  } else {
    $err_msg[] = 'ユーザー名もしくはパスワードが違います';
    $flag = false;
  }
} catch (PDOException $e) {
  throw $e;
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
  $err_msg[] = 'ログインに失敗しました！';
}