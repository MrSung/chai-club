<?php

require_once('setting.php');

$dbhOptions = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_EMULATE_PREPARES => false
);

try {
  $dbh = new PDO(DSN, DB_USER, DB_PASS, $dbhOptions);
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'DBエラー：' . $e->getMessage();
}
