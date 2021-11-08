<?php
// プログラム上で未定義のクラスが見つかったら spl_autoload_register で定義した内容に従って自動的にファイルを require する
spl_autoload_register(function($class) {
  $prefix = 'ganbatter\\';  //namespacesやクラス、インスタンス作成時に影響が出る部分。211108
  if (strpos($class, $prefix) === 0) {
    $className = substr($class, strlen($prefix));
    $classFilePath = __DIR__ . '/../lib/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($classFilePath)) {
      require $classFilePath;
    }
  }
});

// オートロードとは、ファイルを自動で読み込む仕組みのこと。クラスを作成する場合、再利用する性質上1クラスを1ファイルで管理するのが基本。しかし扱うファイル数が増えた時に、各ファイルの先頭で一つ一つrequireする必要が出てくる。そこでファイルを自動で読み込む仕組みであるautoloadを使用することで、管理を楽にしていく。掲示板実装L２より。