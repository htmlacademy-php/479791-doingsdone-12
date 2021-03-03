<div class="content">

  <section class="content__side">
    <p class="content__side-info">Если у вас нет аккаунта, зарегистрируйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="/register.php">Зарегистрироваться</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Вход на сайт</h2>

    <form class="form" action="/auth.php" method="post" autocomplete="off">
      <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['email'])) : ?>
              'form__input--error'<?php endif;?>" type="text" name="email" id="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="Введите e-mail">

        <p class="form__message"><?=$errors['email'] ?? '' ?></p>
      </div>

      <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['password'])) : ?>
              'form__input--error'<?php endif;?>" type="password" name="password" id="password" value="<?= isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" placeholder="Введите пароль">
          <p class="form__message"><?=$errors['password'] ?? '' ?></p>
        </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="submit" value="Войти">
      </div>
    </form>

  </main>

</div>