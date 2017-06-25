<?php

session_start();

require_once 'conf/vars.php';
require_once 'conf/messages.php';
require_once 'items.php';

$flag = false;

$path_to_img = './images/';
$data = [];

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
}

try {
  if (count($err_msg) === 0) {
    try {
      $sql = 'SELECT item_id, img, item_name, price, status, stock FROM ec_item_master WHERE status = 1';
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      foreach ($rows as $row) {
        $data[] = $row;
      }
      if (preg_match('/-[0-9]{0,10}/', $data['stock'])) {
        $err_msg[] = '売り切れ';
      }
    } catch (PDOException $e) {
      throw $e;
    }
  }
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'DBエラー：' . $e->getMessage();
}

?>
<!doctype html>
<html class="no-js" lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php echo $title_products; ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Montserrat:300,400,700" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/styles.css">
  <script src="https://use.fontawesome.com/31b052511e.js"></script>
</head>
<body class="sub products is-fadeIn">
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
      <div class="container">
        <article class="article article--inventories">
          <h2 class="article__h2">main products</h2>
          <?php foreach ($items as $item): ?>
            <figure class="article__inventory inventory">
              <form action="product.php">
                <img src="<?php echo $item->getImage(); ?>" alt="<?php echo $item->getName(); ?>" class="inventory__img">
                <figcaption class="inventory__figcaption">
                  <h3 class="inventory__h3"><?php echo $item->getName(); ?></h3>
                  <p class="inventory__desc"><?php echo $item->getDesc(); ?></p>
                  <input type="hidden" name="item_id" value="<?php echo $item->getItemId(); ?>">
                  <span class="inventory__more"><input type="submit" value="詳しく見る"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                  <span class="inventory__price"><?php echo $item->getPrice(); ?>円</span>
                </figcaption>
              </form>
            </figure>
          <?php endforeach; ?>
        </article>
        <article class="article article--inventories">
          <h2 class="article__h2">seasonal products</h2>
          <?php foreach ($data as $val): ?>
            <figure class="article__inventory inventory">
              <form action="confirm.php" method="post">
                <img src="<?php echo $path_to_img . $val['img']; ?>" alt="<?php echo $val['item_name']; ?>" class="inventory__img">
                <figcaption class="inventory__figcaption">
                  <h3 class="inventory__h3"><?php echo $val['item_name']; ?></h3>
                  <p class="inventory__desc"></p>
                  <input type="hidden" name="item_id" value="<?php echo $val['item_id']; ?>">
                  <input type="hidden" name="item_name" value="<?php echo $val['item_name']; ?>"/>
                  <input type="hidden" name="price" value="<?php echo $val['price']; ?>"/>
                  <span class="inventory__cart"><input type="submit" value="カートに入れる" class="btn btn--sm btn--peridot"></span>
                  <span class="inventory__amount"><input type="number" name="in_cart" min="1" max="99" step="1" value="1" />&nbsp;個</span>
                  <span class="inventory__price"><?php echo $val['price']; ?>円</span>
                </figcaption>
                <input type="hidden" name="action" value="seasonal">
              </form>
            </figure>
          <?php endforeach; ?>
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
