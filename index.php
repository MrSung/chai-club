<?php

session_start();

require_once 'conf/vars.php';
require_once 'conf/messages.php';
require_once 'items.php';

$flag = false;

$amount_session = 0;

require_once 'conf/connect.php';

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $flag = true;
}
if (isset($_SESSION['in_cart'])) {
  $amount_session = $_SESSION['in_cart'];
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


?>
<!doctype html>
<html class="no-js" lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php echo $title_index; ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Montserrat:300,400,700" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/styles.css">
  <script src="https://use.fontawesome.com/31b052511e.js"></script>
</head>
<body class="top is-fadeIn">
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
      <div class="hero">
        <div class="container">
          <h2 class="hero__message" data-aos="fade-up" data-aos-delay="100">チャイのある日常を<span class="ib">お届け</span></h2>
          <p class="hero__btn" data-aos="fade-up" data-aos-delay="150"><a href="products.php" class="btn btn--peridot">商品を見る</a></p>
        </div>
        <video class="hero__video" autoplay muted>
          <source src="videos/chaiclub.mp4" type="video/mp4"></source>
        </video>
      </div>
      <section class="section section--concept">
        <div class="container--inner">
          <h2 class="section__h2" data-aos="fade-up" data-aos-delay="200">Concept</h2>
          <p data-aos="fade-up" data-aos-delay="250">知っているようで知らないチャイの魅力。日本でのチャイは一般的に「煮出したミルクティー」として認知されていますが、チャイは国や地域によって様々な種類があります。本サイトでは、そんなチャイの知られざる魅力をお伝えします。ぜひご自身で手に取ってお試しになってください！</p>
        </div>
      </section>
      <section class="section section--products">
        <div class="container">
          <h2 class="section__h2" data-aos="fade-up">Products</h2>
          <article class="products--top">
            <div class="grid-parent">
              <?php for ($i = 0; $i <= 5; $i++): ?>
              <div class="grid-item productThumb--wrap" data-aos="fade-up">
                <a href="product.php?item_id=<?php echo $i + 1; ?>">
                  <div class="productThumb productThumb--<?php echo $i + 1; ?>" style="background-image: url(<?php echo $items[$i]->getImage(); ?>)">
                    <div class="productThumb__info">
                      <h2 class="productThumb__name"><?php echo $items[$i]->getName(); ?></h2>
                      <hr class="productThumb__hr" />
                      <p class="productThumb__desc"><?php echo $items[$i]->getDesc(); ?></p>
                    </div>
                    <div class="productThumb__layer productThumb__layer--grad"></div>
                    <div class="productThumb__layer productThumb__layer--color"></div>
                  </div>
                </a>
              </div>
              <?php endfor; ?>
            </div>
          </article>
        </div>
      </section>
      <section class="section section--newsletter">
        <div class="container">
          <h2 class="section__h2" data-aos="fade-up">News letter</h2>
          <div class="newsletter" data-aos="fade-up">
            <form method="post" class="form form--newsletter" id="form-newsletter">
              <input type="email" placeholder="johndoe@example.com">
              <input type="submit" placeholder="送信" class="btn btn--awesome">
            </form>
          </div>
        </div>
      </section>
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
