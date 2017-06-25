<?php

session_start();

require_once 'conf/vars.php';
require_once 'conf/messages.php';
require_once 'conf/setting.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

$path_to_img = './images/';
$data = [];
$scs_msg = [];
$err_msg = [];
$new_img_filename = '';
$item_name = '';
$item_price = 0;
$item_stock = 0;
$item_status = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Assign each value to variable
  $itemInfo = $_POST['itemInfo'];
  $itemStock = $_POST['itemStock'];
  $statusId = $_POST['statusIdUpdate'];
  $statusVal = $_POST['statusValUpdate'];
  if (isset($itemInfo)) {
    // Assign each value to variable
    $item_name = $_POST['item_name'];
    $item_price = $_POST['price'];
    $item_stock = $_POST['stockInsert'];
    $item_status = $_POST['statusInsert'];
    // Validate form
    $item_name = input_validate($item_name);
    $item_price = input_validate($item_price);
    $item_stock = input_validate($item_stock);
    $item_status = input_validate($item_status);
    // Check if empty
    $item_name = (!empty($item_name) ? $item_name : $err_msg[] = '商品名を入力してください！');
    if (empty($item_price) && $item_price == '') {
      $err_msg[] = '商品の値段を入力してください！';
    }
    if (empty($item_stock) && $item_stock == '') {
      $err_msg[] = '商品の個数を入力してください！';
    }
    // Validate if positive integer including 0
    if (preg_match('/-[1-9]{0,10}/', $item_price)) {
      $err_msg[] = '商品の値段を0円以上で入力してください！';
    }
    if (preg_match('/-[1-9]{0,10}/', $item_stock)) {
      $err_msg[] = '商品の個数を0個以上で入力してください！';
    }
    if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
      $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
      if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
        $new_img_filename = sha1(uniqid(mt_rand(), true)) . '.' . $extension;
        if (is_file($path_to_img . $new_img_filename) !== TRUE) {
          if (move_uploaded_file($_FILES['new_img']['tmp_name'], $path_to_img . $new_img_filename) !== TRUE) {
            $err_msg[] = 'ファイルアップロードに失敗しました';
          }
        } else {
          $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
        }
      } else {
        $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGまたはPNGのみ利用可能です。';
      }
    } else {
      $err_msg[] = 'ファイルを選択してください';
    }
    if (count($err_msg) === 0) {
      $scs_msg[] = '追加成功！！';
    }
  }
  if (isset($itemStock)) {
    $item_stock = $_POST['stockUpdate'];
    $item_stock = input_validate($item_stock);
    if (preg_match('/-[1-9]{0,10}/', $item_stock)) {
      $err_msg[] = '商品の個数を0個以上で入力してください！';
    }
    if (count($err_msg) === 0) {
      $scs_msg[] = '在庫変更成功！！';
    }
  }
}

