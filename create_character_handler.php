<?php

header('Content-Type: application/json'); // JSON形式を指定
require 'includes/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("無効なリクエストメソッドです。");
    }

    $formType = $_POST['form_type'] ?? null;
    $groupId = $_POST['group_id'] ?? null;

    if (!$groupId) {
        throw new Exception("グループが選択されていません。");
    }

    if ($formType === 'charaeno') {
        $sourceUrl = $_POST['charaeno_url'] ?? null;

        if (!$sourceUrl) {
            throw new Exception("キャラエノのURLが入力されていません。");
        }

        $characterId = extractCharaenoId($sourceUrl);

        // 6th版APIエンドポイント
        $apiUrl = "https://charaeno.com/api/v1/6th/$characterId/summary";
        $response = file_get_contents($apiUrl);
        if ($response === false) {
            throw new Exception("キャラエノAPIからデータを取得できませんでした。");
        }


        $characterData = json_decode($response, true);
        if (!$characterData) {
            throw new Exception("キャラエノAPIのデータが無効です。");
        }

        // データベース登録
        insertCharacterToDatabase($pdo, $characterData, $groupId, $sourceUrl);

        echo json_encode(['success' => true, 'message' => '登録が完了しました！']);
    } else {
        throw new Exception("無効なフォームタイプです。");
    }
} catch (Exception $e) {
    error_log("エラー発生: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}

function extractCharaenoId($url)
{
    if (preg_match('/https:\/\/charaeno\.com\/6th\/([^\/]+)/', $url, $matches)) {
        return $matches[1];
    }
    throw new Exception("キャラエノのURL形式が正しくありません。");
}

function insertCharacterToDatabase($pdo, $data, $groupId, $sourceUrl)
{
    $pdo->beginTransaction();

    try {
        // characters テーブル登録
        $stmt = $pdo->prepare("
    INSERT INTO characters 
    (name, occupation, birthplace, degree, age, sex, address, description, family, injuries, scar, income, cash, deposit, personal_property, real_estate, mythos_tomes, artifacts_and_spells, encounters, note, chatpalette, portrait_url, source_url, group_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
        $stmt->execute([
            $data['name'],
            $data['occupation'] ?? null,
            $data['birthplace'] ?? null,
            $data['degree'] ?? null,
            $data['age'] ?? null,
            $data['sex'] ?? null,
            $data['personalData']['address'] ?? null,
            $data['personalData']['description'] ?? null,
            $data['personalData']['family'] ?? null,
            $data['personalData']['injuries'] ?? null,
            $data['personalData']['scar'] ?? null,
            $data['credit']['income'] ?? null,
            $data['credit']['cash'] ?? null,
            $data['credit']['deposit'] ?? null,
            $data['credit']['personalProperty'] ?? null,
            $data['credit']['realEstate'] ?? null,
            $data['mythosTomes'] ?? null,
            $data['artifactsAndSpells'] ?? null,
            $data['encounters'] ?? null,
            $data['note'] ?? null,
            $data['chatpalette'] ?? null,
            $data['portraitURL'] ?? null,
            $sourceUrl,
            $groupId,
        ]);

        $characterId = $pdo->lastInsertId();

        // character_attributes テーブル登録
        $stmt = $pdo->prepare("
            INSERT INTO character_attributes (character_id, str, con, pow, dex, app, siz, int_value, edu, hp, mp, db, san_current, san_max)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $characterId,
            $data['characteristics']['str'] ?? null,
            $data['characteristics']['con'] ?? null,
            $data['characteristics']['pow'] ?? null,
            $data['characteristics']['dex'] ?? null,
            $data['characteristics']['app'] ?? null,
            $data['characteristics']['siz'] ?? null,
            $data['characteristics']['int'] ?? null,
            $data['characteristics']['edu'] ?? null,
            $data['attribute']['hp'] ?? null,
            $data['attribute']['mp'] ?? null,
            $data['attribute']['db'] ?? null,
            $data['attribute']['san']['value'] ?? null,
            $data['attribute']['san']['max'] ?? null,
        ]);

        // character_skills テーブル登録
        if (!empty($data['skills'])) {
            foreach ($data['skills'] as $skill) {
                $stmt = $pdo->prepare("
                    INSERT INTO character_skills (character_id, skill_name, skill_value, edited)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $characterId,
                    $skill['name'] ?? null,
                    $skill['value'] ?? null,
                    $skill['edited'] ?? false,
                ]);
            }
        }

        // character_weapons テーブル登録
        if (!empty($data['weapons'])) {
            foreach ($data['weapons'] as $weapon) {
                $stmt = $pdo->prepare("
                    INSERT INTO character_weapons (character_id, weapon_name, skill_value, damage, `range`, attacks, ammo, malfunction, hp)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $characterId,
                    $weapon['name'] ?? null,
                    $weapon['value'] ?? null,
                    $weapon['damage'] ?? null,
                    $weapon['range'] ?? null,
                    $weapon['attacks'] ?? null,
                    $weapon['ammo'] ?? null,
                    $weapon['malfunction'] ?? null,
                    $weapon['hp'] ?? null,
                ]);
            }
        }

        // character_possessions テーブル登録
        if (!empty($data['possessions'])) {
            foreach ($data['possessions'] as $possession) {
                $stmt = $pdo->prepare("
                    INSERT INTO character_possessions (character_id, possession_name, possession_count, possession_detail)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $characterId,
                    $possession['name'] ?? null,
                    $possession['count'] ?? null,
                    $possession['detail'] ?? null,
                ]);
            }
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("データベースエラー: " . $e->getMessage());
        throw $e;

    }
}
