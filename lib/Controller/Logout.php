<?php
namespace ganbatter\Controller;
class Logout extends \ganbatter\Controller {
  public function run() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        echo "不正なトークンです！";
        exit();
      }
      $_SESSION = [];
      if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 86400, '/');
      }
      // セッションの破棄
      session_destroy();
    }
    // トップページへリダイレクト
    header('Location: ' . SITE_URL);
  }
}