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
    } elseif($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type'])) {
    $threadData = $this->searchThread();
    return $threadData;
    }
  }

  // 頑張った！作成メソッド
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
      // 応援コメント書き込み後は、その頑張った！詳細の画面に遷移（コメントが追加された状態）する。220130
  }

  // 頑張った！検索メソッド
  public function searchThread(){
    try {
      $this->validateSearch();
    } catch (\ganbatter\Exception\EmptyPost $e) {
      $this->setErrors('keyword', $e->getMessage());
    } catch (\ganbatter\Exception\CharLength $e) {
      $this->setErrors('keyword', $e->getMessage());
    }

    $keyword = $_GET['keyword'];
    $this->setValues('keyword', $keyword);
    if ($this->hasError()) {
      return;
    } else {
      $threadModel = new \ganbatter\Model\Thread();
      $threadData = $threadModel->searchThread($keyword);
      return $threadData;
    }
  }

  private function validate() {
    if ($_POST['type'] === 'createthread' || $_POST['type'] === 'createcomment') {
      if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        echo "不正なトークンです！";
      exit();
      }
      if (!isset($_POST['thread']) && !isset($_POST['content'])) {
        echo "不正な投稿です";
      exit();
      // if文の内容について、頑張った！！詳細からコメント書き込みする際に&&にしないと不正な投稿ですが表示されてしまう。スレッド分とコメント分でvalidetionを分けた方がいいかも。
      }
      if (isset($_POST['thread']) && $_POST['thread'] === '') {
        throw new \ganbatter\Exception\EmptyPost("あなたの頑張った！が入力されていませんよ…！");
        // if文の内容について、頑張った！！詳細からコメント書き込みする際に&&にしないと不正な投稿ですが表示されてしまう。スレッド分とコメント分でvalidetionを分けた方がいいかも。
      }
      if (isset($_POST['thread']) && mb_strlen($_POST['thread']) > 140) {
        throw new \ganbatter\Exception\CharLength("ちょっと頑張った！内容が長過ぎるようです…！");
        // if文の内容について、頑張った！！詳細からコメント書き込みする際に&&にしないと不正な投稿ですが表示されてしまう。スレッド分とコメント分でvalidetionを分けた方がいいかも。
      }
      if ($_POST['content'] === '') {
        throw new \ganbatter\Exception\EmptyPost("応援のコメントが入力されていませんよ…！");
      }
      if (mb_strlen($_POST['content']) > 140) {
        throw new \ganbatter\Exception\CharLength("ちょっと応援コメントが長過ぎるようです…！");
      }
    }
  }

  // 頑張った！検索用のバリデーション
  private function validateSearch() {
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type'])) {
      if ($_GET['keyword'] === '') {
        throw new \ganbatter\Exception\EmptyPost("検索キーワードが入力されていません…！");
      }
      if (mb_strlen($_GET['keyword']) > 20) {
        throw new \ganbatter\Exception\CharLength("キーワードが長すぎます…！");
      }
    }
  }
}