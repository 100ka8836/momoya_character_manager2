<?php
require 'includes/db.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // パスワードの検証: 英数字のみ
    if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        $error = "パスワードは英数字のみを使用してください。";
    } else {
        try {
            // パスワードをハッシュ化して保存
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // グループをデータベースに追加
            $stmt = $pdo->prepare("INSERT INTO groups (name, password) VALUES (?, ?)");
            $stmt->execute([$name, $hashed_password]);

            // 作成完了後のリダイレクト
            header('Location: groups.php?created=1');
            exit;
        } catch (PDOException $e) {
            // データベースエラーを処理
            $error = "グループを作成できませんでした: " . $e->getMessage();
        }
    }
}
?>