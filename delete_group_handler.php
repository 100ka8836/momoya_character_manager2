<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = $_POST['group_id'] ?? null;

    if (!$group_id) {
        die('グループIDが指定されていません。');
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM groups WHERE id = ?");
        $stmt->execute([$group_id]);

        // 削除後、グループ一覧にリダイレクト
        header('Location: groups.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        die('グループを削除できませんでした: ' . $e->getMessage());
    }
}
?>