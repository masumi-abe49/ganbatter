<?php
require_once(__DIR__ .'/header.php');
$threadMod = new ganbatter\Model\Thread();
$threads = $threadMod->getThreadAll();
// echo date('Y-m-d H:i:s');
?>
<h1 class="page__ttl">頑張った！一覧</h1>
<!-- ↓いいぞ！（thread）検索　☆method属性はget☆ -->
<form action="ganbatta_search.php" method="get" class="form-group form-search">
  <div class="form-group">
    <input type="text" name="keyword" placeholder="頑張った！を検索">
  </div>
  <div class="form-group">
    <input type="submit" value="検索" class="btn btn-primary">
    <input type="hidden" name="type" value="searchthread">
  </div>
</form>
<!-- ↑いいぞ！（thread）検索 -->
<ul class="thread">
  <?php foreach($threads as $thread): ?>
    <li class="thread__item" data-threadid="<?= $thread->t_id; ?>">
      <div class="thread__head">
        <!-- <h2 class="thread__ttl">
          h($thread->ganbatta_main);
        </h2> 頑張った！一覧のスレッドにタイトルは不要。本文のみでOK -->
        <span class="thread__item__name">名前：<?= h($thread->username); ?></span>
        <span class="thread__item__date">頑張った！投稿日時：<?= h($thread->created); ?></span>
        <div class="fav__btn<?php if(isset($thread->l_id)) { echo ' active';} ?>"><i class="fas fa-star"></i></div> <!-- いいぞ！の部分 if文の中にあるactiveについては、classのfav__btnの後ろに記載されるためしっかりと＜ ＞（半角スペース）を入れてあげないといいぞ！のbtnを押したときにうまく反応されない。 -->
      </div>
      <ul class="thread__body">
        <li class="thread__item__content">
          <?= h($thread->ganbatta_main); ?>
        </li>
        <?php
          $comments = $threadMod->getComment($thread->t_id);
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
        <a href="<?= SITE_URL; ?>/ganbatta_disp.php?thread_id=<?= $thread->t_id; ?>">コメント書き込み＆すべて読む(<?= h($threadMod->getCommentCount($thread->t_id)); ?>)</a>
      </div>
    </li>
  <?php endforeach; ?>
</ul><!-- thread -->
<?php
require_once(__DIR__ .'/footer.php');
?>