<!-- Notifications Component -->
<div id="notificationsContainer" style="position: relative; display: inline-block; margin-left: 12px;">
    <!-- Notification Bell -->
    <div style="position: relative; display: inline-block;">
        <button id="notificationBell" onclick="toggleNotifications()" style="background: white; border: 1px solid #d1d5db; border-radius: 8px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; color: #2d5016; padding: 0; position: relative;" onmouseover="this.style.borderColor='#2d5016'; this.style.backgroundColor='#f9fafb';" onmouseout="this.style.borderColor='#d1d5db'; this.style.backgroundColor='white';">
            <i class="fas fa-bell" style="font-size: 1.1rem; color: #2d5016;"></i>
        </button>
        <span id="notificationBadge" style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border-radius: 50%; width: 20px; height: 20px; display: none; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; border: 2px solid white; z-index: 1;">0</span>
    </div>
    
    <!-- Notifications Dropdown -->
    <div id="notificationsDropdown" style="display: none; position: absolute; top: 50px; right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 380px; max-height: 500px; overflow-y: auto; z-index: 1000;">
        <div style="padding: 20px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; color: #2d5016; font-weight: 700;">Notifications</h4>
            <button onclick="markAllNotificationsRead()" style="background: none; border: none; color: #3b82f6; cursor: pointer; font-size: 0.875rem; font-weight: 500;">Mark all read</button>
        </div>
        <div id="notificationsList" style="max-height: 400px; overflow-y: auto;">
            <div style="padding: 40px; text-align: center; color: #64748b;">
                <i class="fas fa-bell-slash" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                <p style="margin: 0; font-size: 1rem; font-weight: 500;">No notifications</p>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-item {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .notification-item:hover {
        background: #f8f9fa;
    }
    
    .notification-item.unread {
        background: #f0f7ff;
        border-left: 4px solid #3b82f6;
    }
    
    .notification-item.read {
        opacity: 0.7;
    }
    
    .notification-type-info { border-left-color: #3b82f6; }
    .notification-type-success { border-left-color: #10b981; }
    .notification-type-warning { border-left-color: #f59e0b; }
    .notification-type-danger { border-left-color: #ef4444; }
    .notification-type-system { border-left-color: #2d5016; }
</style>

<script>
let notificationsInterval;
let isNotificationsOpen = false;

function toggleNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    isNotificationsOpen = !isNotificationsOpen;
    dropdown.style.display = isNotificationsOpen ? 'block' : 'none';
    
    if (isNotificationsOpen) {
        loadNotifications();
    }
}

function loadNotifications() {
    fetch('<?= base_url('notifications/') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateNotificationBadge(data.unread_count);
                renderNotifications(data.notifications);
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
        });
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

function renderNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    
    if (!notifications || notifications.length === 0) {
        container.innerHTML = '<div style="padding: 40px; text-align: center; color: #64748b;"><i class="fas fa-bell-slash" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i><p style="margin: 0; font-size: 1rem; font-weight: 500;">No notifications</p></div>';
        return;
    }
    
    let html = '';
    notifications.forEach(notif => {
        const typeClass = `notification-type-${notif.type}`;
        const readClass = notif.is_read ? 'read' : 'unread';
        const timeAgo = getTimeAgo(notif.created_at);
        
        html += `<div class="notification-item ${readClass} ${typeClass}" onclick="handleNotificationClick(${notif.id}, '${notif.link || ''}')">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 12px;">
                <div style="flex: 1;">
                    <h5 style="margin: 0 0 8px 0; font-size: 0.9rem; font-weight: 600; color: #1e293b;">${escapeHtml(notif.title)}</h5>
                    <p style="margin: 0 0 8px 0; font-size: 0.875rem; color: #64748b; line-height: 1.4;">${escapeHtml(notif.message)}</p>
                    <span style="font-size: 0.75rem; color: #94a3b8;">${timeAgo}</span>
                </div>
                ${!notif.is_read ? '<div style="width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; flex-shrink: 0; margin-top: 4px;"></div>' : ''}
            </div>
        </div>`;
    });
    
    container.innerHTML = html;
}

function handleNotificationClick(notificationId, link) {
    // Mark as read
    fetch('<?= base_url('notifications/mark-read/') ?>' + notificationId, {
        method: 'POST'
    }).then(() => {
        loadNotifications();
        loadUnreadCount();
    });
    
    // Navigate if link exists
    if (link) {
        window.location.href = link;
    }
}

function markAllNotificationsRead() {
    fetch('<?= base_url('notifications/mark-all-read') ?>', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            loadNotifications();
            loadUnreadCount();
        }
    });
}

function loadUnreadCount() {
    fetch('<?= base_url('notifications/unread-count') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateNotificationBadge(data.unread_count);
            }
        });
}

function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    
    if (diff < 60) return 'Just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
    return date.toLocaleDateString();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    loadUnreadCount();
    
    // Refresh notifications every 30 seconds
    notificationsInterval = setInterval(() => {
        if (!isNotificationsOpen) {
            loadUnreadCount();
        } else {
            loadNotifications();
        }
    }, 30000);
});

// Close notifications when clicking outside
document.addEventListener('click', function(event) {
    const container = document.getElementById('notificationsContainer');
    if (isNotificationsOpen && !container.contains(event.target)) {
        toggleNotifications();
    }
});
</script>

