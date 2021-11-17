<?php
require_once(__DIR__ .'/header.php');
$threadMod = new ganbatter\Model\Thread();
$threads = $threadMod->getThreadAll();
?>
<h1 class="page__ttl">頑張った！一覧</h1>
<ul class="thread">
  <?php foreach($threads as $thread): ?>
    <li class="thread__item">
      <div class="thread__head">
        <h2 class="thread__ttl">
          <?= h($thread->ganbatta_main); ?>
        </h2>
        <div><i class="fas fa-star"></i></div> <!-- いいぞ！の部分 -->
      </div>
      <ul class="thread__body">
        <?php
          $comments = $threadMod->getComment($thread->id);
          foreach($comments as $comment):
        ?>
        <li class="comment__item">
          <div class="comment__item__head">
            <span class="comment__item__num"><?= h($comment->comment__num); ?></span>
            <span class="comment__item__name">名前：<?= h($comment->username); ?></span>
            <span class="comment__item__date">投稿日時：<?= h($comment->created); ?></span>
          </div>
          <p class="comment__item__content"><?= h($comment->content); ?></p>
        <?php endforeach; ?>
        </li>
      </ul>
      <div class="operation">
        <a href="">コメント書き込み＆すべて読む</a>
        <p class="thread__date">頑張った！作成日時：<?= h($thread->created); ?></p>
      </div>
    </li>
  <?php endforeach; ?>
</ul>
<?php
require_once(__DIR__ .'/footer.php');
?>