<?php
require_once(__DIR__ .'/header.php');
$app = new ganbatter\Controller\Thread();
$app->run();
?>
<h1 class="page__ttl">頑張った！作成</h1>
<form action="" method="post" class="form-group new_thread" id="new_thread">
  <div class="form-group">
    <label>新規作成</label>
    <textarea type="text" name="thread" class="form-control" placeholder="今日、何を頑張った？"><?= isset($app->getValues()->thread) ? h($app->getValues()->thread) : ''; ?></textarea><!-- $threadModelのganbatta_mainに渡している部分。-->
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    <input type="hidden" name="type" value="createthread">
    <p class="err"><?= h($app->getErrors('create_thread')); ?></p>
  </div>
  <div class="form-group btn btn-primary" onclick="document.getElementById('new_thread').submit();">作成</div>
</form>
<?php
require_once(__DIR__ .'/footer.php');
?>