/* INVENTORY STAFF DASHBOARD LOGIC */
(function () {
    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    let ITEMS = [];
    let FILTERED = [];

    // --------- UTILS ---------
    const nowISO = () => new Date().toISOString().slice(0, 10);
    const toISODate = val => (val ? new Date(val).toISOString().slice(0, 10) : "");
    const humanizeAgo = d => {
        if (!d) return "Unknown";
        
        const date = new Date(d);
        if (isNaN(date.getTime())) return "Invalid date";
        
        const diff = Math.max(0, (Date.now() - date.getTime()) / 1000);
        const mins = Math.floor(diff / 60);
        const hours = Math.floor(mins / 60);
        const days = Math.floor(hours / 24);
        
        if (mins < 1) return "Just now";
        if (mins < 60) return `${mins} min${mins > 1 ? "s" : ""} ago`;
        if (hours < 24) return `${hours} hour${hours > 1 ? "s" : ""} ago`;
        if (days < 7) return `${days} day${days > 1 ? "s" : ""} ago`;
        
        // For older dates, show the actual date
        return date.toLocaleDateString() + " " + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    };
    const statusOf = (qty, min, max, expiry = null) => {
        qty = Number(qty || 0); 
        min = Number(min || 0); 
        max = Number(max || 0);
        
        // Check if expired
        if (expiry) {
            const expiryDate = new Date(expiry);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time to start of day
            if (expiryDate < today) {
                return "Expired";
            }
            
            // Check if expiring soon (within 7 days)
            const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
            if (daysUntilExpiry <= 7) {
                return "Expiring Soon";
            }
        }
        
        // Check stock levels with improved logic
        if (qty <= 0) return "Out of Stock";
        
        // If min and max are the same, treat as good if stock equals that value
        if (min === max) {
            if (qty === min) return "Good";
            if (qty < min) return "Critical";
            return "Good"; // Stock is above the set level
        }
        
        // Calculate the range between min and max
        const range = max - min;
        
        // If stock is at or below minimum, it's critical
        if (qty <= min) return "Critical";
        
        // If stock is in the lower 30% of the range, it's low stock
        if (qty < min + (range * 0.3)) return "Low Stock";
        
        // If stock is at or near maximum, it's good
        if (qty >= max * 0.8) return "Good";
        
        // If stock is in the middle range, it's good
        return "Good";
    };
    const statusClass = s => ({ 
        "Critical": "critical", 
        "Low Stock": "low-stock", 
        "Good": "good",
        "Out of Stock": "critical",
        "Expired": "critical",
        "Expiring Soon": "low-stock"
    }[s] || "neutral");

    // --------- RENDERING ---------
    function renderTable(list) {
        const tbody = $("#invBody");
        if (!tbody) {
            console.error('Table body not found');
            return;
        }
        
        if (!list || list.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #999;">No inventory items found</td></tr>';
            renderCounts([]);
            return;
        }
        
        tbody.innerHTML = list.map(item => {
            // Ensure status is properly set
            const status = (item.status || 'Good').trim();
            const statusCls = statusClass(status);
            const statusBadge = status ? `<span class="badge ${statusCls}">${status}</span>` : '<span class="badge neutral">-</span>';
            
            const stockQty = parseInt(item.stock_qty || 0, 10);
            const minStock = parseInt(item.min_stock || 0, 10);
            const maxStock = parseInt(item.max_stock || 0, 10);
            const unit = item.unit || 'pcs';
            
            return `
            <tr data-id="${item.id}" data-branch="${item.branch_id || ''}" data-status="${status}" data-date="${item.date || ''}" data-stockqty="${stockQty}" data-unit="${unit}" data-expiry="${item.expiry || ''}">
                <td><div class="cell-title" style="font-weight: 600; color: #2d5016;">${item.name || 'N/A'}</div><div class="cell-sub" style="color: #6b7280; font-size: 0.875rem;">${item.category || 'N/A'}</div></td>
                <td style="font-weight: 500;">${item.branch_label || item.branch_name || 'N/A'}</td>
                <td><div class="cell-title" style="font-weight: 600; color: #2d5016;">${stockQty.toLocaleString()} ${unit}</div><div class="cell-sub" style="color: #6b7280; font-size: 0.875rem;">Min: ${minStock.toLocaleString()} / Max: ${maxStock.toLocaleString()}</div></td>
                <td>${statusBadge}</td>
                <td style="color: #6b7280;">${item.updated_ago || 'Unknown'}</td>
                <td class="right"><button class="btn btn-sm btn-info viewBtn" data-id="${item.id}" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease;">View</button></td>
            </tr>
            `;
        }).join("");
        
        // Render counts after table is rendered
        renderCounts(list);
    }

    function renderCounts(list) {
        const counts = { critical: 0, low: 0, good: 0, total: list.length };
        
        // Count items by status - be very explicit about status matching
        list.forEach(i => {
            const status = String(i.status || '').trim();
            
            // Check for critical statuses (case-sensitive)
            if (status === "Critical" || status === "Out of Stock" || status === "Expired") {
                counts.critical++;
            } 
            // Check for low stock statuses
            else if (status === "Low Stock" || status === "Expiring Soon") {
                counts.low++;
            } 
            // Everything else (including "Good" and empty) counts as good
            else {
                counts.good++;
            }
        });
        
        // Update summary cards - IDs are directly on the summary-value divs
        const pillCritical = document.getElementById('pillCritical');
        const pillLow = document.getElementById('pillLow');
        const pillGood = document.getElementById('pillGood');
        const pillTotal = document.getElementById('pillTotal');
        
        if (pillCritical) {
            pillCritical.textContent = String(counts.critical);
            console.log('Updated pillCritical:', counts.critical);
        } else {
            console.error('pillCritical element not found');
        }
        
        if (pillLow) {
            pillLow.textContent = String(counts.low);
            console.log('Updated pillLow:', counts.low);
        } else {
            console.error('pillLow element not found');
        }
        
        if (pillGood) {
            pillGood.textContent = String(counts.good);
            console.log('Updated pillGood:', counts.good);
        } else {
            console.error('pillGood element not found');
        }
        
        if (pillTotal) {
            pillTotal.textContent = String(counts.total);
            console.log('Updated pillTotal:', counts.total);
        } else {
            console.error('pillTotal element not found');
        }
        
        // Also update item count
        const itemCount = document.getElementById('itemCount');
        if (itemCount) {
            itemCount.textContent = `${counts.total} item${counts.total !== 1 ? 's' : ''}`;
        }
        
        // Debug logging
        console.log('=== Summary Counts ===');
        console.log('Total items in list:', list.length);
        console.log('Critical:', counts.critical);
        console.log('Low Stock:', counts.low);
        console.log('Good Stock:', counts.good);
        console.log('Total:', counts.total);
        
        // Log status breakdown for debugging
        const statusBreakdown = {};
        list.forEach(i => {
            const status = String(i.status || 'Unknown').trim();
            statusBreakdown[status] = (statusBreakdown[status] || 0) + 1;
        });
        console.log('Status breakdown:', statusBreakdown);
    }

    // --------- FILTERING ---------
    function applyFilters() {
        const q = ($("#searchBox").value || "").toLowerCase().trim();
        const branch = ($("#filterBranch") ? $("#filterBranch").value : 'all') || 'all';
        const status = $("#filterStatus").value;
        const dateVal = $("#filterDate").value;

        FILTERED = ITEMS.filter(it => {
            if (branch !== "all") {
                if (String(it.branch_id ?? '') !== branch) return false;
            }
            if (status !== "all" && it.status !== status) return false;
            if (dateVal && toISODate(it.date) !== dateVal) return false;
            if (!q) return true;

            const blob = `${it.name} ${it.category} ${it.branch_label || ''}`.toLowerCase();
            return blob.includes(q);
        });

        renderTable(FILTERED);
    }

    // --------- MODALS ---------
    const addModal = $("#addItemModal");
    const viewModal = $("#viewItemModal");

    $('#btnAdd')?.addEventListener("click", () => addModal.hidden = false);
    $('#addClose')?.addEventListener("click", () => addModal.hidden = true);
    $('#addClose2')?.addEventListener("click", () => addModal.hidden = true);

    // --------- VIEW ITEM DETAILS ---------
    function openViewModal(itemId) {
        const item = ITEMS.find(x => x.id == itemId);
        if (!item) {
            alert('Item not found');
            return;
        }

        const status = item.status || 'Good';
        const statusCls = statusClass(status);
        
        // Update modal content
        const viewItemTitle = $('#viewItemTitle');
        const viewCategory = $('#viewCategory');
        const viewBranch = $('#viewBranch');
        const viewStock = $('#viewStock');
        const viewMinMax = $('#viewMinMax');
        const viewStatus = $('#viewStatus');
        const viewUpdated = $('#viewUpdated');
        const viewExpiry = $('#viewExpiry');
        const viewPrice = $('#viewPrice');
        
        if (viewItemTitle) viewItemTitle.textContent = item.name || 'N/A';
        if (viewCategory) viewCategory.textContent = item.category || 'N/A';
        if (viewBranch) viewBranch.textContent = item.branch_label || item.branch_name || 'N/A';
        if (viewStock) viewStock.textContent = (item.stock_qty || 0) + ' ' + (item.unit || '');
        if (viewMinMax) viewMinMax.textContent = `Min: ${item.min_stock || 0} / Max: ${item.max_stock || 0}`;
        if (viewPrice) viewPrice.textContent = item.price ? '₱' + parseFloat(item.price).toFixed(2) : 'N/A';
        
        // Update status with badge class
        if (viewStatus) {
            viewStatus.textContent = status;
            viewStatus.className = 'badge ' + statusCls;
        }
        
        if (viewUpdated) viewUpdated.textContent = item.updated_ago || 'Unknown';
        if (viewExpiry) viewExpiry.textContent = item.expiry || 'N/A';

        const updateStockContainer = $('#updateStockContainer');
        const updateStockInput = $('#updateStockInput');
        if (updateStockContainer) updateStockContainer.style.display = 'none';
        if (updateStockInput) updateStockInput.value = item.stock_qty || 0;

        if (viewModal) {
            viewModal.hidden = false;
            viewModal.style.display = 'flex';
            viewModal.dataset.currentId = itemId;
        }
    }

    document.addEventListener('click', e => {
        if (e.target.classList.contains('viewBtn')) openViewModal(e.target.dataset.id);
    });

    $('#viewClose')?.addEventListener('click', () => {
        if (viewModal) {
            viewModal.hidden = true;
            viewModal.style.display = 'none';
        }
    });
    
    // Close modal when clicking backdrop
    const viewBackdrop = viewModal?.querySelector('.backdrop');
    if (viewBackdrop) {
        viewBackdrop.addEventListener('click', () => {
            if (viewModal) {
                viewModal.hidden = true;
                viewModal.style.display = 'none';
            }
        });
    }

    // --------- STOCK ACTIONS ---------
    $('#btnUpdateStock').addEventListener('click', () => $('#updateStockContainer').style.display = 'flex');
    $('#cancelStockBtn').addEventListener('click', () => $('#updateStockContainer').style.display = 'none');
    $('#saveStockBtn').addEventListener('click', () => {
        const newQty = Number($('#updateStockInput').value);
        const id = viewModal.dataset.currentId;
        if (isNaN(newQty)) return alert('Invalid stock value');

        fetch('/staff/updateStock/' + id, {
            method:'POST',
            headers:{ 'Content-Type':'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ stock_qty: newQty })
        }).then(r => r.json()).then(data => {
            if (data.status === 'success') location.reload();
            else alert(data.error || 'Failed to update stock');
        });
    });

    // --------- ACTION BUTTONS (wired to backend) ---------
    $('#btnReceiveDelivery').addEventListener('click', () => {
        const id = viewModal.dataset.currentId;
        const qty = prompt('Enter quantity received:');
        const n = Number(qty);
        if (!qty || isNaN(n) || n <= 0) return alert('Invalid quantity');
        fetch('/staff/receiveDelivery/' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ quantity: n })
        }).then(r => r.json()).then(data => {
            if (data.status === 'success') location.reload();
            else alert(data.error || 'Failed to receive delivery');
        }).catch(e => alert(e.message));
    });

    $('#btnReportDamaged').addEventListener('click', () => {
        const id = viewModal.dataset.currentId;
        const qty = prompt('Enter damaged quantity:');
        const n = Number(qty);
        if (!qty || isNaN(n) || n <= 0) return alert('Invalid quantity');
        fetch('/staff/reportDamage/' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ quantity: n })
        }).then(r => r.json()).then(data => {
            if (data.status === 'success') location.reload();
            else alert(data.error || 'Failed to report damage');
        }).catch(e => alert(e.message));
    });

    $('#btnTrackInventory').addEventListener('click', () => {
        // Apply branch filter for current item's branch
        const id = viewModal.dataset.currentId;
        const item = ITEMS.find(x => String(x.id) === String(id));
        if (!item || !item.branch_id) return alert('No branch for this item');
        window.location.href = '/inventory?branch_id=' + encodeURIComponent(item.branch_id);
    });

    $('#btnCheckExpiry').addEventListener('click', () => {
        const id = viewModal.dataset.currentId;
        fetch('/inventory/expiry/' + id)
            .then(r => r.json())
            .then(data => {
                if (data.status !== 'success') return alert(data.error || 'Failed to check expiry');
                const msg = data.expiry
                    ? `State: ${data.state}\nExpiry: ${data.expiry}\nDays remaining: ${data.days}`
                    : `State: ${data.state}`;
                alert(msg);
            }).catch(e => alert(e.message));
    });

    // --------- ADD PRODUCT ---------
    $('#addForm').addEventListener('submit', e => {
        e.preventDefault();

        const form = $('#addForm');
        const formData = new FormData(form);

        console.log('Form data:', Object.fromEntries(formData));

        fetch('/staff/addProduct', {
            method:'POST',
            headers:{ 'Content-Type':'application/x-www-form-urlencoded' },
            body: new URLSearchParams(formData)
        }).then(r => {
            console.log('Response status:', r.status);
            if (!r.ok) {
                throw new Error(`HTTP error! status: ${r.status}`);
            }
            return r.json();
        }).then(data => {
            console.log('Response data:', data);
            if (data.status === 'success') {
                alert('Product added successfully!');
                location.reload(); // Reload to see the new product
            } else {
                alert('Error: ' + (data.error || 'Failed to add product'));
            }
        }).catch(err => {
            console.error('Add product request failed', err);
            alert('Server error: ' + err.message);
        });
    });

    // --------- INIT ---------
    function init() {
        const script = $("#initial-items");
        if(script) {
            try { ITEMS = JSON.parse(script.textContent || "[]"); } catch { ITEMS = []; }
        }

        ITEMS = ITEMS.map(i => {
            // Use status from server (calculated by ProductModel) if available, otherwise calculate
            const serverStatus = (i.status || '').trim();
            const calculatedStatus = statusOf(i.stock_qty || 0, i.min_stock, i.max_stock, i.expiry);
            
            // Validate server status against known statuses
            const validStatuses = ['Good', 'Low Stock', 'Critical', 'Out of Stock', 'Expired', 'Expiring Soon'];
            const finalStatus = serverStatus && validStatuses.includes(serverStatus) 
                ? serverStatus 
                : (calculatedStatus || 'Good');
            
            // Ensure accurate numeric values
            const stockQty = parseInt(i.stock_qty || 0, 10);
            const minStock = parseInt(i.min_stock || 0, 10);
            const maxStock = parseInt(i.max_stock || 0, 10);
            const price = parseFloat(i.price || 0);
            
            const processedItem = {
                ...i,
                branch_label: i.branch_label || i.branch_name || i.branch_address || '',
                branch_id: i.branch_id || null,
                stock_qty: stockQty,
                min_stock: minStock,
                max_stock: maxStock,
                price: price,
                status: finalStatus,
                updated_at: i.updated_at || i.created_at || new Date().toISOString(),
                updated_ago: i.updated_ago || i.created_ago || humanizeAgo(i.updated_at || i.created_at || new Date())
            };
            
            // Debug log for first item
            if (ITEMS.indexOf(i) === 0) {
                console.log('Sample processed item:', {
                    id: processedItem.id,
                    name: processedItem.name,
                    status: processedItem.status,
                    serverStatus: serverStatus,
                    calculatedStatus: calculatedStatus,
                    finalStatus: finalStatus
                });
            }
            
            return processedItem;
        });

        const urlParams = new URLSearchParams(window.location.search);
        const branchParam = urlParams.get('branch_id') || urlParams.get('branch');
        if (branchParam) {
            const branchSelect = $("#filterBranch");
            if(branchSelect) branchSelect.value = branchParam;
        }

        applyFilters();
    }

    // --------- FILTER EVENTS ---------
    $("#searchBox")?.addEventListener("input", debounce(applyFilters, 150));
    $("#filterBranch")?.addEventListener("change", applyFilters);
    $("#filterStatus")?.addEventListener("change", applyFilters);
    $("#filterDate")?.addEventListener("change", applyFilters);

    // --------- HELPERS ---------
    function debounce(fn, ms) { let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn.apply(null, args), ms); }; }

    document.addEventListener("DOMContentLoaded", init);

})();