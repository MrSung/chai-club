<?php

session_start();

require_once 'conf/vars.php';
require_once 'conf/messages.php';
require_once 'items.php';

$flag = false;

$item_id = 0;
$item_name = '';
$in_cart = 0;
$amount = 0;
$price = 0;
$totalPrice = 0;
$totalPriceMain = 0;
$totalPriceSeasonal = 0;
$totalPriceArr = [];
$sum = 0;
$stock = 0;
$dataMain = [];
$dataSeasonal = [];

require_once 'conf/connect.php';

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $flag = true;
} else {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
    if ($_POST['action'] === 'register') {
      require_once 'register.php';
    }
    if ($_POST['action'] === 'login') {
      require_once 'login.php';
    }
    if (isset($_POST['flag'])) {
      if (!$flag) {
        $err_msg[] = '続けるにはログインしてください！';
      }
    }
    if ($_POST['action'] === 'main' || $_POST['action'] === 'seasonal') {
      $item_id = $_POST['item_id'];
      $item_name = $_POST['item_name'];
      $in_cart = $_POST['in_cart'];
      $price = $_POST['price'];
      $item_id = input_validate($item_id);
      $item_name = input_validate($item_name);
      $in_cart = input_validate($in_cart);
      $price = input_validate($price);
    }
    if ($_POST['action'] === 'main') {
      try {
        // Select from table ec_cart
        $sql = 'SELECT amount, in_cart FROM ec_cart WHERE item_id = :item_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':item_id', $item_id);
        $stmt->execute();
        $rowAmount = $stmt->fetch(PDO::FETCH_ASSOC);
        $amount = $rowAmount['amount'] - $in_cart;
        $in_cart = $rowAmount['in_cart'] + $in_cart;
        // Update table ec_cart
        $sql = 'UPDATE ec_cart SET user_id = :user_id, amount = :amount, in_cart = :in_cart WHERE item_id = :item_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':in_cart', $in_cart);
        $stmt->bindValue(':item_id', $item_id);
        $stmt->execute();
      } catch (PDOException $e) {
        throw $e;
      }
    }
    if ($_POST['action'] === 'seasonal') {
      try {
        // Select from table ec_item_master
        $sql = 'SELECT stock FROM ec_item_master WHERE item_id = :item_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':item_id', $item_id); 
        $stmt->execute();
        $rowAmount = $stmt->fetch(PDO::FETCH_ASSOC);
        $amount = $rowAmount['stock'] - $in_cart;
        // Update table ec_item_master
        $sql = 'UPDATE ec_item_master SET stock = :amount WHERE item_id = :item_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':item_id', $item_id); 
        $stmt->execute();
        // Update table ec_cart
        $sql = 'UPDATE ec_cart SET user_id = :user_id, amount = :amount, in_cart = :in_cart WHERE item_id = :item_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':in_cart', $in_cart);
        $stmt->bindValue(':item_id', $item_id); 
        $stmt->execute();
      } catch (PDOException $e) {
        throw $e;
      }
    }
  }
  if (isset($_POST['changeMain']) || isset($_POST['changeSeasonal'])) {
    if (isset($_POST['delete']) && isset($_POST['in_cart_edit'])) {
      $item_id = $_POST['item_id'];
      $in_cart = $_POST['in_cart_edit'];
      $item_id = input_validate($item_id);
      $in_cart = input_validate($in_cart);
      if ($_POST['delete']) {
        // Begin transaction
        $dbh->beginTransaction();
        try {
          // Delete from table ec_cart
          $sql = 'DELETE FROM ec_cart WHERE item_id = :item_id';
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':item_id', $item_id);
          $stmt->execute();
          // Delete from table ec_item_master
          $sql = 'DELETE FROM ec_item_master WHERE item_id = :item_id';
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':item_id', $item_id);
          $stmt->execute();
          // Commit
          $dbh->commit();
        } catch (PDOException $e) {
          // Rollback
          $dbh->rollBack();
          throw $e;
        }
      }
      try {
        // Select from table ec_cart
        $sql = 'SELECT amount FROM ec_cart WHERE item_id = :item_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':item_id', $item_id);
        $stmt->execute();
        $rowAmount = $stmt->fetch(PDO::FETCH_ASSOC);
        $amount = $rowAmount['amount'] - $in_cart;
        // Update table ec_cart
        $sql = 'UPDATE ec_cart SET amount = :amount, in_cart = :in_cart WHERE item_id = :item_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':in_cart', $in_cart);
        $stmt->bindValue(':item_id', $item_id);
        $stmt->execute();
        // Update table ec_item_master
        $sql = 'UPDATE ec_item_master SET stock = :amount WHERE item_id = :item_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':item_id', $item_id);
        $stmt->execute();
        if ($_POST['delete']) {
          $scs_msg[] = "ご指定の商品を削除しました！";
        } else {
          $scs_msg[] = "ご指定の商品の数量を変更しました！";
        }
      } catch (PDOException $e) {
        throw $e;
      }
    }
  }
}

