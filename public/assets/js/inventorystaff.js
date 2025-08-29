// inventorystaff.js
document.addEventListener("DOMContentLoaded", () => {
  const addItemModal = document.getElementById("addItemModal");
  const btnAdd = document.getElementById("btnAdd");
  const addClose = document.getElementById("addClose");
  const addClose2 = document.getElementById("addClose2");
  const addForm = document.getElementById("addForm");
  const invTable = document.getElementById("invTable").querySelector("tbody");
  const searchBox = document.getElementById("searchBox");
  const filterBranch = document.getElementById("filterBranch");
  const filterStatus = document.getElementById("filterStatus");
  const filterDate = document.getElementById("filterDate");

  // ---------- MODAL OPEN / CLOSE ----------
  btnAdd.addEventListener("click", () => (addItemModal.hidden = false));
  [addClose, addClose2].forEach((btn) =>
    btn.addEventListener("click", () => (addItemModal.hidden = true))
  );
  addItemModal.querySelector(".backdrop")?.addEventListener("click", () => (addItemModal.hidden = true));

  // ---------- OTHER OPTION HANDLER ----------
  const itemSelect = addForm.querySelector("select[name='name']");
  let otherInput = null;

  itemSelect.addEventListener("change", () => {
    if (itemSelect.value === "Other") {
      if (!otherInput) {
        otherInput = document.createElement("input");
        otherInput.type = "text";
        otherInput.name = "otherName";
        otherInput.placeholder = "Enter custom item name";
        otherInput.required = true;
        otherInput.style.marginTop = "8px";
        itemSelect.insertAdjacentElement("afterend", otherInput);
      }
    } else if (otherInput) {
      otherInput.remove();
      otherInput = null;
    }
  });

  // ---------- ADD FORM SUBMIT ----------
  addForm.addEventListener("submit", (e) => {
    e.preventDefault();

    let name = itemSelect.value;
    if (name === "Other" && otherInput) {
      name = otherInput.value.trim();
      if (!name) {
        alert("Please enter a custom item name.");
        return;
      }
    }

    const category = addForm.querySelector("input[name='category']").value;
    const branch = addForm.querySelector("select[name='branch']").value;
    const stockNum = parseInt(addForm.querySelector("input[name='stock']").value, 10);
    const unit = addForm.querySelector("select[name='unit']")?.value || "pcs";
    const min = parseInt(addForm.querySelector("input[name='min']").value, 10) || 0;
    const max = parseInt(addForm.querySelector("input[name='max']").value, 10) || 0;
    const expiry = addForm.querySelector("input[name='expiry']")?.value || "";

    if (!branch || isNaN(stockNum)) {
      alert("Please fill all required fields.");
      return;
    }

    // STOCK STATUS
    let status = getStatus(stockNum, { dataset: { min, max, expiry } });

    // EXPIRY CHECK
    let expiryStatus = "N/A";
    if (expiry) {
      const today = new Date();
      const expDate = new Date(expiry);
      if (expDate < today) {
        expiryStatus = "Expired";
        status = "Expired";
      } else {
        const diffDays = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
        expiryStatus = diffDays <= 30 ? `Near Expiry (${diffDays}d)` : `Valid (${diffDays}d)`;
      }
    }

    const stockLabel = `${stockNum} ${unit}`;
    const row = document.createElement("tr");
    row.dataset.branch = branch;
    row.dataset.status = status;
    row.dataset.date = new Date().toISOString().split("T")[0];
    row.dataset.unit = unit;
    row.dataset.min = min;
    row.dataset.max = max;
    row.dataset.expiry = expiry;

    row.innerHTML = `
      <td>
        <div class="cell-title">${name}</div>
        <div class="cell-sub">${category}</div>
      </td>
      <td>${branch}</td>
      <td>
        <div class="cell-title">${stockLabel}</div>
        <div class="cell-sub">Min: ${min} / Max: ${max}</div>
      </td>
      <td>
        <span class="badge ${status.toLowerCase().replace(" ", "-")}">${status}</span>
        <div class="cell-sub">${expiryStatus}</div>
      </td>
      <td>just now</td>
      <td class="right">
        <button class="link viewBtn"
          data-name="${name}"
          data-category="${category}"
          data-branch="${branch}"
          data-stock="${stockLabel}"
          data-unit="${unit}"
          data-min="${min}"
          data-max="${max}"
          data-status="${status}"
          data-updated="just now"
          data-expiry="${expiry}"
          data-expiryStatus="${expiryStatus}"
        >View</button>
      </td>
    `;

    invTable.appendChild(row);
    addItemModal.hidden = true;
    addForm.reset();
    if (otherInput) {
      otherInput.remove();
      otherInput = null;
    }
    filterTable();
  });

  // ---------- VIEW MODAL ----------
  const viewModal = document.getElementById("viewItemModal");
  const viewClose = document.getElementById("viewClose");
  const viewClose2 = document.getElementById("viewClose2");
  const viewPrev = document.getElementById("viewPrev");
  const viewNext = document.getElementById("viewNext");

  let viewButtons = []; // all .viewBtn elements
  let currentIndex = -1; // current item index

  function populateViewModal(btn) {
    document.getElementById("viewName").textContent = btn.dataset.name || "-";
    document.getElementById("viewCategory").textContent = btn.dataset.category || "-";
    document.getElementById("viewBranch").textContent = btn.dataset.branch || "-";
    document.getElementById("viewStock").textContent = btn.dataset.stock || "-";
    document.getElementById("viewMin").textContent = btn.dataset.min || "-";
    document.getElementById("viewMax").textContent = btn.dataset.max || "-";
    document.getElementById("viewStatus").textContent = btn.dataset.status || "-";
    document.getElementById("viewExpiry").textContent = btn.dataset.expiry || "N/A";
    document.getElementById("viewExpiryStatus").textContent = btn.dataset.expiryStatus || "N/A";
    document.getElementById("viewUpdated").textContent = btn.dataset.updated || "-";
  }

  function openViewModal(index) {
    if (index < 0 || index >= viewButtons.length) return;
    currentIndex = index;
    populateViewModal(viewButtons[currentIndex]);
    viewModal.hidden = false;
    updateNavButtons();
  }

  function updateNavButtons() {
    viewPrev.disabled = currentIndex <= 0;
    viewNext.disabled = currentIndex >= viewButtons.length - 1;
  }

  // Close modal
  [viewClose, viewClose2].forEach((btn) =>
    btn.addEventListener("click", () => (viewModal.hidden = true))
  );
  viewModal.querySelector(".backdrop").addEventListener("click", () => (viewModal.hidden = true));
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") viewModal.hidden = true;
  });

  // Event delegation for View buttons
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".viewBtn");
    if (btn) {
      e.preventDefault();
      viewButtons = Array.from(document.querySelectorAll(".viewBtn"));
      openViewModal(viewButtons.indexOf(btn));
    }
  });

  // Prev / Next navigation
  viewPrev.addEventListener("click", () => {
    if (currentIndex > 0) openViewModal(currentIndex - 1);
  });
  viewNext.addEventListener("click", () => {
    if (currentIndex < viewButtons.length - 1) openViewModal(currentIndex + 1);
  });

  // ---------- SEARCH / FILTER ----------
  function filterTable() {
    const search = (searchBox.value || "").toLowerCase();
    const branch = filterBranch.value;
    const status = filterStatus.value;
    const date = filterDate.value;

    invTable.querySelectorAll("tr").forEach((row) => {
      const name = row.querySelector(".cell-title")?.textContent.toLowerCase() || "";
      const rowBranch = row.dataset.branch;
      const rowStatus = row.dataset.status;
      const rowDate = row.dataset.date;

      let visible = true;
      if (search && !name.includes(search)) visible = false;
      if (branch !== "all" && rowBranch !== branch) visible = false;
      if (status !== "all" && rowStatus !== status) visible = false;
      if (date && rowDate !== date) visible = false;

      row.style.display = visible ? "" : "none";
    });
    updateSummary();
  }

  [searchBox, filterBranch, filterStatus, filterDate].forEach((el) => {
    el.addEventListener("input", filterTable);
    el.addEventListener("change", filterTable);
  });

  // ---------- SUMMARY PILL COUNTS ----------
  function updateSummary() {
    const rows = invTable.querySelectorAll("tr");
    let critical = 0, low = 0, good = 0, expired = 0, total = 0;

    rows.forEach((row) => {
      if (row.style.display === "none") return;
      total++;
      const status = row.dataset.status;
      if (status === "Critical") critical++;
      else if (status === "Low Stock") low++;
      else if (status === "Expired") expired++;
      else good++;
    });

    document.getElementById("pillCritical").querySelector(".pill-num").textContent = critical;
    document.getElementById("pillLow").querySelector(".pill-num").textContent = low;
    document.getElementById("pillGood").querySelector(".pill-num").textContent = good;
    document.getElementById("pillTotal").querySelector(".pill-num").textContent = total;
    if (document.getElementById("pillExpired")) {
      document.getElementById("pillExpired").querySelector(".pill-num").textContent = expired;
    }
  }

  filterTable();

  // ---------- STOCK ACTIONS ----------
  const btnUpdateStock = document.getElementById("btnUpdateStock");
  const btnReceiveDelivery = document.getElementById("btnReceiveDelivery");
  const btnReportDamage = document.getElementById("btnReportDamage");

  if (btnUpdateStock && btnReceiveDelivery && btnReportDamage) {
    btnUpdateStock.addEventListener("click", () => {
      const stockText = document.getElementById("viewStock").textContent;
      const currentStock = parseInt(stockText) || 0;
      const unit = getCurrentUnit();
      const newStock = prompt("Enter new stock quantity:", currentStock);
      if (newStock !== null && !isNaN(newStock)) {
        updateTableStock(parseInt(newStock), unit);
      }
    });

    btnReceiveDelivery.addEventListener("click", () => {
      const addQty = prompt("Enter quantity received:", 0);
      const unit = getCurrentUnit();
      if (addQty !== null && !isNaN(addQty) && addQty > 0) {
        const currentStock = parseInt(document.getElementById("viewStock").textContent) || 0;
        updateTableStock(currentStock + parseInt(addQty), unit);
      }
    });

    btnReportDamage.addEventListener("click", () => {
      const removeQty = prompt("Enter damaged/expired quantity:", 0);
      const unit = getCurrentUnit();
      if (removeQty !== null && !isNaN(removeQty) && removeQty > 0) {
        const currentStock = parseInt(document.getElementById("viewStock").textContent) || 0;
        updateTableStock(Math.max(currentStock - parseInt(removeQty), 0), unit);
      }
    });
  }

  // ---------- HELPERS ----------
  function getCurrentUnit() {
    const stockText = document.getElementById("viewStock").textContent;
    return stockText.replace(/[0-9]/g, "").trim() || "pcs";
  }

  function updateTableStock(newStock, unit) {
    const name = document.getElementById("viewName").textContent;
    const row = [...document.querySelectorAll("#invTable tbody tr")].find(
      r => r.querySelector(".cell-title")?.textContent === name
    );

    if (row) {
      row.querySelector("td:nth-child(3) .cell-title").textContent = `${newStock} ${unit}`;
      row.dataset.status = getStatus(newStock, row);
      row.querySelector(".badge").textContent = row.dataset.status;
      row.querySelector(".badge").className = `badge ${row.dataset.status.toLowerCase().replace(" ", "-")}`;

      const viewBtn = row.querySelector(".viewBtn");
      viewBtn.dataset.stock = `${newStock} ${unit}`;
      viewBtn.dataset.status = row.dataset.status;
      viewBtn.dataset.updated = "just now";
    }

    document.getElementById("viewStock").textContent = `${newStock} ${unit}`;
    document.getElementById("viewStatus").textContent = row.dataset.status;
    document.getElementById("viewUpdated").textContent = "just now";

    filterTable();
  }

  function getStatus(stock, row) {
    const min = parseInt(row.dataset.min) || 0;
    const max = parseInt(row.dataset.max) || 0;
    const expiry = row.dataset.expiry;

    if (expiry) {
      const expDate = new Date(expiry);
      const today = new Date();
      if (expDate < today) return "Expired";
    }

    if (stock <= min) return "Critical";
    if (stock <= Math.floor((min + max) / 2)) return "Low Stock";
    return "Good";
  }

  // Wait for DOM
