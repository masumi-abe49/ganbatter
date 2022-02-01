<?php
require_once(__DIR__ .'/header.php');
$app = new ganbatter\Controller\UserUpdate();
$app->run();
?>
<h1 class="page__ttl">マイページ</h1>
<div class="container">
  <form action="" method="post" id="userupdate" class="form mypage-form row" enctype="multipart/form-data">
    <div class="form-main">
      <div class="form-left col-md-4">
        <div class="form-group">
          <!-- <p class="err"></p> -->
          <div class="imgarea <?= isset($app->getValues()->image) ? '': 'noimage' ?>">
            <p>現在の画像</p>
            <div class="imgfile">
              <img src="<?= isset($app->getValues()->image) ? './gazou/'. h($app->getValues()->image) : './asset/img/noimage.png'; ?>" alt="">
            </div>
          </div>
          <label>
            <span class="file-btn btn btn-info">
              ユーザー画像編集
              <input type="file" name="image" class="form" style="display:none" accept="image/*">
            </span>
          </label>
          <label>
            <span class="file-btn btn btn-info">
              ユーザー画像削除
              <input class="form" type="submit" name="type" style="display:none" value="image-delete">
            </span>
          </label>
        </div>
      </div>
      <div class="form-right col-md-8">
        <div class="form-group">
          <label>メールアドレス</label>
          <input type="text" name="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email): ''; ?>" class="form-control">
          <p class="err"><?= h($app->getErrors('email')); ?></p>
        </div>
        <div class="form-group">
          <label>ユーザー名</label>
          <input type="text" name="username" value="<?= isset($app->getValues()->username) ? h($app->getValues()->username): ''; ?>" class="form-control">
          <p class="err"><?= h($app->getErrors('username')); ?></p>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('userupdate').submit();">更新</button> <!-- getElementById('userupdate')はformのidを指定している。 -->
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        <input type="hidden" name="old_image" value="<?= h($app->getValues()->image); ?>">
        <input type="hidden" name="type" value="userupdate">
        <p class="err"></p>
      </div>
    </div>
  </form>
  <input type="button" value="頑張った！新規作成画面へ" class="btn btn-primary btn-gangatta-create" onclick="location.href='ganbatta_create.php'">
  <!-- <ul class="thread">
    <li class="thread__item">
      <ul class="thread__body">
        <li class="comment__item">
          <div class="comment__item__head">
            <div class="comment__item__head__left">
              <span class="image imgarea"><img src="./gazou/cat.jpg" alt=""></span>
              <span class="comment__item__name">ユーザー名</span>
            </div>
            <div class="comment__item__head__right">
              <span class="comment__item__date">投稿日時　2020-010-05 16:03:01</span>
              <span class="fav__btn"><i class="fas fa-heart"></i></span>
            </div>
          </div>
          <p class="comment__item__content">コメントが入ります。コメントが入ります。コメントが入ります。コメントが入ります。コメントが入ります。コメントが入ります。コメントが入ります。コメントが入ります。コメントが入ります。コメントが入ります。</p>
        </li>
      </ul>
      <div class="operation">
        <a href="./ganbatta_disp.html">コメントを書く&amp;読む</a>
      </div>
    </li>
  </ul> -->
  <!-- thread -->
  <form class="user-delete" action="user_delete_confirm.html">
    <input type="submit" class="btn btn-default" value="退会する">
  </form>
</div><!--container -->
<?php
require_once(__DIR__ .'/footer.php');
?>