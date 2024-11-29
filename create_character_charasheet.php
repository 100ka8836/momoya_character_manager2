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
    <title>キャラクター保管所登録</title>
    <script src="assets/js/add_skills.js" defer></script>
</head>

<body>
    <h2>キャラクター保管所から登録</h2>
    <form method="post" action="create_character_handler_charasheet.php">
        <label>キャラクター保管所URL: <input type="url" name="charasheet_url" required></label><br>
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

        <h3>技能</h3>
        <div id="skill_list">
            <?php
            $skills = [
                "言いくるめ" => 5,
                "医学" => 1,
                "運転（自動車）" => 20,
                "応急手当" => 30,
                "オカルト" => 5,
                // 他の技能をここに記載
            ];
            foreach ($skills as $skillName => $defaultValue): ?>
                <div class="skill_input_row">
                    <label><?= htmlspecialchars($skillName) ?>:
                        <input type="number" name="skill_value[<?= htmlspecialchars($skillName) ?>]"
                            value="<?= htmlspecialchars($defaultValue) ?>" required>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit">キャラクター保管所登録</button>
    </form>
</body>

</html>