window.addEventListener("DOMContentLoaded", () => {
  const viewItemModal = document.getElementById("viewItemModal");
  const viewClose = document.getElementById("viewClose");
  const viewClose2 = document.getElementById("viewClose2");

  // Track Branch + Check Expiry buttons
  const btnTrackBranch = document.getElementById("btnTrackBranch");
  const btnCheckExpiry = document.getElementById("btnCheckExpiry");

  // Table
  const table = document.getElementById("invTable");
  const rows = table.querySelectorAll("tbody tr");

  // Open View Modal
  document.querySelectorAll(".viewBtn").forEach(btn => {
    btn.addEventListener("click", () => {
      document.getElementById("viewName").textContent = btn.dataset.name;
      document.getElementById("viewCategory").textContent = btn.dataset.category;
      document.getElementById("viewBranch").textContent = btn.dataset.branch;
      document.getElementById("viewStock").textContent = btn.dataset.stock;
      document.getElementById("viewMin").textContent = btn.dataset.min;
      document.getElementById("viewMax").textContent = btn.dataset.max;
      document.getElementById("viewStatus").textContent = btn.dataset.status;
      document.getElementById("viewExpiry").textContent = btn.dataset.expiry;
      document.getElementById("viewUpdated").textContent = btn.dataset.updated;

      // Save branch + expiry for later
      viewItemModal.dataset.branch = btn.dataset.branch;
      viewItemModal.dataset.expiry = btn.dataset.expiry;

      viewItemModal.hidden = false;
    });
  });

  // Close View Modal
  [viewClose, viewClose2].forEach(btn => {
    btn.addEventListener("click", () => (viewItemModal.hidden = true));
  });

  // Track Branch Inventory → filter rows by branch
  btnTrackBranch.addEventListener("click", () => {
    const branch = viewItemModal.dataset.branch;
    rows.forEach(r => {
      r.style.display = r.dataset.branch === branch ? "" : "none";
    });
    viewItemModal.hidden = true;
  });

  // Check Expiry → show only expired or near expiry items
  btnCheckExpiry.addEventListener("click", () => {
    const today = new Date();
    const soon = new Date();
    soon.setDate(today.getDate() + 7); // 7 days ahead

    rows.forEach(r => {
      const expiryStr = r.querySelector("[data-expiry]")
        ? r.dataset.expiry
        : null;

      if (!expiryStr || expiryStr === "N/A") {
        r.style.display = "none";
        return;
      }

      const expiryDate = new Date(expiryStr);
      if (expiryDate <= soon) {
        r.style.display = "";
      } else {
        r.style.display = "none";
      }
    });

    viewItemModal.hidden = true;
  });
});

});
