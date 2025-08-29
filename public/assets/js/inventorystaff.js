/* INVENTORY STAFF DASHBOARD LOGIC */

(function () {
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  // --------- STATE ---------
  let ITEMS = [];
  let FILTERED = [];
  let currentViewIndex = -1; // index in FILTERED for View modal

  // --------- UTILS ---------
  const nowISO = () => new Date().toISOString().slice(0, 10);
  const toISODate = (val) => (val ? new Date(val).toISOString().slice(0, 10) : "");
  const humanizeAgo = (d) => {
    const date = new Date(d);
    const diff = Math.max(0, (Date.now() - date.getTime()) / 1000);
    const mins = Math.floor(diff / 60);
    const hours = Math.floor(mins / 60);
    const days = Math.floor(hours / 24);
    if (mins < 1) return "just now";
    if (mins < 60) return `${mins} min${mins>1?"s":""} ago`;
    if (hours < 24) return `${hours} hour${hours>1?"s":""} ago`;
    return `${days} day${days>1?"s":""} ago`;
  };
  const statusOf = (qty, min, max) => {
    qty = Number(qty || 0);
    min = Number(min || 0);
    max = Number(max || 0);
    if (qty <= min) return "Critical";
    const threshold = min + (max - min) * 0.30;
    if (qty < threshold) return "Low Stock";
    return "Good";
  };
  const statusClass = (s) => {
    const map = { "Critical": "critical", "Low Stock": "low-stock", "Good": "good" };
    return map[s] || "neutral";
  };
  const nextId = () => (ITEMS.length ? Math.max(...ITEMS.map(i => i.id)) + 1 : 1);

  // --------- RENDERING ---------
  function renderTable(list) {
    const tbody = $("#invBody");
    tbody.innerHTML = list.map(item => {
      const stockLabel = `${item.stock_qty} ${item.unit}`;
      return `
        <tr data-id="${item.id}"
            data-branch="${item.branch}"
            data-status="${item.status}"
            data-date="${item.date}"
            data-stockqty="${item.stock_qty}"
            data-unit="${item.unit}"
            data-expiry="${item.expiry || ''}">
          <td>
            <div class="cell-title">${escapeHtml(item.name)}</div>
            <div class="cell-sub">${escapeHtml(item.category)}</div>
          </td>
          <td>${escapeHtml(item.branch)}</td>
          <td>
            <div class="cell-title">${stockLabel}</div>
            <div class="cell-sub">Min: ${item.min} / Max: ${item.max}</div>
          </td>
          <td><span class="badge ${statusClass(item.status)}">${item.status}</span></td>
          <td>${item.updated_ago || humanizeAgo(item.updated_at || new Date())}</td>
          <td class="right">
            <button class="link viewBtn"
              data-id="${item.id}"
              data-name="${escapeHtml(item.name)}"
              data-category="${escapeHtml(item.category)}"
              data-branch="${escapeHtml(item.branch)}"
              data-stock="${stockLabel}"
              data-min="${item.min}"
              data-max="${item.max}"
              data-status="${item.status}"
              data-updated="${item.updated_ago || humanizeAgo(item.updated_at || new Date())}"
              data-expiry="${item.expiry || 'N/A'}"
            >View</button>
          </td>
        </tr>`;
    }).join("");

    // bind view buttons
    $$("#invBody .viewBtn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = Number(btn.dataset.id);
        currentViewIndex = FILTERED.findIndex(i => i.id === id);
        openViewModalOf(FILTERED[currentViewIndex]);
      });
    });

    renderCounts(list);
  }

  function renderCounts(list) {
    const counts = { critical: 0, low: 0, good: 0, total: list.length };
    list.forEach(i => {
      if (i.status === "Critical") counts.critical++;
      else if (i.status === "Low Stock") counts.low++;
      else counts.good++;
    });
    $("#pillCritical .pill-num").textContent = counts.critical;
    $("#pillLow .pill-num").textContent = counts.low;
    $("#pillGood .pill-num").textContent = counts.good;
    $("#pillTotal .pill-num").textContent = counts.total;
  }

  // --------- FILTERING ---------
  function applyFilters() {
    const q = ($("#searchBox").value || "").toLowerCase().trim();
    const branch = $("#filterBranch").value;
    const status = $("#filterStatus").value;
    const dateVal = $("#filterDate").value; // YYYY-MM-DD or ""

    FILTERED = ITEMS.filter(it => {
      if (branch !== "all" && it.branch !== branch) return false;
      if (status !== "all" && it.status !== status) return false;
      if (dateVal && toISODate(it.date) !== dateVal) return false;

      if (!q) return true;
      const blob = `${it.name} ${it.category} ${it.branch}`.toLowerCase();
      return blob.includes(q);
    });

    renderTable(FILTERED);
  }

  // --------- MODALS HELPERS ---------
  const addModal = $("#addItemModal");
  const viewModal = $("#viewItemModal");
  const updateModal = $("#updateStockModal");
  const trackModal = $("#trackBranchModal");
  const expiryModal = $("#expiryCheckModal");

  const open = (m) => { if (m) m.hidden = false; };
  const close = (m) => { if (m) m.hidden = true; };

  // --------- VIEW MODAL ---------
  function openViewModalOf(item) {
    $("#viewName").textContent = item.name;
    $("#viewCategory").textContent = item.category;
    $("#viewBranch").textContent = item.branch;
    $("#viewStock").textContent = `${item.stock_qty} ${item.unit}`;
    $("#viewMin").textContent = item.min;
    $("#viewMax").textContent = item.max;
    $("#viewStatus").textContent = item.status;
    $("#viewUpdated").textContent = item.updated_ago || humanizeAgo(item.updated_at || new Date());
    $("#viewExpiry").textContent = item.expiry || "N/A";

    // expiry status
    let expStatus = "N/A";
    if (item.expiry) {
      const expDate = new Date(item.expiry);
      expStatus = expDate < new Date() ? "❌ Expired" : "✅ Valid";
    }
    $("#viewExpiryStatus").textContent = expStatus;

    open(viewModal);
  }

  // Prev/Next navigation
  $("#viewPrev").addEventListener("click", () => {
    if (FILTERED.length === 0) return;
    currentViewIndex = (currentViewIndex - 1 + FILTERED.length) % FILTERED.length;
    openViewModalOf(FILTERED[currentViewIndex]);
  });
  $("#viewNext").addEventListener("click", () => {
    if (FILTERED.length === 0) return;
    currentViewIndex = (currentViewIndex + 1) % FILTERED.length;
    openViewModalOf(FILTERED[currentViewIndex]);
  });

  // Close buttons
  $("#viewClose").addEventListener("click", () => close(viewModal));
  $("#viewClose2").addEventListener("click", () => close(viewModal));
  $("#updateClose").addEventListener("click", () => close(updateModal));
  $("#updateClose2").addEventListener("click", () => close(updateModal));
  $("#trackClose").addEventListener("click", () => close(trackModal));
  $("#trackClose2").addEventListener("click", () => close(trackModal));
  $("#expiryClose").addEventListener("click", () => close(expiryModal));
  $("#expiryClose2").addEventListener("click", () => close(expiryModal));

  // --------- ADD NEW ITEM ---------
  $("#btnAdd").addEventListener("click", () => open(addModal));
  $("#addClose").addEventListener("click", () => close(addModal));
  $("#addClose2").addEventListener("click", () => close(addModal));

  $("#addForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const form = e.target;
    const name = form.elements["name"].value.trim();
    const category = form.elements["category"].value.trim();
    const branch = form.elements["branch"].value;
    const stock_qty = Number(form.elements["stock"].value);
    const unit = form.elements["unit"].value;
    const min = Number(form.elements["min"].value);
    const max = Number(form.elements["max"].value);
    const expiry = form.elements["expiry"].value ? toISODate(form.elements["expiry"].value) : "";

    if (!name || !branch || !stock_qty || !min || !max) {
      alert("Please fill all required fields.");
      return;
    }
    const s = statusOf(stock_qty, min, max);
    const item = {
      id: nextId(),
      name, category, branch,
      stock_qty, unit, min, max,
      status: s,
      updated_at: new Date().toISOString(),
      updated_ago: "just now",
      date: nowISO(),
      expiry
    };
    ITEMS.push(item);
    applyFilters();
    close(addModal);
    form.reset();
  });

  // --------- UPDATE STOCK ---------
  $("#btnUpdateStock").addEventListener("click", () => {
    if (currentViewIndex < 0) return;
    const it = FILTERED[currentViewIndex];
    $("#updateId").value = it.id;
    $("#updateStock").value = it.stock_qty;
    open(updateModal);
  });

  $("#updateStockForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const id = Number($("#updateId").value);
    const newQty = Number($("#updateStock").value);
    const idx = ITEMS.findIndex(i => i.id === id);
    if (idx >= 0) {
      ITEMS[idx].stock_qty = newQty;
      ITEMS[idx].status = statusOf(newQty, ITEMS[idx].min, ITEMS[idx].max);
      ITEMS[idx].updated_at = new Date().toISOString();
      ITEMS[idx].updated_ago = "just now";
      applyFilters();
      // keep view modal in sync
      currentViewIndex = FILTERED.findIndex(i => i.id === id);
      if (currentViewIndex >= 0) openViewModalOf(FILTERED[currentViewIndex]);
      close(updateModal);
    }
  });

  // --------- RECEIVE DELIVERY (simple prompt add) ---------
  $("#btnReceiveDelivery").addEventListener("click", () => {
    if (currentViewIndex < 0) return;
    const amt = Number(prompt("Quantity received:", "0") || "0");
    if (amt <= 0) return;
    const it = FILTERED[currentViewIndex];
    const idx = ITEMS.findIndex(i => i.id === it.id);
    ITEMS[idx].stock_qty += amt;
    ITEMS[idx].status = statusOf(ITEMS[idx].stock_qty, ITEMS[idx].min, ITEMS[idx].max);
    ITEMS[idx].updated_at = new Date().toISOString();
    ITEMS[idx].updated_ago = "just now";
    applyFilters();
    currentViewIndex = FILTERED.findIndex(i => i.id === it.id);
    openViewModalOf(FILTERED[currentViewIndex]);
  });

  // --------- REPORT DAMAGED/EXPIRED (simple prompt subtract or mark expired) ---------
  $("#btnReportDamage").addEventListener("click", () => {
    if (currentViewIndex < 0) return;
    const choice = prompt("Type quantity to subtract, or type 'expired' to mark expired:", "");
    if (!choice) return;

    const it = FILTERED[currentViewIndex];
    const idx = ITEMS.findIndex(i => i.id === it.id);

    if (choice.toLowerCase() === "expired") {
      ITEMS[idx].expiry = toISODate(new Date()); // mark as expired today
    } else {
      const qty = Math.max(0, Number(choice) || 0);
      ITEMS[idx].stock_qty = Math.max(0, ITEMS[idx].stock_qty - qty);
      ITEMS[idx].status = statusOf(ITEMS[idx].stock_qty, ITEMS[idx].min, ITEMS[idx].max);
    }
    ITEMS[idx].updated_at = new Date().toISOString();
    ITEMS[idx].updated_ago = "just now";
    applyFilters();
    currentViewIndex = FILTERED.findIndex(i => i.id === it.id);
    openViewModalOf(FILTERED[currentViewIndex]);
  });

  // --------- TRACK BRANCH INVENTORY ---------
  $("#btnTrackBranch").addEventListener("click", () => {
    if (currentViewIndex < 0) return;
    const branch = FILTERED[currentViewIndex].branch;
    const list = ITEMS.filter(i => i.branch === branch);
    const html = `
      <h4>Items at ${escapeHtml(branch)}</h4>
      ${list.map(i => `<p>${escapeHtml(i.name)} — ${i.stock_qty} ${i.unit} (${i.status})</p>`).join("")}
    `;
    $("#branchInventoryList").innerHTML = html;
    open(trackModal);
  });

  // --------- CHECK EXPIRY ---------
  $("#btnCheckExpiry").addEventListener("click", () => {
    if (currentViewIndex < 0) return;
    const it = FILTERED[currentViewIndex];
    let msg = "No expiry set.";
    if (it.expiry) {
      const exp = new Date(it.expiry);
      msg = exp < new Date() ? `❌ Item expired on ${toISODate(exp)}` : `✅ Valid until ${toISODate(exp)}`;
    }
    $("#expiryCheckBody").textContent = msg;
    open(expiryModal);
  });

  // --------- FILTER EVENTS ---------
  $("#filterBranch").addEventListener("change", applyFilters);
  $("#filterStatus").addEventListener("change", applyFilters);
  $("#filterDate").addEventListener("change", applyFilters);
  $("#searchBox").addEventListener("input", debounce(applyFilters, 150));

  // --------- INIT ---------
  function init() {
    // prefer embedded JSON, fallback to parsing existing rows
    const script = $("#initial-items");
    if (script) {
      try { ITEMS = JSON.parse(script.textContent || "[]"); } catch { ITEMS = []; }
    }
    if (!ITEMS.length) {
      ITEMS = $$("#invBody tr").map(tr => ({
        id: Number(tr.dataset.id),
        name: tr.querySelector(".cell-title")?.textContent?.trim() || "",
        category: tr.querySelector(".cell-sub")?.textContent?.trim() || "",
        branch: tr.dataset.branch,
        stock_qty: Number(tr.dataset.stockqty || 0),
        unit: tr.dataset.unit || "pcs",
        min: Number(tr.querySelector("td:nth-child(3) .cell-sub")?.textContent?.match(/Min:\s*(\d+)/)?.[1] || 0),
        max: Number(tr.querySelector("td:nth-child(3) .cell-sub")?.textContent?.match(/Max:\s*(\d+)/)?.[1] || 0),
        status: tr.dataset.status,
        updated_ago: tr.children[4]?.textContent?.trim() || "",
        date: tr.dataset.date || nowISO(),
        expiry: tr.dataset.expiry || ""
      }));
    }

    // normalize computed fields
    ITEMS = ITEMS.map(i => ({
      ...i,
      status: statusOf(i.stock_qty, i.min, i.max),
      date: toISODate(i.date) || nowISO(),
      updated_at: i.updated_at || new Date().toISOString()
    }));

    applyFilters();
  }

  // helpers
  function debounce(fn, ms) {
    let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn.apply(null, args), ms); };
  }
  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
  }

  // go!
  document.addEventListener("DOMContentLoaded", init);
})();
