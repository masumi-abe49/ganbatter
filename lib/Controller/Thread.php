<?php
namespace ganbatter\Controller;
class Thread extends \ganbatter\Controller {
  public function run() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      if ($_POST['type'] === 'createthread') {
        $this->createThread();
      } elseif($_POST['type'] === 'createcomment') {
        $this->createComment();
      }
    }
  }
  private function createThread() {
    try {
      $this->validate();
    } catch (\ganbatter\Exception\EmptyPost $e) {
        $this->setErrors('create_thread', $e->getMessage());
    } catch (\ganbatter\Exception\CharLength $e) {
        $this->setErrors('create_thread', $e->getMessage());
    }
    $this->setValues('thread', $_POST['thread']);
    if ($this->hasError()) {
      return;
    } else {
      $threadModel = new \ganbatter\Model\Thread();
      $threadModel->createThread([
        'ganbatta_main' => $_POST['thread'],
        'user_id' => $_SESSION['me']->id
      ]);
      header('Location: '. SITE_URL . '/ganbatta_all.php');
      exit();
    }
  }

  // コメント作成メソッド
  private function createComment() {
    try {
        $this->validate();
      } catch (\ganbatter\Exception\EmptyPost $e) {
          $this->setErrors('comment', $e->getMessage());
      } catch (\ganbatter\Exception\CharLength $e) {
          $this->setErrors('comment', $e->getMessage());
      }
      $this->setValues('content', $_POST['content']);
      if ($this->hasError()) {
        return;
      } else {
        $threadModel = new \ganbatter\Model\Thread();
        $threadModel->createComment([
          'thread_id' => $_POST['thread_id'],
          'user_id' => $_SESSION['me']->id,
          'content' => $_POST['content']
        ]);
      }
      header('Location: '. SITE_URL . '/ganbatta_disp.php?thread_id=' . $_POST['thread_id']);
      exit();
  }


  private function validate() {
    if ($_POST['type'] === 'createthread' || $_POST['type'] === 'createcomment') {
      if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        echo "不正なトークンです！";
      exit();
      }
      if (!isset($_POST['thread'])) {
        echo '不正な投稿です';
      exit();
      }
      if ($_POST['thread'] === '') {
        throw new \ganbatter\Exception\EmptyPost("あなたの頑張った！が入力されていませんよ…！");
      }
      if (mb_strlen($_POST['thread']) > 141) {
        throw new \ganbatter\Exception\CharLength("ちょっと頑張った！内容が長過ぎるようです…！");
      }
      if ($_POST['comment'] === '') {
        throw new \ganbatter\Exception\EmptyPost("応援のコメントが入力されていませんよ…！");
      }
      if (mb_strlen($_POST['comment']) > 141) {
        throw new \ganbatter\Exception\CharLength("ちょっと応援コメントが長過ぎるようです…！");
      }
    }
  }
}