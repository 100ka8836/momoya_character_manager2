<?php
require 'includes/db.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("無効なリクエストメソッドです。");
    }

    $groupId = $_POST['group_id'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$groupId || !$password) {
        throw new Exception("グループIDまたはパスワードが入力されていません。");
    }

    // グループ情報を取得
    $stmt = $pdo->prepare("SELECT password FROM groups WHERE id = ?");
    $stmt->execute([$groupId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$group || !password_verify($password, $group['password'])) {
        echo json_encode(['success' => false, 'message' => 'パスワードが正しくありません。']);
        exit;
    }

    // 成功
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
