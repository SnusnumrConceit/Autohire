<?php
  if ($_COOKIE['Account'] ?? '') {
    foreach ($_COOKIE['Account'] as $key => $value) {
      $id = $key;
    }
    require_once 'Classes/User.php';
    $user = new User();
    $currentUser = $user->GetUser($id);
  }
?>
<header class="header">
    <nav class="navbar navbar-expand-sm bg-light">
        <ul class="navbar-nav">
        <?php                        
            if ($currentUser[0] ?? '') {
              print <<<LOGGED_PANEL
                <li class="nav-item"><a class="nav-link" href="cabinet.php?user={$currentUser[0]->id}">{$currentUser[0]->Login}</a></li>
                <li class="nav-item"><a class="nav-link" href="Index/Logout.php">Выйти</a></li>    
LOGGED_PANEL;
            } else {
              print <<<UNLOGGED_PANEL
                <li class="nav-item" data-toggle="modal" data-target="#loginModal"><a class="nav-link" href="#">Войти</a></li>
                <li class="nav-item" data-toggle="modal" data-target="#regModal"><a class="nav-link" href="#">Регистрация</a></li>
UNLOGGED_PANEL;
            }  
        ?>
    </nav>
        <div class="logo"><img src="сюда воткнёшь ссылку на лого" alt="АвтоПрокат"></div>
    
</header>

<!-- Модальное окно авторизации -->
<div class="modal fade" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Модальная шапка -->
      <div class="modal-header">
        <h4 class="modal-title">Авторизация</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Модальное тело -->
      <div class="modal-body">
        <form method="POST">
          <div class="form-group">
            <label for="auth-login">Логин</label>
            <input type="text" name="auth-login" id="auth-login" class="form-control">
          </div>
          <div class="form-group">
            <label for="auth-pass">Пароль</label>
            <input type="password" name="auth-pass" id="auth-pass" class="form-control">
          </div>
        </form>
      </div>

      <!-- Модальный футер -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-login">Войти</button>
      </div>

    </div>
  </div>
</div>

<!-- Модальное окно регистрации -->
<div class="modal fade" id="regModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Модальная шапка -->
      <div class="modal-header">
        <h4 class="modal-title">Регистрация</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Модальное тело -->
      <div class="modal-body">
        <form method="POST">
            <div class="form-group">
                <label for="user-login">Логин</label>
                <input type="text" name="user-login" id="user-login" value="" class="form-control">
            </div>
            <div class="form-group">
                <label for="user-password">Пароль</label>
                <input type="password" name="user-pass" id="user-password" value="" class="form-control">
            </div>
            <div class="form-group">
                <label for="user-confirm-password">Подтвердите пароль</label>
                <input type="password" name="user-confirm-password" id="user-confirm-password" value="" class="form-control">
            </div>
            <div class="form-group">
                        <label for="phone-number">Номер телефона</label>
                        <input type="text" class="form-control" id="phone-number">
            </div>
            <div class="form-group">
                <label for="user-last-name">Фамилия</label>
                <input type="text" name="user-last-name" id="user-last-name" value="" class="form-control">
            </div> 
            <div class="form-group">
                <label for="user-first-name">Имя</label>
                <input type="text" name="user-first-name" id="user-first-name" value="" class="form-control">
            </div>
            <div class="form-group">
                <label for="user-middle-name">Отчество</label>
                <input type="text" name="user-middle-name" id="user-middle-name" value="" class="form-control">
            </div>
            
        </form>
      </div>

      <!-- Модальный футер -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-registration">Зарегистрироваться</button>
      </div>

    </div>
  </div>
</div>