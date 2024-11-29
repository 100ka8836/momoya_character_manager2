<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['form_type'] ?? '') !== 'charasheet') {
    die("不正なアクセスです。");
}

$charasheetInput = $_POST['charasheet_url'] ?? null;
$groupId = $_POST['group_id'] ?? null;
$skillNames = $_POST['skill_name'] ?? [];
$skillValues = $_POST['skill_value'] ?? [];

if (!$charasheetInput || !$groupId) {
    die("キャラクター保管所URLまたは所属グループが指定されていません。");
}

if (filter_var($charasheetInput, FILTER_VALIDATE_URL)) {
    $parsedUrl = parse_url($charasheetInput);
    $charasheetId = basename($parsedUrl['path'], ".js");
} elseif (is_numeric($charasheetInput)) {
    $charasheetId = $charasheetInput;
} else {
    die("キャラクター保管所URLが無効です。");
}

$charasheetUrl = "http://charasheet.vampire-blood.net/{$charasheetId}.js";

try {
    $characterJson = file_get_contents($charasheetUrl);

    if (!$characterJson) {
        throw new Exception("キャラクターデータを取得できませんでした。");
    }

    $characterData = json_decode($characterJson, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("キャラクターデータの解析に失敗しました。");
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO characters (name, age, occupation, sex, source_url, group_id)
        VALUES (:name, :age, :occupation, :sex, :source_url, :group_id)
    ");
    $stmt->execute([
        ':name' => $characterData['pc_name'] ?? null,
        ':age' => $characterData['age'] ?? null,
        ':occupation' => $characterData['shuzoku'] ?? null,
        ':sex' => $characterData['sex'] ?? null,
        ':source_url' => $charasheetUrl,
        ':group_id' => $groupId,
    ]);
    $characterId = $pdo->lastInsertId();

    // 手動入力技能の登録
    for ($i = 0; $i < count($skillNames); $i++) {
        $skillName = $skillNames[$i] ?? null;
        $skillValue = $skillValues[$i] ?? null;

        if ($skillName && $skillValue !== null) {
            $stmt = $pdo->prepare("
                INSERT INTO character_skills (character_id, skill_name, skill_value)
                VALUES (:character_id, :skill_name, :skill_value)
            ");
            $stmt->execute([
                ':character_id' => $characterId,
                ':skill_name' => $skillName,
                ':skill_value' => $skillValue,
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'キャラクター保管所の登録が完了しました！']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
