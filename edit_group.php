<?php
require 'includes/db.php';

$group_id = $_GET['group_id'] ?? null;

if (!$group_id) {
    die("グループIDが指定されていません。");
}

// グループ情報を取得
$stmt = $pdo->prepare("SELECT name FROM groups WHERE id = ?");
$stmt->execute([$group_id]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    die("指定されたグループが存在しません。");
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'] ?? null;

    try {
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
        $error = "更新に失敗しました: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>グループ編集</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1>グループ編集</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <label>グループ名: <input type="text" name="name" value="<?= htmlspecialchars($group['name']) ?>"
                    required></label><br>
            <label>パスワード（変更する場合のみ入力）: <input type="password" name="password"></label><br>
            <button type="submit">更新</button>
        </form>
    </main>
</body>

</html>