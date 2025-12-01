/* INVENTORY STAFF DASHBOARD LOGIC */
(function () {
    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    let ITEMS = [];
    let FILTERED = [];
    
    // Pagination settings
    let currentPage = 1;
    const ITEMS_PER_PAGE = 10;

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
            renderPagination(0);
            return;
        }
        
        // Calculate pagination
        const totalItems = list.length;
        const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
        
        // Ensure current page is valid
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;
        
        // Get items for current page
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const pageItems = list.slice(startIndex, endIndex);
        
        tbody.innerHTML = pageItems.map(item => {
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
        
        // Render counts after table is rendered (use full list for accurate counts)
        renderCounts(list);
        
        // Render pagination controls
        renderPagination(totalItems);
    }
    
    function renderPagination(totalItems) {
        const paginationContainer = $("#paginationContainer");
        if (!paginationContainer) return;
        
        if (totalItems === 0) {
            paginationContainer.innerHTML = '';
            return;
        }
        
        const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
        const startItem = (currentPage - 1) * ITEMS_PER_PAGE + 1;
        const endItem = Math.min(currentPage * ITEMS_PER_PAGE, totalItems);
        
        let paginationHTML = `
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing <strong>${startItem}-${endItem}</strong> of <strong>${totalItems}</strong> items
                </div>
                <div class="pagination-controls">
        `;
        
        // Previous button
        paginationHTML += `
            <button class="pagination-btn ${currentPage === 1 ? 'disabled' : ''}" 
                    data-page="${currentPage - 1}" 
                    ${currentPage === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left"></i> Prev
            </button>
        `;
        
        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        if (startPage > 1) {
            paginationHTML += `<button class="pagination-btn" data-page="1">1</button>`;
            if (startPage > 2) {
                paginationHTML += `<span class="pagination-ellipsis">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <button class="pagination-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>
            `;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHTML += `<span class="pagination-ellipsis">...</span>`;
            }
            paginationHTML += `<button class="pagination-btn" data-page="${totalPages}">${totalPages}</button>`;
        }
        
        // Next button
        paginationHTML += `
            <button class="pagination-btn ${currentPage === totalPages ? 'disabled' : ''}" 
                    data-page="${currentPage + 1}" 
                    ${currentPage === totalPages ? 'disabled' : ''}>
                Next <i class="fas fa-chevron-right"></i>
            </button>
        `;
        
        paginationHTML += `
                </div>
            </div>
        `;
        
        paginationContainer.innerHTML = paginationHTML;
        
        // Add event listeners for pagination buttons
        $$('.pagination-btn:not(.disabled)', paginationContainer).forEach(btn => {
            btn.addEventListener('click', (e) => {
                const page = parseInt(e.currentTarget.dataset.page);
                if (page && page !== currentPage) {
                    currentPage = page;
                    renderTable(FILTERED);
                    // Scroll to top of table
                    const table = $("#invTable");
                    if (table) {
                        table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
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

        // Reset to page 1 when filters change
        currentPage = 1;
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
    // Get base URL from page or default
    const getBaseUrl = () => {
        // Try to get from meta tag or script
        const baseUrlMeta = document.querySelector('meta[name="base-url"]');
        if (baseUrlMeta) return baseUrlMeta.content;
        
        // Try to extract from current URL
        const pathParts = window.location.pathname.split('/');
        // Check if we're in a subdirectory (e.g., /ChakaNoks-SCMS-/)
        if (pathParts.length > 1 && pathParts[1].includes('SCMS')) {
            return '/' + pathParts[1] + '/';
        }
        // Check for index.php pattern
        const indexPhpMatch = window.location.href.match(/(.*\/index\.php)/);
        if (indexPhpMatch) {
            return indexPhpMatch[1] + '/';
        }
        return '/';
    };
    
    const baseUrl = getBaseUrl();
    
    $('#btnUpdateStock')?.addEventListener('click', () => {
        const container = $('#updateStockContainer');
        if (!container) return;
        
        if (container.style.display === 'none' || !container.style.display) {
            container.style.display = 'flex';
            // Pre-fill with current stock value
            const stockText = $('#viewStock')?.textContent || '0';
            const currentStock = parseInt(stockText.split(' ')[0]) || 0;
            const input = $('#updateStockInput');
            if (input) input.value = currentStock;
        } else {
            container.style.display = 'none';
        }
    });
    
    $('#cancelStockBtn')?.addEventListener('click', () => {
        const container = $('#updateStockContainer');
        if (container) container.style.display = 'none';
    });
    
    $('#saveStockBtn')?.addEventListener('click', () => {
        const input = $('#updateStockInput');
        const newQty = parseInt(input?.value, 10);
        const id = viewModal?.dataset?.currentId;
        
        if (!id) {
            alert('Product ID not found. Please close and reopen the item.');
            return;
        }
        
        if (isNaN(newQty) || newQty < 0) {
            alert('Please enter a valid stock quantity (0 or greater)');
            return;
        }

        // Show loading state
        const saveBtn = $('#saveStockBtn');
        const originalText = saveBtn?.textContent;
        if (saveBtn) {
            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;
        }

        // Build the URL properly
        const updateUrl = baseUrl + 'staff/updateStock/' + id;
        console.log('Updating stock at:', updateUrl, 'with qty:', newQty);

        fetch(updateUrl, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({ stock_qty: newQty })
        })
        .then(r => {
            if (!r.ok) {
                throw new Error(`HTTP error! status: ${r.status}`);
            }
            return r.json();
        })
        .then(data => {
            if (data.status === 'success') {
                alert('Stock updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.error || data.message || 'Failed to update stock'));
            }
        })
        .catch(e => {
            console.error('Stock update error:', e);
            alert('Error updating stock: ' + e.message);
        })
        .finally(() => {
            if (saveBtn) {
                saveBtn.textContent = originalText || 'Save';
                saveBtn.disabled = false;
            }
        });
    });

    // --------- ACTION BUTTONS (wired to backend) ---------
    $('#btnReceiveDelivery')?.addEventListener('click', () => {
        const id = viewModal?.dataset?.currentId;
        if (!id) return alert('Product ID not found');
        
        const qty = prompt('Enter quantity received:');
        const n = Number(qty);
        if (!qty || isNaN(n) || n <= 0) return alert('Invalid quantity');
        
        fetch(baseUrl + 'staff/receiveDelivery/' + id, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({ quantity: n })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Delivery received! Stock updated.');
                location.reload();
            } else {
                alert(data.error || 'Failed to receive delivery');
            }
        })
        .catch(e => alert('Error: ' + e.message));
    });

    $('#btnReportDamaged')?.addEventListener('click', () => {
        const id = viewModal?.dataset?.currentId;
        if (!id) return alert('Product ID not found');
        
        const qty = prompt('Enter damaged quantity:');
        const n = Number(qty);
        if (!qty || isNaN(n) || n <= 0) return alert('Invalid quantity');
        
        fetch(baseUrl + 'staff/reportDamage/' + id, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({ quantity: n })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Damage reported! Stock updated.');
                location.reload();
            } else {
                alert(data.error || 'Failed to report damage');
            }
        })
        .catch(e => alert('Error: ' + e.message));
    });

    $('#btnTrackInventory')?.addEventListener('click', () => {
        const id = viewModal?.dataset?.currentId;
        const item = ITEMS.find(x => String(x.id) === String(id));
        if (!item || !item.branch_id) return alert('No branch for this item');
        window.location.href = baseUrl + 'inventory?branch_id=' + encodeURIComponent(item.branch_id);
    });

    $('#btnCheckExpiry')?.addEventListener('click', () => {
        const id = viewModal?.dataset?.currentId;
        if (!id) return alert('Product ID not found');
        
        fetch(baseUrl + 'inventory/expiry/' + id)
            .then(r => r.json())
            .then(data => {
                if (data.status !== 'success') return alert(data.error || 'Failed to check expiry');
                const msg = data.expiry
                    ? `State: ${data.state}\nExpiry: ${data.expiry}\nDays remaining: ${data.days}`
                    : `State: ${data.state}`;
                alert(msg);
            })
            .catch(e => alert('Error: ' + e.message));
    });

    // Print Report button
    $('#btnPrintReport')?.addEventListener('click', () => {
        const id = viewModal?.dataset?.currentId;
        const item = ITEMS.find(x => String(x.id) === String(id));
        if (!item) return alert('Item not found');
        
        // Create printable content
        const printContent = `
            <html>
            <head>
                <title>Inventory Report - ${item.name}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h1 { color: #2d5016; border-bottom: 2px solid #2d5016; padding-bottom: 10px; }
                    .info-row { display: flex; padding: 10px 0; border-bottom: 1px solid #eee; }
                    .label { font-weight: bold; width: 150px; color: #666; }
                    .value { flex: 1; }
                    .status { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; }
                    .status.good { background: #d1fae5; color: #065f46; }
                    .status.low-stock { background: #fef3c7; color: #92400e; }
                    .status.critical { background: #fee2e2; color: #991b1b; }
                    .footer { margin-top: 30px; font-size: 12px; color: #999; text-align: center; }
                </style>
            </head>
            <body>
                <h1>Inventory Item Report</h1>
                <div class="info-row"><span class="label">Item Name:</span><span class="value">${item.name || 'N/A'}</span></div>
                <div class="info-row"><span class="label">Category:</span><span class="value">${item.category || 'N/A'}</span></div>
                <div class="info-row"><span class="label">Branch:</span><span class="value">${item.branch_label || item.branch_name || 'N/A'}</span></div>
                <div class="info-row"><span class="label">Current Stock:</span><span class="value">${item.stock_qty || 0} ${item.unit || 'pcs'}</span></div>
                <div class="info-row"><span class="label">Min/Max Stock:</span><span class="value">Min: ${item.min_stock || 0} / Max: ${item.max_stock || 0}</span></div>
                <div class="info-row"><span class="label">Price:</span><span class="value">₱${parseFloat(item.price || 0).toFixed(2)}</span></div>
                <div class="info-row"><span class="label">Status:</span><span class="value"><span class="status ${statusClass(item.status)}">${item.status || 'N/A'}</span></span></div>
                <div class="info-row"><span class="label">Expiry Date:</span><span class="value">${item.expiry || 'N/A'}</span></div>
                <div class="info-row"><span class="label">Last Updated:</span><span class="value">${item.updated_ago || 'Unknown'}</span></div>
                <div class="footer">
                    Generated on ${new Date().toLocaleString()} | CHAKANOKS Supply Chain Management System
                </div>
            </body>
            </html>
        `;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    });

    // --------- ADD PRODUCT ---------
    $('#addForm')?.addEventListener('submit', e => {
        e.preventDefault();

        const form = $('#addForm');
        const formData = new FormData(form);

        console.log('Form data:', Object.fromEntries(formData));

        fetch(baseUrl + 'staff/addProduct', {
            method:'POST',
            headers:{ 
                'Content-Type':'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
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