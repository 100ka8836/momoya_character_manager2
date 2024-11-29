<?php
require 'includes/db.php';

// グループIDを取得
$group_id = $_GET['group_id'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];

    // データベースからグループ情報を取得
    $stmt = $pdo->prepare("SELECT * FROM groups WHERE id = ?");
    $stmt->execute([$group_id]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($group && $group['password'] === $password) {
        header("Location: group_page.php?group_id=" . $group_id);
        exit;
    } else {
        $error = "パスワードが正しくありません。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>パスワード確認</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>

    <main>
        <h1>グループへのアクセス</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <label>パスワード: <input type="password" name="password" required></label><br>
            <button type="submit">確認</button>
        </form>
    </main>
</body>

</html>