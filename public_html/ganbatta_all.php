<?php
require_once(__DIR__ .'/header.php');
$threadMod = new ganbatter\Model\Thread();
$threads = $threadMod->getThreadAll();
// echo date('Y-m-d H:i:s');
?>
<h1 class="page__ttl">頑張った！一覧</h1>
<ul class="thread">
  <?php foreach($threads as $thread): ?>
    <li class="thread__item">
      <div class="thread__head">
        <!-- <h2 class="thread__ttl">
          h($thread->ganbatta_main);
        </h2> 頑張った！一覧のスレッドにタイトルは不要。本文のみでOK -->
        <span class="thread__item__name">名前：<?= h($thread->username); ?></span>
        <span class="thread__item__date">頑張った！投稿日時：<?= h($thread->created); ?></span>
        <div><i class="fas fa-star"></i></div> <!-- いいぞ！の部分 -->
      </div>
      <ul class="thread__body">
        <li class="thread__item__content">
          <?= h($thread->ganbatta_main); ?>
        </li>
        <?php
          $comments = $threadMod->getComment($thread->id);
          foreach($comments as $comment):
        ?>
        <li class="comment__item">
          <div class="comment__item__head">
            <span class="comment__item__num"><?= h($comment->comment_num); ?></span>
            <span class="comment__item__name">名前：<?= h($comment->username); ?></span>
            <span class="comment__item__date">投稿日時：<?= h($comment->created); ?></span>
          </div>
          <p class="comment__item__content"><?= h($comment->content); ?></p>
        <?php endforeach; ?>
        </li>
      </ul>
      <div class="operation">
        <a href="<?= SITE_URL; ?>/ganbatta_disp.php?thread_id=<?= $thread->id; ?>">コメント書き込み＆すべて読む(<?= h($threadMod->getCommentCount($thread->id)); ?>)</a>
      </div>
    </li>
  <?php endforeach; ?>
</ul>
<?php
require_once(__DIR__ .'/footer.php');
?>