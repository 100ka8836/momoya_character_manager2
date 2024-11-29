<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>グループ作成</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main>
        <h1>グループを作成</h1>
        <?php if (isset($error) && $error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="create_group_handler.php">
            <label>グループ名: <input type="text" name="name" required></label><br>
            <label>パスワード: <input type="password" name="password" required></label><br>
            <button type="submit">作成</button>
        </form>
    </main>
</body>

</html>