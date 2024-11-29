document.addEventListener("DOMContentLoaded", () => {
  // すべてのタブボタンを取得
  const tabButtons = document.querySelectorAll(".tab-button");
  // すべてのタブコンテンツを取得
  const tabContents = document.querySelectorAll(".tab-content");

  // タブボタンのクリックイベントを設定
  tabButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // アクティブなタブをリセット
      tabButtons.forEach((btn) => btn.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));

      // クリックされたボタンをアクティブにする
      button.classList.add("active");
      // 対応するタブコンテンツをアクティブにする
      const tabId = button.dataset.tab;
      const activeContent = document.getElementById(tabId);
      if (activeContent) {
        activeContent.classList.add("active");
      }
    });
  });
});
