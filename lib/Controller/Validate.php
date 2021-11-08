<?php
namespace ganbatter\Controller;

class Validate {
  public function tokenCheck($value) {
    if (!isset($value) || $value !== $_SESSION['token']) {
      echo "不正なトークンです!";
      exit;
    }
  }

  public function unauthorizedCheck($values) {
    $errFlag = false;
    foreach($values as $value) {
      if(!isset($value)) {
        echo '不正な投稿です';
        exit;
      }
    }
  }

  public function emptyCheck($values) {
    $errFlag = false;
    foreach($values as $value) {
      if($value === '') {
        $errFlag = true;
      }
    }
    return $errFlag;
  }

  public function charLenghtCheck($value,$num) {
    if (mb_strlen($value) > $num) {
      return true;
    }
  }

  public function mailCheck($value) {
    if (!filter_var($value,FILTER_VALIDATE_EMAIL)) {
      return true;
    }
  }

  public function passwordCheck($value) {
    if(!preg_match('/\A[a-zA-Z0-9]+\z/', $_POST['password'])) {
      return true;
    }
  }

}
