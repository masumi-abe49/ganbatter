<?php
require_once(__DIR__ .'/header.php');
$threadCon = new ganbatter\Controller\Thread();
$threadCon->run();
$thread_id = $_GET['thread_id'];
$threadMod = new ganbatter\Model\Thread();
$threadDisp = $threadMod->getThread($thread_id);
?>
<h1 class="page__ttl">頑張った！詳細</h1>
<div class="thread">
  <div class="thread__item">
    <div class="thread__head">
      <!-- <h2 class="thread__ttl">
        h($thread->ganbatta_main);
      </h2> 頑張った！一覧のスレッドにタイトルは不要。本文のみでOK -->
      <span class="thread__item__name">名前：<?= h($threadDisp->username); ?></span>
      <span class="thread__item__date">頑張った！投稿日時：<?= h($threadDisp->created); ?></span>
      <!-- <div><i class="fas fa-star"></i></div> --> <!-- いいぞ！の部分 -->
    </div>
    <ul class="thread__body">
      <li class="thread__item__content">
        <?= h($threadDisp->ganbatta_main); ?>
      </li>
      <?php
        $comments = $threadMod->getCommentAll($threadDisp->id);
        foreach($comments as $comment):
      ?>
      <li class="comment__item">
        <div class="comment__item__head">
          <span class="comment__item__name">名前：<?= h($comment->username); ?></span>
          <span class="comment__item__date">投稿日時：<?= h($comment->created); ?></span>
        </div>
        <p class="comment__item__content"><?= h($comment->content); ?></p>
      <?php endforeach; ?>
      </li>
    </ul>
    <form action="" method="post" class="form-group new_thread" id="new_thread">
      <div class="form-group">
        <label>コメント</label>
        <textarea type="text" name="comment" class="form-control" <?= isset($threadCon->getValues()->content) ? h($threadCon->getValues()->content) : ''; ?> placeholder="応援のコメントをどうぞ！！"></textarea>
        <p class="err"><?= h($threadCon->getErrors('comment')); ?></p>
      </div>
      <div class="form-group">
        <input type="submit" value="書き込み" class="btn btn-primary">
      </div>
      <input type="hidden" name="thread_id" value="<?= h($thread_id); ?>">
      <input type="hidden" name="type" value="createcomment">
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    </form>
  </div><!-- thread__item -->
</div><!-- thread -->
<?php require_once(__DIR__ .'/footer.php'); ?>