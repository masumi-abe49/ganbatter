<?php
namespace ganbatter\Controller;
// Controllerクラス継承
class Signup extends \ganbatter\Controller {
  public function run() {
    if ($this->isLoggedIn()) {
      header('Location: ' . SITE_URL);
      exit();
    }
    // POSTメソッドがリクエストされていればpostProcessメソッド実行
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->postProcess();
    }
  }
  protected function postProcess() {
    try {
      $this->validate();
    } catch (\ganbatter\Exception\InvalidEmail $e) {
        $this->setErrors('email', $e->getMessage());
    } catch (\ganbatter\Exception\InvalidName $e) {
        $this->setErrors('username', $e->getMessage());
    } catch (\ganbatter\Exception\InvalidPassword $e) {
        $this->setErrors('password', $e->getMessage());
    }
    $this->setValues('email', $_POST['email']);
    $this->setValues('username', $_POST['username']);
    if ($this->hasError()) {
      return;
    } else {
      try {
        $userModel = new \ganbatter\Model\User();
        $user = $userModel->create([
          'email' => $_POST['email'],
          'username' => $_POST['username'],
          'password' => $_POST['password']
        ]);
      }
      catch (\ganbatter\Exception\DuplicateEmail $e) {
        $this->setErrors('email', $e->getMessage());
        return;
      }
// ユーザー登録後、ログインを行う処理↓↓
      $userModel = new \ganbatter\Model\User();
      $user = $userModel->login([
        'email' => $_POST['email'],
        'password' => $_POST['password']
      ]);
      session_regenerate_id(true);
      $_SESSION['me'] = $user;
      header('Location: '. SITE_URL . '/ganbatta_all.php');
      exit();
    }
  }

  // バリデーションメソッド
  private function validate() {
    $validate = new \ganbatter\Controller\Validate();
    $validate->tokenCheck($_POST['token']);
    $validate->unauthorizedCheck([$_POST['email'],$_POST['username'],$_POST['password']]);
    if ($validate->mailCheck($_POST['email'])) {
      throw new \ganbatter\Exception\InvalidEmail("メールアドレスの形式が不正です!");
    }
    if($validate->emptyCheck([$_POST['username']])) {
      throw new \ganbatter\Exception\InvalidName("ユーザー名が入力されていません");
    }
    if($validate->passwordCheck([$_POST['password']])) {
      throw new \ganbatter\Exception\InvalidPassword("パスワードは半角英数字で入力してください。");
    }
  }
}
