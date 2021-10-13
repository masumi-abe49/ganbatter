<?php
ini_set('display_errors',1); //PHPのエラーメッセージ表示用のため、本番用では削除する。
define('DSN','mysql:host=localhost;charset=utf8;dbname= ganbatter');
define('DB_USERNAME','ganbatter_user'); // データベース作成時（phpMyAdmin）のUSERNAMEを記載。Gmail下書き保存確認。
define('DB_PASSWORD','pktF9utOOk8BPMFG'); // phpMyAdminで作成したユーザーのパスワードを記述する。Gmail下書き保存確認。
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/ganbatter/public_html');
require_once(__DIR__ .'/../lib/Controller/functions.php');
require_once(__DIR__ . '/autoload.php');
session_start();