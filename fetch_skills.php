<?php
require 'includes/db.php';

function fetchSkills($group_id, $pdo)
{
    if (!$group_id) {
        return [];
    }

    // キャラクターの技能を取得
    $stmt = $pdo->prepare("
        SELECT character_id, skill_name, skill_value
        FROM character_skills
        WHERE character_id IN (SELECT id FROM characters WHERE group_id = ?)
    ");
    $stmt->execute([$group_id]);
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

    // 技能リストを生成
    $all_skills = is_array($skills)
        ? array_unique(array_map(fn($s) => $s['skill_name'], $skills))
        : [];

    // キャラクターIDごとに技能をグループ化
    $grouped_skills = [];
    foreach ($skills as $skill) {
        $grouped_skills[$skill['character_id']][] = $skill;
    }

    return ['skills' => $grouped_skills, 'all_skills' => $all_skills];
}
