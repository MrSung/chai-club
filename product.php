<?php

session_start();

require_once 'conf/vars.php';
require_once 'conf/messages.php';
require_once 'items.php';

$flag = false;

require_once 'conf/connect.php';

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $flag = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
    if ($_POST['action'] === 'register') {
      require_once 'register.php';
    }
    if ($_POST['action'] === 'login') {
      require_once 'login.php';
    }
  }
  if (isset($_POST['flag'])) {
    if (!$flag) {
      $err_msg[] = '続けるにはログインしてください！';
    }
  }
}

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
  $index_match = array_search($item_id, $itemIds);
  $item_match = $items[$index_match];
} else {
  header('Location: products.php');
  exit;
}

?>
<!doctype html>
<html class="no-js" lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php echo $title_product; ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Montserrat:300,400,700" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/styles.css">
  <script src="https://use.fontawesome.com/31b052511e.js"></script>
</head>
<body class="sub product is-fadeIn">
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
      <div class="container--inner">
        <article class="article article--chai chai">
          <h2 class="article__h2">Product detail</h2>
          <div class="chai__photo">
            <img src="<?php echo $item_match->getImage(); ?>" alt="<?php echo $item_match->getName(); ?>">
          </div>
          <div class="chai__desc">
            <h3 class="chai__h3"><?php echo $item_match->getName(); ?></h3>
            <p class="chai__price">&yen;<?php echo $item_match->getPrice(); ?></p>
            <p class="chai__txt"><?php echo $item_match->getDesc(); ?>
            <form method="post" <?php echo ($flag) ? 'action="confirm.php"' : ''; ?> id="form-cart">
              <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
              <input type="hidden" name="item_name" value="<?php echo $item_match->getName(); ?>">
              <div class="chai__quantity quantity">
                <input type="number" name="in_cart" min="1" max="9" step="1" value="1">&nbsp;個
              </div>
              <input type="hidden" name="price" value="<?php echo $item_match->getPrice(); ?>">
              <input type="hidden" name="action" value="main">
              <p class="chai__add">
                <input type="hidden" name="flag" value="<?php ($flag) ? 1 : 0 ; ?>">
                <input type="submit" value="カートに入れる" class="btn btn--peridot">
              </p>
            </form>
          </div>
        </article>
      </div>
    </main>
    <?php include 'inc/footer.php'; ?>
  </div>
  <script
    src="http://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="dist/js/vendor/jquery-2.2.4.min.js"><\/script>')</script>
  <script src="dist/js/scripts.js"></script>
</body>
</html>