// ✅ Tab switching functionality
function switchTab(tabName) {
  // Remove active class from all forms
  document.querySelectorAll('.form-content').forEach(form => form.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

  // Show the correct form
  const targetForm = document.getElementById(`${tabName}-form`);
  if (targetForm) {
    targetForm.classList.add('active');
  }

  // Activate the tab button only if it exists (signin/register)
  const targetTab = document.querySelector(`.tab-btn[onclick="switchTab('${tabName}')"]`);
  if (targetTab) {
    targetTab.classList.add('active');
  }

  // ✅ Extra: clear forgot password email when switching back to sign in
  if (tabName === 'signin') {
    const forgotEmail = document.getElementById('forgot-email');
    if (forgotEmail) {
      forgotEmail.value = '';
    }
  }
}

// ✅ Show forgot password form
function showForgotPassword() {
  document.querySelectorAll('.form-content').forEach(form => form.classList.remove('active'));
  document.getElementById('forgot-form').classList.add('active');
}

// ✅ Handle Sign In
function handleSignIn() {
  const email = document.getElementById('signin-email').value.trim();
  const password = document.getElementById('signin-password').value.trim();
  const rememberMe = document.getElementById('remember-me').checked;

  if (!email || !password) {
    showNotification('Please fill in all fields', 'error');
    return;
  }

  if (!isValidEmail(email)) {
    showNotification('Please enter a valid email address', 'error');
    return;
  }

  // AJAX call to signin.php (or your CI controller route)
  const formData = new FormData();
  formData.append('email', email);
  formData.append('password', password);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "signin.php", true);
  xhr.onreadystatechange = function () {
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      if (this.responseText === "Logged in successfully") {
        redirectToDashboard();
      } else {
        showNotification(this.responseText, 'error');
      }
    }
  }
  xhr.send(formData);
}

// ✅ Handle Forgot Password
function handleForgotPassword() {
  const email = document.getElementById('forgot-email').value.trim();

  if (!email) {
    showNotification('Please enter your email address', 'error');
    return;
  }

  if (!isValidEmail(email)) {
    showNotification('Please enter a valid email address', 'error');
    return;
  }

  showNotification('Password reset link sent to your email!', 'success');
  document.getElementById('forgot-email').value = '';

  // Go back to Sign In after success
  switchTab('signin');
}

// ✅ Email validation
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// ✅ Notification system
function showNotification(message, type = 'info') {
  const existingNotification = document.querySelector('.notification');
  if (existingNotification) {
    existingNotification.remove();
  }

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
    if (notification.parentElement) {
      notification.remove();
    }
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

// ✅ Enter key submit + input animations
document.addEventListener('DOMContentLoaded', function() {
  const signinForm = document.getElementById('signin-form');
  signinForm.addEventListener('keypress', function(e) { if (e.key === 'Enter') { handleSignIn(); } });

  const forgotForm = document.getElementById('forgot-form');
  forgotForm.addEventListener('keypress', function(e) { if (e.key === 'Enter') { handleForgotPassword(); } });

  const inputs = document.querySelectorAll('input');
  inputs.forEach(input => {
    input.addEventListener('focus', function(){ this.parentElement.style.transform = 'scale(1.02)'; });
    input.addEventListener('blur', function(){ this.parentElement.style.transform = 'scale(1)'; });
  });
});

// ✅ Redirect after login
function redirectToDashboard() {
  window.location.href = "dashboard.php"; // Change path if needed
}
