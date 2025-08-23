script.js

// Tab switching functionality
function switchTab(tabName) {
  // Remove active class from all tabs and forms
  document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
  document.querySelectorAll('.form-content').forEach(form => form.classList.remove('active'));
  
  // Add active class to selected tab and form
  document.querySelector(`[onclick="switchTab('${tabName}')"]`).classList.add('active');
  document.getElementById(`${tabName}-form`).classList.add('active');
}

// Show forgot password form
function showForgotPassword() {
  document.querySelectorAll('.form-content').forEach(form => form.classList.remove('active'));
  document.getElementById('forgot-form').classList.add('active');
}

// Handle Sign In
function handleSignIn() {
  const email = document.getElementById('signin-email').value.trim();
  const password = document.getElementById('signin-password').value.trim();
  const rememberMe = document.getElementById('remember-me').checked;
  
  // Basic validation
  if (!email || !password) {
    showNotification('Please fill in all fields', 'error');
    return;
  }
  
  if (!isValidEmail(email)) {
    showNotification('Please enter a valid email address', 'error');
    return;
  }
  
  // AJAX call to signin.php
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

// Handle Register
function handleRegister() {
  const name = document.getElementById('register-name').value.trim();
  const email = document.getElementById('register-email').value.trim();
  const position = document.getElementById('register-position').value.trim();
  const password = document.getElementById('register-password').value.trim();
  const confirmPassword = document.getElementById('register-confirm').value.trim();
  
  // Basic validation
  if (!name || !email || !position || !password || !confirmPassword) {
    showNotification('Please fill in all fields', 'error');
    return;
  }
  
  if (!isValidEmail(email)) {
    showNotification('Please enter a valid email address', 'error');
    return;
  }
  
  if (password.length < 8) {
    showNotification('Password must be at least 8 characters long', 'error');
    return;
  }
  
  if (password !== confirmPassword) {
    showNotification('Passwords do not match', 'error');
    return;
  }
  
  // AJAX call to register.php
  const formData = new FormData();
  formData.append('full_name', name);
  formData.append('email', email);
  formData.append('position', position);
  formData.append('password', password);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "register.php", true);
  xhr.onreadystatechange = function () {
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      console.log(this.responseText);
      if (this.responseText === "New record created successfully") {
        showNotification('Account created successfully!', 'success');
      } else {
        showNotification(this.responseText, 'error');
      }
    }
  }
  xhr.send(formData);
}

// Handle Forgot Password
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
  
  // Simulate password reset process
  showNotification('Password reset link sent to your email!', 'success');
  
  // Switch back to sign in tab
  switchTab('signin');
  // Clear form
  document.getElementById('forgot-email').value = '';
}

// Email validation helper
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Clear register form
function clearRegisterForm() {
  document.getElementById('register-name').value = '';
  document.getElementById('register-email').value = '';
  document.getElementById('register-position').value = '';
  document.getElementById('register-password').value = '';
  document.getElementById('register-confirm').value = '';
}

// Notification system
function showNotification(message, type = 'info') {
  // Remove existing notifications
  const existingNotification = document.querySelector('.notification');
  if (existingNotification) {
    existingNotification.remove();
  }
  
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
    <div class="notification-content">
      <span class="notification-message">${message}</span>
      <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
    </div>
  `;
  
  // Add styles
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
  
  // Add to page
  document.body.appendChild(notification);
  
  // Auto remove after 5 seconds
  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove();
    }
  }, 3000);
}

// Add CSS animations
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

// Form Enter submit
document.addEventListener('DOMContentLoaded', function() {
  const signinForm = document.getElementById('signin-form');
  signinForm.addEventListener('keypress', function(e) { if (e.key === 'Enter') { handleSignIn(); } });
  const registerForm = document.getElementById('register-form');
  registerForm.addEventListener('keypress', function(e) { if (e.key === 'Enter') { handleRegister(); } });
  const forgotForm = document.getElementById('forgot-form');
  forgotForm.addEventListener('keypress', function(e) { if (e.key === 'Enter') { handleForgotPassword(); } });
  const inputs = document.querySelectorAll('input');
  inputs.forEach(input => {
    input.addEventListener('focus', function(){ this.parentElement.style.transform = 'scale(1.02)'; });
    input.addEventListener('blur', function(){ this.parentElement.style.transform = 'scale(1)'; });
  });
});

// Legacy
function showForm(id) { switchTab(id); }