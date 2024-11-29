<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['form_type'] ?? '') !== 'charasheet') {
    die("不正なアクセスです。");
}

// キャラクター保管所のIDまたはURLを取得
$charasheetInput = $_POST['charasheet_id'] ?? null;
$groupId = $_POST['group_id'] ?? null;

if (!$charasheetInput || !$groupId) {
    die("キャラクター保管所IDまたは所属グループが指定されていません。");
}

// IDまたはURLを正規化
if (filter_var($charasheetInput, FILTER_VALIDATE_URL)) {
    $parsedUrl = parse_url($charasheetInput);
    parse_str($parsedUrl['path'], $pathParts);
    $charasheetId = basename($parsedUrl['path'], ".js");
} elseif (is_numeric($charasheetInput)) {
    $charasheetId = $charasheetInput;
} else {
    die("キャラクター保管所IDまたはURLが無効です。");
}

// キャラクター保管所URLを生成
$charasheetUrl = "http://charasheet.vampire-blood.net/{$charasheetId}.js";

try {
    // キャラクターJSONを取得
    $characterJson = file_get_contents($charasheetUrl);

    if (!$characterJson) {
        throw new Exception("キャラクターデータを取得できませんでした。");
    }

    // JSONデコード
    $characterData = json_decode($characterJson, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("キャラクターデータの解析に失敗しました。");
    }

    // 必要なデータを抽出
    $name = $characterData['character_name'] ?? '名無し';
    $age = $characterData['age'] ?? null;
    $occupation = $characterData['job'] ?? '不明';

    // データベースに保存
    $stmt = $pdo->prepare("
        INSERT INTO characters (name, age, occupation, group_id)
        VALUES (:name, :age, :occupation, :group_id)
    ");
    $stmt->execute([
        ':name' => $name,
        ':age' => $age,
        ':occupation' => $occupation,
        ':group_id' => $groupId
    ]);

    echo "キャラクター「{$name}」が正常に登録されました。";

} catch (Exception $e) {
    die("エラー: " . $e->getMessage());
}
