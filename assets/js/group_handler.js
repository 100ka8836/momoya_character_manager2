document.addEventListener("DOMContentLoaded", () => {
  const groupButtons = document.querySelectorAll(".group-button");
  const modal = document.getElementById("password-modal");
  const modalClose = document.getElementById("modal-close");
  const groupIdInput = document.getElementById("group-id");
  const passwordForm = document.getElementById("password-form");
  const errorMessage = document.getElementById("error-message");

  // グループボタンをクリックするとモーダルを表示
  groupButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const groupId = button.dataset.groupId;
      groupIdInput.value = groupId; // グループIDをセット
      modal.style.display = "flex"; // モーダルを表示
    });
  });

  // モーダルを閉じる
  modalClose.addEventListener("click", () => {
    modal.style.display = "none";
    errorMessage.style.display = "none";
  });

  // モーダル外をクリックして閉じる
  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      errorMessage.style.display = "none";
    }
  });

  // パスワードフォームの送信
  passwordForm.addEventListener("submit", async (event) => {
    event.preventDefault(); // デフォルトのフォーム送信を防ぐ
    const formData = new FormData(passwordForm);

    try {
      const response = await fetch("group_password_handler.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();
      if (result.success) {
        // 成功: グループページへ移動
        window.location.href = `group_page.php?group_id=${groupIdInput.value}`;
      } else {
        // 失敗: エラーメッセージを表示
        errorMessage.textContent = result.message;
        errorMessage.style.display = "block";
      }
    } catch (error) {
      console.error("通信エラー:", error);
    }
  });
});
