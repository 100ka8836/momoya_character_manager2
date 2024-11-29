document.addEventListener("DOMContentLoaded", () => {
  const addSkillButton = document.getElementById("add-skill-button");
  const newSkillContainer = document.getElementById("new-skill-container");

  // 新しい技能を追加する関数
  const addNewSkill = () => {
    const skillRow = document.createElement("div");
    skillRow.className = "skill-row";

    // 技能名入力フォーム
    const skillNameInput = document.createElement("input");
    skillNameInput.type = "text";
    skillNameInput.name = "new_skill_name[]";
    skillNameInput.placeholder = "技能名";
    skillNameInput.required = true;

    // 技能値入力フォーム
    const skillValueInput = document.createElement("input");
    skillValueInput.type = "number";
    skillValueInput.name = "new_skill_value[]";
    skillValueInput.placeholder = "技能値";
    skillValueInput.required = true;

    // 削除ボタン
    const removeButton = document.createElement("button");
    removeButton.type = "button";
    removeButton.textContent = "×";
    removeButton.onclick = () => {
      newSkillContainer.removeChild(skillRow);
    };

    // フォームを行としてまとめる
    skillRow.appendChild(skillNameInput);
    skillRow.appendChild(skillValueInput);
    skillRow.appendChild(removeButton);

    // 新しい行をコンテナに追加
    newSkillContainer.appendChild(skillRow);
  };

  // ボタンのクリックイベントで新しい技能を追加
  addSkillButton.addEventListener("click", addNewSkill);
});
