<?php

session_start();

require_once 'conf/vars.php';
require_once 'conf/messages.php';
require_once 'items.php';

$flag = false;

// $amount_session = 0;

require_once 'conf/connect.php';

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $flag = true;
}
// if (isset($_SESSION['in_cart'])) {
//   $amount_session = $_SESSION['in_cart'];
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
    if ($_POST['action'] === 'register') {
      require_once 'register.php';
    }
    if ($_POST['action'] === 'login') {
      require_once 'login.php';
    }
//    if ($_POST['action'] === 'logout') {
//      session_abort();
//      $flag = false;
//    }
  }
}

?>
<!doctype html>
<html class="no-js" lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php echo $title_about; ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Montserrat:300,400,700" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/styles.css">
  <script src="https://use.fontawesome.com/31b052511e.js"></script>
</head>
<body class="sub about is-fadeIn">
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
        <article class="article article--about about">
          <h2 class="article__h2">ABOUT US</h2>
          <div class="about__photo">
            <img src="images/img_about.jpg" alt="Man making chai tea"></img>
          </div>
          <div class="about__desc">
            <blockquote cite="http://portal.nifty.com/koneta05/11/09/02/">
              <p>チャイとは簡単に言ってしまえばインド式のミルクティ。インドでは日本で言うコーヒーや緑茶のようにメジャーな飲み物で、紅茶とミルクとシナモン等の香辛料を煮出して飲むものである。</p>
              <p>ミルクが多くてやや甘いが、煮出しているので紅茶や香辛料の成分が濃厚に溶け出し、普通の紅茶に比べ味に独特のコクがあるのが特長だ。</p>
              <p>実はチャイの誕生には複雑な事情がある。<br>植民地時代のインドは紅茶を生産していたが、立派な紅茶葉はすべてイギリスに持って行かれてしまっていた。インドの庶民に残るのは商品にならないダストティーと呼ばれる埃のように細かい紅茶の葉だけ。そこで、インド人はこの捨てるしかないと言われたダストティをどうにかおいしく飲む方法はないかと考えた。<br>その結果紅茶葉をミルクと一緒に煮出し香辛料を加える方法が考えられ、チャイが誕生するのだ。</p>
            </blockquote>
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
  <script src="dist/js/scripts.min.js"></script>
</body>
</html>