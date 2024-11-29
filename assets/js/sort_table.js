document.addEventListener("DOMContentLoaded", () => {
  const tables = document.querySelectorAll("table[id='sortable-table']");

  if (!tables) return;

  tables.forEach((table) => {
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    let sortOrder = "asc"; // 初期状態は昇順

    // 各行の最初のセルをクリック可能にする
    rows.forEach((row) => {
      const firstCell = row.querySelector("td:first-child");

      if (!firstCell) return;

      firstCell.addEventListener("click", () => {
        // クリックされた行のすべてのセルを取得
        const cells = Array.from(row.querySelectorAll("td")).slice(1);

        // 並び替え基準データを取得
        const columnData = cells.map((cell, index) => ({
          index: index + 1, // 列番号 (1列目を除く)
          value: isNaN(cell.textContent.trim())
            ? cell.textContent.trim()
            : parseFloat(cell.textContent.trim()) // 数値か文字列かを判別
        }));

        // 昇順・降順の切り替え
        columnData.sort((a, b) => {
          if (sortOrder === "asc") {
            return a.value > b.value ? 1 : -1;
          } else {
            return a.value < b.value ? 1 : -1;
          }
        });

        // ソート順序を反転
        sortOrder = sortOrder === "asc" ? "desc" : "asc";

        // ソートされた列順序に基づいてテーブルを再構築
        const newOrder = columnData.map((data) => data.index);
        const headerRow = table.querySelector("thead tr");
        const allRows = [headerRow, ...rows];

        allRows.forEach((tr) => {
          const cells = Array.from(tr.children);
          const reorderedCells = [cells[0], ...newOrder.map((i) => cells[i])];
          reorderedCells.forEach((cell, i) => tr.appendChild(cell));
        });
      });
    });
  });
});
