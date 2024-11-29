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
    <title>いあきゃら登録</title>
</head>

<body>
    <h2>いあきゃらから登録</h2>
    <form method="post" action="create_character_handler_iachara.php">
        <label>キャラクター名: <input type="text" name="name" required></label><br>
        <label>年齢: <input type="number" name="age" required></label><br>
        <label>性別:
            <select name="sex" required>
                <option value="">選択してください</option>
                <option value="男性">男性</option>
                <option value="女性">女性</option>
                <option value="その他">その他</option>
            </select>
        </label><br>
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
        <label>説明: <textarea name="description" rows="4" required></textarea></label><br>
        <button type="submit">いあきゃら登録</button>
    </form>
</body>

</html>