<div class="login" style="display: none">
  <p class="login__close"><a href="javascript: void(0);">&times;</a></p>
  <div class="login__inner">
    <div class="login__logo logo">
      <h1 class="logo__h1">Chai Club</h1>
      <p class="logo__p">chai with pride and joy</p>
    </div>
    <!-- Form login -->
    <form method="post" class="form form--login" id="form-login">
      <div class="form-item">
        <p class="formLabel">Username</p>
        <input type="text" name="username" id="username-register" class="form-style" autocomplete="off"/>
      </div>
      <div class="form-item">
        <p class="formLabel">Password</p>
        <input type="password" name="password" id="password-register" class="form-style" />
      </div>
      <div class="form-item form-item--submit">
        <p><a href="javascript: void(0);" class="js-register">登録する</a></p>
        <input type="submit" value="ログインする" class="btn btn--awesome">
      </div>
      <input type="hidden" name="action" value="login">
    </form>
    <!-- Form register -->
    <form method="post" class="form form--register" id="form-register" style="display: none;">
      <div class="form-item">
        <p class="formLabel">Username</p>
        <input type="text" name="username" id="username-login" class="form-style" autocomplete="off"/>
      </div>
      <div class="form-item">
        <p class="formLabel">Password</p>
        <input type="password" name="password" id="password-login" class="form-style" />
      </div>
      <div class="form-item form-item--submit">
        <p><a href="javascript: void(0);" class="js-login">ログインする</a></p>
        <input type="submit" value="登録する" class="btn btn--awesome">
      </div>
      <input type="hidden" name="action" value="register">
    </form>
  </div>
</div>
