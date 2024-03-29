<?php
namespace ganbatter\Controller;
class UserUpdate extends \ganbatter\Controller {
  public function run() {
    $this->showUser();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // var_dump($_FILES['image']);
      // exit;
      $this->updateUser();
    }
  }

  protected function showUser() {
    $user = new \ganbatter\Model\User();
    $userData = $user->find($_SESSION['me']->id);
    $this->setValues('username', $userData->username);
    $this->setValues('email', $userData->email);
    $this->setValues('image', $userData->image);
  }

  protected function updateUser() {
    try {
      $this->validate();
    } catch (\ganbatter\Exception\InvalidEmail $e) {
      $this->setErrors('email', $e->getMessage());
    } catch (\ganbatter\Exception\InvalidName $e) {
      $this->setErrors('username', $e->getMessage());
    }
    $this->setValues('username', $_POST['username']);
    $this->setValues('email', $_POST['email']);
    if ($this->hasError()) {
      return;
    } else {
      $user_img = $_FILES['image'];
      $old_img = $_POST['old_image'];
      $ext = substr($user_img['name'], strrpos($user_img['name'], '.') + 1);
      $user_img['name'] = uniqid("img_") .'.'. $ext;
      if($old_img == '') {
        $old_img = NULL;
        // phpMyAdmin（データベース）のuserテーブルのimageカラム（今回は$old_imgを定義している）が「更新ボタン」を押したときに空欄（空データ）だった場合はNULLを代入する。これによりimageカラムが空欄＝画像削除をした際にNULLが登録され、ビュー（mypage.php）に記載しているようにnoimage.pngが表示されるようになる。220201
      }
      try {
        $userModel = new \ganbatter\Model\User();
        if($user_img['size'] > 0) {
          unlink('./gazou/'.$old_img);
          move_uploaded_file($user_img['tmp_name'],'./gazou/'.$user_img['name']);
          $userModel->update([
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'userimg' => $user_img['name']
          ]);
          $_SESSION['me']->image = $user_img['name'];
        } else {
          $userModel->update([
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'userimg' => $old_img
          ]);
          $_SESSION['me']->image = $old_img;
          // 本メソッド内で画像がアップロードされていなかった場合、$_SESSION['me']のimageの値に$old_img（すなわちNULL）を代入してヘッダー部分の画像にも同じ画像を反映させている。220201
        }
      }
      catch (\ganbatter\Exception\DuplicateEmail $e) {
        $this->setErrors('email', $e->getMessage());
        return;
      }
    }
    $_SESSION['me']->username = $_POST['username'];
    header('Location: '. SITE_URL . '/mypage.php');
    exit();
  }

  private function validate() {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      echo "不正なトークンです！";
      exit();
    }
    if (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
      throw new \ganbatter\Exception\InvalidEmail("メールアドレスが不正です。");
    }
    if ($_POST['username'] === '') {
      throw new \ganbatter\Exception\InvalidName("ユーザー名が入力されていません！");
    }
  }
}