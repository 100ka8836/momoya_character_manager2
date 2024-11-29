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
    <title>キャラクター登録</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/form_handler.js" defer></script>
    <script src="assets/js/add_skills.js" defer></script>
</head>

<body>
    <nav>
        <a href="create_character_charaeno.php">キャラエノ</a>
        <a href="create_character_charasheet.php">キャラクター保管所</a>
        <a href="create_character_iachara.php">いあきゃら</a>
    </nav>

    </main>
</body>

</html>