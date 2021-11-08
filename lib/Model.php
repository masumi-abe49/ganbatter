<?php

namespace ganbatter;

class Model {

  protected $db;
  public function __construct(){
    //  Modelクラス及び子クラスのインスタンスを生成した際には、必ずPDOクラスのインスタンスを生成する。
    try {
      $this->db = new \PDO(DSN, DB_USERNAME,  DB_PASSWORD);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }
}
// PDOとは「PHP Data Object」の略。PHPではPDOを利用し、DBへの接続、SQL文の実行が出来る。掲示板実装L3より。