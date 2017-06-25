<header class="header">
  <div class="container">
    <div class="header__logo">
      <a href="./">
        <div class="logo">
          <h1 class="logo__h1">Chai Club</h1>
          <p class="logo__p">chai with pride and joy</p>
        </div>
      </a>
    </div>
    <?php if (!$flag): ?>
      <nav class="header__nav">
        <ul>
          <li><a href="products.php">Products</a></li>
          <li><a href="about.php">About Us</a></li>
        </ul>
      </nav>
      <div class="header__login">
        <span class="js-modal"><a href="javascript: void(0);">Login</a></span>
      </div>
    <?php else: ?>
      <nav class="header__nav header__nav--loggedin">
        <ul>
          <li><a href="products.php">Products</a></li>
          <li><a href="about.php">About Us</a></li>
          <!--<li class="favorite"><a href="#">Favorite</a></li>-->
        </ul>
      </nav>
      <div class="header__login header__login--loggedin js-loggedin">
        <span class="cart">
          <a href="confirm.php"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i></a>
        </span>
        <!--<span class="cart--content"><span></span></span>-->
        <div class="header__logout">
          <form method="post" action="logout.php">
            <input type="hidden" name="action" value="logout">
            <input type="submit" value="Logout">
          </form>
        </div>
      </div>
    <?php endif; ?>
    <div class="header__btn"><div class="burger"></div></div>
  </div>
</header>
