<?php
namespace ganbatter\Model;
class Thread extends \ganbatter\Model {
  public function createThread($values) {
    try {
      $sql = "INSERT INTO threads (user_id,ganbatta_main,created,modified) VALUES (:user_id,:ganbatta_main,now(),now())"; //php.iniのdate.timezoneが設定なし（UTC）のため、nou関数を使うとJST（日本時間）から-9:00で登録されてしまう。PHPの設定を変更する必要あるかも。
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue('user_id',$values['user_id']);
      $stmt->bindValue('ganbatta_main',$values['ganbatta_main']);
      $res = $stmt->execute();
      // $thread_id = $this->db->lastInsertId(); //トランザクションがなければ不要かも 211115
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  // 全頑張った！を取得するメソッド INNER JOIN usersと LEFT JOIN likesを同時にやらないとダメかも。
  public function getThreadAll() {
    $user_id = $_SESSION['me']->id;
    // $stmt = $this->db->query("SELECT t.id AS t_id,ganbatta_main,t.created,l.id AS l_id FROM threads AS t LEFT JOIN likes AS l ON t.delflag = 0 AND t.id = l.thread_id AND l.user_id = $user_id ORDER BY t.id desc");

    $stmt = $this->db->query("SELECT t.id AS t_id,t.user_id,users.username,ganbatta_main,t.created,l.id AS l_id FROM threads AS t INNER JOIN users ON t.user_id = users.id LEFT JOIN likes AS l ON t.delflag = 0 AND t.id = l.thread_id AND l.user_id = $user_id ORDER BY t.id desc");

    // $stmt = $this->db->query("SELECT threads.id,threads.user_id,users.username,ganbatta_main,threads.created FROM threads INNER JOIN users ON threads.user_id = users.id LEFT JOIN likes ON threads.delflag = 0 AND threads.id = likes.thread_id AND likes.user_id = $user_id ORDER BY id desc");

    // threadsテーブルとusersテーブルを内部結合（INNER JOIN）し、ON句を使ってthreadsテーブルのuser_idとusersテーブルのidを結合条件としてSELECT句の中のusers.usernameでthreadsテーブルのidに紐づくuser_idのユーザー名を取得。ON句での条件指定は、結合条件を指定する意味合いがある。また、WHERE句での条件指定は、対象データの抽出を行うために使う。結合前のデータで条件による絞り込みを行うのが「ON句」、結合後のデータで条件による絞り込みを行うのが「WHERE句」に記述した場合となる。 AS句とは、カラムやテーブルに別名をつけられる句。別名をつけたいカラムの後ろに「AS」と別名を記述すればOK。threadsを「t」に、likesを「l」と別名をつけています。別名をつけないと「threads.id」「like.id」など、クエリが冗長になってしまうでしょう。このように、テーブル名を短く設定したい場合などにテーブルの別名設定は有効
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  // 頑張った！に対するコメントを取得するメソッド
  public function getComment($thread_id) {
    $stmt = $this->db->prepare("SELECT comment_num,username,content,comments.created FROM comments INNER JOIN users ON user_id = users.id WHERE thread_id = :thread_id AND comments.delflag = 0 ORDER BY comment_num ASC LIMIT 5;");
    $stmt->execute([':thread_id' => $thread_id]);
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  // コメント数取得 COUNT関数でコメント数をカウント、AS句でカラムに別名をつけられる。FETCH_ASSOCは取得結果を連想配列で返し、FETCH_OBJはオブジェクト型で返してくれる。
  public function getCommentCount($thread_id) {
    $stmt = $this->db->prepare("SELECT COUNT(comment_num) AS record_num FROM comments WHERE thread_id = :thread_id AND delflag = 0;");
    $stmt->bindValue('thread_id',$thread_id);
    $stmt->execute();
    $res = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $res['record_num'];
  }

  //  スレッド1件取得 頑張った！作成ユーザー名も取得する必要あり。→prepare文の中でカラム指定するときは正確に指定する。（同じカラム名がある場合は必ず＜テーブル名.カラム名,＞を入力する。） 220130
  public function getThread($thread_id) {
    $stmt = $this->db->prepare("SELECT threads.id,user_id,ganbatta_main,threads.delflag,threads.created,username FROM threads INNER JOIN users ON user_id = users.id WHERE threads.id = :id AND threads.delflag = 0 ");
    $stmt->bindValue(":id",$thread_id);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_OBJ);
  }

  // コメント全件取得
  public function getCommentAll($thread_id) {
    $stmt = $this->db->prepare("SELECT comment_num,username,content,comments.created FROM comments INNER JOIN users ON user_id = users.id WHERE thread_id = :thread_id AND comments.delflag = 0 ORDER BY comment_num ASC; ");
    $stmt->execute([':thread_id' => $thread_id]);
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  // コメント投稿
  public function createComment($values) {
    try {
      $this->db->beginTransaction();
      $lastNum = 0;
      $sql = "SELECT comment_num FROM comments WHERE thread_id = :thread_id ORDER BY comment_num DESC LIMIT 1";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue('thread_id',$values['thread_id']);
      $stmt->execute();
      $res = $stmt->fetch(\PDO::FETCH_OBJ);
      $lastNum = $res->comment_num;
      $lastNum++;
      $sql = "INSERT INTO comments (thread_id,comment_num,user_id,content,created,modified) VALUES (:thread_id,:comment_num,:user_id,:content,now(),now())";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue('thread_id',$values['thread_id']);
      $stmt->bindValue('comment_num',$lastNum);
      $stmt->bindValue('user_id',$values['user_id']);
      $stmt->bindValue('content',$values['content']);
      $stmt->execute();
      // トランザクション処理を終了する
      $this->db->commit();
    } catch (\Exception $e) {
      echo $e->getMessage();
      // エラーがあれば元に戻す
      $this->db->rollBack();
    }
  }

  // いいぞ！機能(お気に入りみたいなもの) favの部分をlikeに変更する方がわかりやすいかも
  public function changeLike($values) {
    try {
      $this->db->beginTransaction();
      // レコード取得
      $stmt = $this->db->prepare("SELECT * FROM likes WHERE thread_id = :thread_id AND user_id = :user_id");
      $stmt->execute([
        ':thread_id' => $values['thread_id'],
        ':user_id' => $values['user_id']
      ]);
      // fetchMode データを扱いやすい形に変換
      $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
      $rec = $stmt->fetch();
      $fav_flag = 0;
      if (empty($rec)) {
        $sql = "INSERT INTO likes (thread_id,user_id,created) VALUES (:thread_id,:user_id,now())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
          ':thread_id' => $values['thread_id'],
          ':user_id' => $values['user_id']
        ]);
        $fav_flag = 1;
      } else {
        $sql = "DELETE FROM likes WHERE thread_id = :thread_id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([
          ':thread_id' => $values['thread_id'],
          ':user_id' => $values['user_id']
        ]);
        $fav_flag = 0;
      }
      $this->db->commit();
      return $fav_flag;
      // $fav_flagが1であれば、いいぞ！あり、ということになり、returnで結果を返している。ganbatter.jsのsuccess: function (data) { if (data == 1) { で使用している。
    } catch (\Exception $e) {
      echo $e->getMessage();
      // エラーがあったら元に戻す
      $this->db->rollback();
    }
  }

  //いいぞ！したスレッドの取得
  public function getThreadLikeALL() {
    $user_id = $_SESSION['me']->id;
    $stmt = $this->db->query("SELECT t.id AS t_id,t.user_id,users.username,ganbatta_main,t.created,l.id AS l_id FROM threads AS t INNER JOIN users ON t.user_id = users.id INNER JOIN likes AS l ON t.delflag = 0 AND t.id = l.thread_id AND l.user_id = $user_id ORDER BY t.id desc");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  // 頑張った！検索
  public function searchThread($keyword) {
    $user_id = $_SESSION['me']->id;
    // $stmt = $this->db->prepare("SELECT * FROM threads WHERE ganbatta_main LIKE :ganbatta_main AND delflag = 0;");
    $stmt = $this->db->prepare("SELECT t.id AS t_id,t.user_id,users.username,ganbatta_main,t.created,l.id AS l_id FROM threads AS t INNER JOIN users ON t.user_id = users.id LEFT JOIN likes AS l ON t.delflag = 0 AND t.id = l.thread_id AND l.user_id = $user_id WHERE ganbatta_main LIKE :ganbatta_main");
    $stmt->execute([':ganbatta_main' => '%'.$keyword.'%']);
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

}