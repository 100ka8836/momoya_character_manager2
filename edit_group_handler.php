<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = $_POST['group_id'] ?? null;
    $name = $_POST['name'];
    $password = $_POST['password'] ?? null;

    if (!$group_id) {
        header("Location: groups.php?error=グループIDが指定されていません。");
        exit;
    }

    try {
        // グループ名とパスワードの更新
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE groups SET name = ?, password = ? WHERE id = ?");
            $stmt->execute([$name, $hashed_password, $group_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE groups SET name = ? WHERE id = ?");
            $stmt->execute([$name, $group_id]);
        }
        header("Location: groups.php?updated=1");
        exit;
    } catch (PDOException $e) {
        header("Location: groups.php?error=更新に失敗しました: " . urlencode($e->getMessage()));
        exit;
    }
}
