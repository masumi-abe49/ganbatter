<?php
require_once(__DIR__ .'/../config/config.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Cache-Control" content="no-cache">
  <title>ganbatter</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link href="https://fonts.googleapis.com/css?family=Charm|M+PLUS+Rounded+1c&amp;subset=latin-ext,thai,vietnamese" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/8bc1904d08.js"></script>
  <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
<header class="sticky-top header">
<div class="header__inner">
  <nav>
    <ul>
      <li><a href="<?= SITE_URL; ?>/">ホーム</a></li>
      <?php
      if(isset($_SESSION['me'])): ?>
      <li><a href="<?= SITE_URL; ?>/ganbatta_create.php">作成</a></li>
      <li><a href="<?= SITE_URL; ?>/ganbatta_all.php">頑張った！一覧</a></li>
      <li><a href="<?= SITE_URL; ?>/ganbatta_likes.php">いいぞ！一覧</a></li>
      <?php // if ($_SESSION['me']->authority === '99'): ?>
      <!-- <li><a href="<?= SITE_URL; ?>/user_manage.php">管理者ページ</a></li> -->
      <?php // endif; ?>
      <?php else: ?>
        <li class="user-btn"><a href="<?= SITE_URL; ?>/login.php">ログイン</a></li>
        <li><a href="<?= SITE_URL; ?>/signup.php">ユーザー登録</a></li>
      <?php endif; ?>
    </ul>
  </nav>
  <div class="header-r">
    <?php
      if(isset($_SESSION['me'])) { ?>
      <div class="prof-show" data-me="<?= h($_SESSION['me']->id); ?>">
        <a href="<?= SITE_URL; ?>/mypage.php">
          <span class="name"><?= h($_SESSION['me']->username); ?></span>
          <span class="image">
            <?php if(isset($_SESSION['me']->image)): ?>
              <img src="<?= SITE_URL; ?>/gazou/<?= h($_SESSION['me']->image); ?>" alt="">
            <?php else: ?>
              <img src="<?= SITE_URL; ?>/asset/img/noimage.png" alt="">
            <?php endif; ?>
          </span>
        </a>
      </div>
      <form action="logout.php" method="post" id="logout" class="user-btn">
        <input type="submit" value="ログアウト">
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      </form>
    <?php  } ?>
  </div>
</div>
</header>
<div class="wrapper">

