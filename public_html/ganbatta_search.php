<?php
require_once(__DIR__ .'/header.php');
$threadCon = new ganbatter\Controller\Thread();
$threadMod = new ganbatter\Model\Thread();
$threads = $threadCon->run();
?>
<h1 class="page__ttl">頑張った！検索</h1>
<form action="" method="get" class="form-group form-search">
  <div class="form-group">
    <input type="text" name="keyword" value="<?= isset($threadCon->getValues()->keyword) ? h($threadCon->getValues()->keyword): ''; ?>" placeholder="頑張った！検索">
    <p class="err"><?= h($threadCon->getErrors('keyword')); ?></p>
  </div>
  <div class="form-group">
    <input type="submit" value="検索" class="btn btn-primary">
    <input type="hidden" name="type" value="searchthread">
  </div>
</form>
<?php $threads != '' ? $con = count($threads) : $con = ''; // 三項演算子（if..else文と異なり、演算子のため式を返す） 条件式 ? 式1 : 式2  →条件式を評価し、TRUEであれば式1、FALSEであれば式2を返す。FALSEだった場合の$conに対しての代入を記載しないと24行目のif($con > 0)の部分でNotice:Undefined variable:con in... エラーが発生する。 ?>
<?php if (($threadCon->getErrors('keyword'))): ?>
<?php else : ?>
<div>キーワード：<?= $_GET['keyword']; ?>　　該当件数：<?= $con; ?>件</div>
<?php endif; ?>
<ul class="thread">
<?php if ($con > 0): ?>
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
      </ul><!-- thread__body -->
      <div class="operation">
        <a href="<?= SITE_URL; ?>/ganbatta_disp.php?thread_id=<?= $thread->t_id; ?>">コメント書き込み＆すべて読む(<?= h($threadMod->getCommentCount($thread->t_id)); ?>)</a>
        <p class="thread_date">頑張った！作成日時：<?= h($thread->created); ?></p>
      </div>
    </li>
  <?php endforeach; ?>
<?php elseif($threads != ''): //elseだけにすると、検索ワード未入力時と検索ワードが長すぎたときに↓の文章が表示されてしまう。そうすると未入力時もワードが長すぎた時も検索をしていて、その結果スレッドが見つからなかったように感じるためelseifで条件を指定して表示さないようにしている。 ?>
  <p>キーワードに該当する頑張った！が見つかりませんでした…！</p>
<?php endif; ?>
</ul><!-- thread -->
<?php
require_once(__DIR__ .'/footer.php');
?>
