<?php
require 'includes/db.php';

// データベースからグループ一覧を取得
$stmt = $pdo->query("SELECT id, name FROM groups");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>キャラエノ登録</title>
</head>

<body>
    <h2>キャラエノから登録</h2>
    <form method="post" action="create_character_handler.php">
        <label>キャラエノURL: <input type="url" name="charaeno_url" required></label><br>
        <label>所属グループ:
            <select name="group_id" required>
                <option value="">選択してください</option>
                <?php foreach ($groups as $group): ?>
                    <option value="<?= htmlspecialchars($group['id']) ?>">
                        <?= htmlspecialchars($group['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">キャラエノ登録</button>
    </form>
</body>

</html>