try {
  $dbh = new PDO(DSN, DB_USER, DB_PASS);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($itemInfo)) {
      // Begin transaction
      $dbh->beginTransaction();
      try {
        // Insert into table ec_item_master
        $sql = 'INSERT INTO ec_item_master (img,item_name,price,status,stock) VALUE (?,?,?,?,?)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $new_img_filename, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_name, PDO::PARAM_STR);
        $stmt->bindValue(3, $item_price, PDO::PARAM_INT);
        $stmt->bindValue(4, $item_status, PDO::PARAM_INT);
        $stmt->bindValue(5, $item_stock, PDO::PARAM_INT);
        $stmt->execute();
        // Insert into table ec_cart
        $item_id = $dbh->lastInsertId('id');
        $sql = 'INSERT INTO ec_cart (item_id,item_name,amount) VALUE (?,?,?)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $item_name, PDO::PARAM_INT);
        $stmt->bindValue(3, $item_stock, PDO::PARAM_INT);
        $stmt->execute();
        // Commit
        $dbh->commit();
      } catch (PDOException $e) {
        // Rollback
        $dbh->rollback();
        throw $e;
      }
    }
    if (isset($itemStock)) {
      $item_id = $itemStock;
      // Update table ec_item_master
      try {
        $sql = 'UPDATE ec_item_master SET stock = ? WHERE item_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $item_stock, PDO::PARAM_INT);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
      } catch (PDOException $e) {
        throw $e;
      }
    }
    if (isset($statusId)) {
      $item_id = $statusId;
      $statusVal = (is_numeric($statusVal) ? (int)$statusVal : 0);
      $statusVal ? $item_status = 0 : $item_status = 1 ;
      try {
        $sql = 'UPDATE ec_item_master SET status = ? WHERE item_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $item_status, PDO::PARAM_INT);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
      } catch (PDOException $e) {
        throw $e;
      }
    }
  }
  try {
    // Select from inner-joined table
    $sql = 'SELECT item_id,img,item_name,price,status,stock FROM ec_item_master';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
      $data[] = $row;
    }
  } catch (PDOException $e) {
    throw $e;
  }
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'DBエラー：' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>商品情報管理ページ</title>
  <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Montserrat:300,400,700" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/styles.css">
  <style>
    h1 {  
      margin-bottom: .5rem;
    }
    hr {
      margin-top: 1rem;
      margin-bottom: 1rem;
    }
    table {
      width: 960px;
      border-collapse: collapse;
    }
    table, tr, th, td {
      border: solid 1px;
      padding: 10px;
      text-align: center;
    }
    th, td {
      min-width: 80px;
    }
    table img {
      max-width: 400px;
      height: auto;
    }
    select {
      height: 1.75rem;
      margin-top: .25rem;
      margin-bottom: .25rem;
    }
    input[type="number"] {
      width: 100px;
    }
    .container {
      padding-bottom: 3rem;
    }
    .success {
      color: green;
    }
    .error {
      color: red;
    }
  </style>
  <script src="https://use.fontawesome.com/31b052511e.js"></script>
</head>
<body>
  <div class="wrap">
    <?php foreach ($err_msg as $value): ?>
      <p class="label label-error"><?php print $value; ?><span class="label__close pull-right">&times;</span></p>
    <?php endforeach; ?>
    <?php foreach ($scs_msg as $value): ?>
      <p class="label label-success"><?php print $value; ?><span class="label__close pull-right">&times;</span></p>
    <?php endforeach; ?>
    <div class="container">
      <h1>商品情報管理ページ</h1>
      <form method="post" enctype="multipart/form-data">
        <div><label for="item_name">商品の名前：</label><input type="text" name="item_name" id="item_name" placeholder="item name"></div>
        <div><label for="price">商品の値段：</label><input type="number" name="price" id="price" placeholder="100">円</div>
        <div><label for="stock">商品の個数：</label><input type="number" name="stockInsert" id="stock" placeholder="100">個</div>
        <div>
          <select name="statusInsert">
            <option value="0">非公開</option>
            <option value="1" selected>公開</option>
          </select>
        </div>
        <div><input type="file" name="new_img"></div>
        <input type="hidden" name="itemInfo">
        <div><input type="submit" value="商品追加"></div>
      </form>
      <hr>
      <table>
        <tr>
          <th>画像</th>
          <th>商品名</th>
          <th>価格</th>
          <th>在庫数</th>
          <th>ステータス</th>
        </tr>
        <?php foreach ($data as $value): ?>
          <tr>
            <td><img src="<?php print $path_to_img . $value['img']; ?>" alt="<?php print $value['item_name']; ?>"></td>
            <td><?php print $value['item_name']; ?></td>
            <td><?php print $value['price']; ?>円</td>
            <td>
              <form method="post" enctype="multipart/form-data">
                <input type="number" name="stockUpdate" placeholder="100" value="<?php print $value['stock']; ?>">個
                <input type="hidden" name="itemStock" value="<?php print $value['item_id']; ?>">
                <input type="submit" value="変更">
              </form>
            </td>
            <td>
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="statusIdUpdate" value="<?php print $value['item_id']; ?>">
                <input type="hidden" name="statusValUpdate" value="<?php print $value['status']; ?>">
                <?php if ($value['status']): ?>
                  <input type="submit" value="公開 → 非公開" class="is-show">
                <?php else: ?>
                  <input type="submit" value="非公開 → 公開" class="is-hide">
                <?php endif; ?>
              </form>
            </td>
          <tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
  <script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="dist/js/vendor/jquery-2.2.4.min.js"><\/script>')</script>
  <script>
    $('.is-hide').parents('tr').attr('bgColor', 'lightgray');
    $('.label__close').click(function(){
      $(this).parent().hide();
    });
  </script>
</body>
</html>
