// ✅ Tab switching functionality
function switchTab(tabName) {
  document.querySelectorAll('.form-content').forEach(form => form.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

  const targetForm = document.getElementById(`${tabName}-form`);
  if (targetForm) targetForm.classList.add('active');

  const targetTab = document.querySelector(`.tab-btn[onclick="switchTab('${tabName}')"]`);
  if (targetTab) targetTab.classList.add('active');

}

// ❌ Removed handleSignIn() because the form now posts directly to CI route

// ✅ Email validation
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// ✅ Notification system
function showNotification(message, type = 'info') {
  const existingNotification = document.querySelector('.notification');
  if (existingNotification) existingNotification.remove();

  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
    <div class="notification-content">
      <span class="notification-message">${message}</span>
      <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
    </div>
  `;

  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${type === 'error' ? '#dc3545' : type === 'success' ? '#28a745' : '#007bff'};
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    max-width: 400px;
    animation: slideIn 0.3s ease;
  `;

  document.body.appendChild(notification);

  setTimeout(() => {
    if (notification.parentElement) notification.remove();
  }, 3000);
}

// ✅ CSS animations for notification
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }
  .notification-content { display:flex; align-items:center; justify-content:space-between; }
  .notification-close { background:none; border:none; color:#fff; font-size:20px; cursor:pointer; margin-left:15px; padding:0; width:20px; height:20px; display:flex; align-items:center; justify-content:center; }
  .notification-close:hover { opacity:0.85; }
`;
document.head.appendChild(style);

// ✅ Input animations
document.addEventListener('DOMContentLoaded', function() {
  // ❌ Removed Enter key submit binding to handleSignIn()

  const inputs = document.querySelectorAll('input');
  inputs.forEach(input => {
    input.addEventListener('focus', function(){ this.parentElement.style.transform = 'scale(1.02)'; });
    input.addEventListener('blur', function(){ this.parentElement.style.transform = 'scale(1)'; });
  });
});

// ❌ Removed redirectToDashboard() — redirect is handled by PHP controller
