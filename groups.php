<?php
require 'includes/db.php';

// グループのデータを取得
$stmt = $pdo->query("SELECT id, name FROM groups");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>グループ一覧</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>

        <?php if (isset($_GET['created'])): ?>
            <p style="color: green;">グループが作成されました。</p>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <p style="color: green;">グループが削除されました。</p>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
            <p style="color: green;">グループが更新されました。</p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;">エラー: <?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <!-- グループ追加フォーム -->
        <h2>グループを追加</h2>
        <form method="POST" action="create_group_handler.php">
            <label>グループ名: <input type="text" name="name" required></label><br>
            <label>パスワード: <input type="password" name="password" required></label><br>
            <button type="submit">グループを作成</button>
        </form>

        <!-- グループリスト -->
        <h2>既存のグループ</h2>
        <ul>
            <?php foreach ($groups as $group): ?>
                <li>
                    <?= htmlspecialchars($group['name']) ?>
                    <a href="edit_group.php?group_id=<?= htmlspecialchars($group['id']) ?>">編集</a>
                    <form method="POST" action="delete_group_handler.php" style="display:inline;">
                        <input type="hidden" name="group_id" value="<?= htmlspecialchars($group['id']) ?>">
                        <button type="submit" onclick="return confirm('本当に削除しますか？');">削除</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>

</html>