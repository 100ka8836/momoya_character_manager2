<?php
// Composerのオートローダーを読み込む
require_once __DIR__ . '/../vendor/autoload.php';

// Dotenvを初期化して読み込む
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 環境変数からデータベース情報を取得
$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

try {
    // PDOを使用してデータベースに接続
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    // エラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

?>