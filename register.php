<?php

$user_name = $_POST['username'];
$password = $_POST['password'];
$user_name = input_validate($user_name);
$password = input_validate($password);
try {
  $sql = 'SELECT COUNT(user_name) AS num FROM ec_user WHERE user_name = :username';
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':username', $user_name);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row['num'] > 0) {
    $err_msg[] = 'そのユーザー名は既に使われています！';
  }
  if (count($err_msg) === 0) {
    $sql = 'INSERT INTO ec_user (user_name, password) VALUES (:username, :password)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':username', $user_name, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $result = $stmt->execute();
    if ($RESULT) {
      $scs_msg[] = '新規登録完了しました！';
    }
  }
} catch (PDOException $e) {
  throw $e;
}
