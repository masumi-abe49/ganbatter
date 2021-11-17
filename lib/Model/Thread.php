<?php
namespace ganbatter\Model;
class Thread extends \ganbatter\Model {
  public function createThread($values) {
    try {
      $sql = "INSERT INTO threads (user_id,ganbatta_main,created,modified) VALUES (:user_id,:ganbatta_main,now(),now())";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue('user_id',$values['user_id']);
      $stmt->bindValue('ganbatta_main',$values['ganbatta_main']);
      $res = $stmt->execute();
      // $thread_id = $this->db->lastInsertId(); //トランザクションがなければ不要かも 211115
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  // 全頑張った！を取得するメソッド
  public function getThreadAll() {
    $stmt = $this->db->query("SELECT id,ganbatta_main,created FROM threads WHERE delflag = 0 ORDER BY id desc");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  // 頑張った！に対するコメントを取得するメソッド
  public function getComment($thread_id) {
    $stmt = $this->db->prepare("SELECT comment_num,username,content,comments.created FROM comments INNNER JOIN users ON user_id = user.id WHERE thread_id = :thread_id AND comments.delflag = 0 ORDER BY comment_num ASC LIMIT 5;");
    $stmt->execute([':thread_id' => $thread_id]);
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

}