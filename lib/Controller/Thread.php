<?php
namespace ganbatter\Controller;
class Thread extends \ganbatter\Controller {
  public function run() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      if ($_POST['type'] === 'createthread') {
        $this->createThread();
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
  private function validate() {
    if ($_POST['type'] === 'createthread') {
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
    }
  }
}