try {
  // Select from table ec_cart
  $sql = 'SELECT user_id, item_id, item_name, in_cart FROM ec_cart WHERE user_id = :user_id AND item_id <= 6';
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $rowsMain = $stmt->fetchAll();
  foreach ($rowsMain as $i) {
    $dataMain[] = $i;
  }
} catch (PDOException $e) {
  throw $e;
}
try {
  // Select from inner-join table
  $sql = 'SELECT user_id, ec_cart.item_id, ec_cart.item_name, in_cart, price 
          FROM ec_cart 
          INNER JOIN ec_item_master 
          ON ec_cart.item_id = ec_item_master.item_id 
          WHERE user_id = :user_id';
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $rowsSeasonal = $stmt->fetchAll();
  foreach ($rowsSeasonal as $j) {
    $dataSeasonal[] = $j;
  }
} catch (PDOException $e) {
  throw $e;
}

?>
<!doctype html>
<html class="no-js" lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php echo $title_confirm; ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Montserrat:300,400,700" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/styles.css">
  <script src="https://use.fontawesome.com/31b052511e.js"></script>
</head>
<body class="sub confirm is-fadeIn">
  <div class="wrap">
    <?php foreach ($err_msg as $value): ?>
      <p class="label label-error"><?php print $value; ?><span class="label__close pull-right">&times;</span></p>
    <?php endforeach; ?>
    <?php foreach ($scs_msg as $value): ?>
      <p class="label label-success"><?php print $value; ?><span class="label__close pull-right">&times;</span></p>
    <?php endforeach; ?>
    <?php include 'inc/header.php'; ?>
    <?php include 'inc/login.php'; ?>
    <main class="main">
      <div class="container--innner">
        <article class="article article--confirm confirm">
          <h2 class="article__h2">Your order</h2>
          <h3 class="article__h3">Main products</h3>
          <?php foreach ($dataMain as $val): ?>
          <form method="post">
            <div class="confirm__items">
              <input type="hidden" name="item_id" value="<?php echo $val['item_id']; ?>">
              <span class="confirm__name"><?php echo $val['item_name']; ?></span>
              &nbsp;&times;&nbsp;
              <span class="confirm__cart"><input type="number" name="in_cart_edit" value="<?php echo $val['in_cart']; ?>" min="1" max="99"></span>
              &nbsp;&#61;&nbsp;
              <span class="confirm__price"><?php echo $totalPriceMain = $items[$val['item_id'] - 1]->getPrice() * $val['in_cart']; ?>円</span>
              <span class="confirm__select--wrap">
                <select name="delete" class="confirm__select">
                  <option value="0">残す</option>
                  <option value="1">削除</option>
                </select>
              </span>
              <span class="confirm__change">
                <input type="submit" name="changeMain" value="変更する" class="confirm__btn"/>
              </span>
            </div>
          </form>
          <?php $totalPriceArr[] = $totalPriceMain; ?>
          <?php endforeach; ?>
          <h3 class="article__h3">Seasonal products</h3>
          <?php foreach ($dataSeasonal as $val): ?>
          <form method="post">
            <div class="confirm__items">
              <input type="hidden" name="item_id" value="<?php echo $val['item_id']; ?>">
              <span class="confirm__name"><?php echo $val['item_name']; ?></span>
              &nbsp;&times;&nbsp;
              <span class="confirm__cart"><input type="number" name="in_cart_edit" value="<?php echo $val['in_cart']; ?>" min="1" max="99"></span>
              &nbsp;&#61;&nbsp;
              <span class="confirm__price"><?php echo $totalPriceSeasonal = $val['price'] * $val['in_cart']; ?>円</span>
              <span class="confirm__select--wrap">
                <select name="delete" class="confirm__select">
                  <option value="0">残す</option>
                  <option value="1">削除</option>
                </select>
              </span>
              <span class="confirm__change">
                <input type="submit" name="changeSeasonal" value="変更する" class="confirm__btn"/>
              </span>
            </div>
          </form>
          <?php $totalPriceArr[] = $totalPriceSeasonal; ?>
          <?php endforeach; ?>
          <hr class="confirm__hr">
          <div class="confirm__total">
            <?php for ($k = 0; $k < count($totalPriceArr); $k++): ?>
              <?php $sum += $totalPriceArr[$k]; ?>
            <?php endfor; ?>
            <strong class="confirm__sum"><?php echo $sum; ?>円</strong>
            <span class="btn btn--sm btn--awesome">購入手続きへ</span>
          </div>
        </article>
      </div>
    </main>
    <?php include 'inc/footer.php'; ?>
  </div>
  <script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="dist/js/vendor/jquery-2.2.4.min.js"><\/script>')</script>
  <script src="dist/js/scripts.js"></script>
</body>
</html>