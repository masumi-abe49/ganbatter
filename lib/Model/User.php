<?php
namespace ganbatter\Model;
class User extends \ganbatter\Model {
  public function create($values) { //ユーザー新規登録のためのメソッド
    $stmt = $this->db->prepare("INSERT INTO users (username,email,password,created,modified) VALUES (:username,:email,:password,now(),now())");
    // $stmtという変数にSQL文を代入。prepareメソッドを利用してユーザーからの入力をSQLに含めることができる（変数を埋め込みできる）※queryメソッドは変数の埋め込みができない。
    $res = $stmt->execute([
      ':username' => $values['username'],
      ':email' => $values['email'],
      // executeメソッドの実行で$stmt内SQL文の変数部分に実際の値をセットしている。usernameとemail
      // この実行するSQLを準備し、後からSQLを実行することを「プリペアードステートメント」と言う。（SQLインジェクション対策のため）掲示板実装L3より。
      // パスワードのハッシュ化（パスワードはユーザー自身のみ知っている状態にするためのパスワード暗号化のこと。DBにも暗号化された状態で保存される。）
      ':password' => password_hash($values['password'],PASSWORD_DEFAULT)
    ]);
    // メールアドレスがユニークでなければfalseを返す(usersテーブルのメールアドレスカラムには「ユニークキー制約」を付けているので、重複するメールアドレスを登録しようとすると、エラーになる。)
    if ($res === false) {
      throw new \ganbatter\Exception\DuplicateEmail();
    }
  }
  public function login($values) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email;");
    $stmt->execute([
      ':email' => $values['email']
    ]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    $user = $stmt->fetch();
    if (empty($user)) {
      throw new \ganbatter\Exception\UnmatchEmailOrPassword();
    }
    if (!password_verify($values['password'], $user->password)) {
      throw new \ganbatter\Exception\UnmatchEmailOrPassword();
    }
    return $user;
  }
}