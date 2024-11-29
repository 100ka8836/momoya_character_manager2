document.addEventListener("DOMContentLoaded", () => {
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    form.addEventListener("submit", async (event) => {
      event.preventDefault(); // デフォルトの送信を防ぐ
      const formData = new FormData(form);

      // フォーム内のメッセージ表示エリアを取得
      const messageDiv = form.querySelector(".message");
      if (!messageDiv) {
        console.error("メッセージ表示エリアが見つかりません");
        return;
      }

      try {
        const response = await fetch(form.action, {
          method: "POST",
          body: formData
        });

        if (!response.ok) {
          const errorText = await response.text();
          messageDiv.textContent = `サーバーエラー: ${errorText}`;
          messageDiv.style.color = "red";
          console.error("詳細なエラー情報:", result);

          return;
        }

        // JSONを解析
        const result = await response.json();

        if (result.success) {
          messageDiv.textContent = result.message || "登録が完了しました！";
          messageDiv.style.color = "green";
          form.reset(); // フォームをリセット
        } else {
          messageDiv.textContent = result.message || "エラーが発生しました。";
          messageDiv.style.color = "red";
          console.error("詳細なエラー情報:", result);
        }
      } catch (error) {
        console.error("詳細なエラー情報:", result);

        messageDiv.textContent = `通信エラー: ${error.message}`;
        messageDiv.style.color = "red";
      }
    });
  });
});
