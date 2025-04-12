<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Exam Results Management</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #4f46e5;
      --primary-dark: #4338ca;
      --primary-light: #818cf8;
      --sidebar-bg: #1e1b4b;
      --sidebar-hover: #312e81;
      --text-light: #f8fafc;
      --text-dark: #1e293b;
      --card-bg: #ffffff;
      --body-bg: #f1f5f9;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --info: #3b82f6;
      --border-radius: 8px;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --transition: all 0.2s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--body-bg);
      color: var(--text-dark);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      background-image: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(30, 27, 75, 0.2) 100%);
    }

    .register-container {
      width: 100%;
      max-width: 500px;
      background-color: var(--card-bg);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      position: relative;
    }

    .register-header {
      background-color: var(--sidebar-bg);
      color: var(--text-light);
      padding: 2rem;
      text-align: center;
      position: relative;
    }

    .register-header h1 {
      font-size: 1.5rem;
      font-weight: 600;
      margin-top: 0.5rem;
    }

    .register-header .logo {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }

    .register-form {
      padding: 2rem;
    }

    .form-row {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
      flex: 1;
    }

    .form-row .form-group {
      margin-bottom: 0;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--text-dark);
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #e2e8f0;
      border-radius: var(--border-radius);
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group input:focus, .form-group select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    .form-group .input-with-icon {
      position: relative;
    }

    .form-group .input-with-icon input, .form-group .input-with-icon select {
      padding-left: 2.5rem;
    }

    .form-group .input-with-icon i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
    }

    .form-check {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.5rem;
    }

    .form-check input {
      margin-right: 0.5rem;
      margin-top: 0.25rem;
      width: 1rem;
      height: 1rem;
    }

    .form-check label {
      font-size: 0.875rem;
      color: #64748b;
      line-height: 1.4;
    }

    .register-btn {
      width: 100%;
      padding: 0.75rem;
      background-color: var(--primary);
      color: white;
      border: none;
      border-radius: var(--border-radius);
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: var(--transition);
    }

    .register-btn:hover {
      background-color: var(--primary-dark);
    }

    .register-footer {
      text-align: center;
      padding: 1rem 2rem 2rem;
    }

    .register-footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .register-footer a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    /* Notification styles */
    .notification {
      position: fixed;
      top: 1.5rem;
      right: 1.5rem;
      padding: 1rem 1.5rem;
      border-radius: var(--border-radius);
      background-color: white;
      color: var(--text-dark);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transform: translateX(calc(100% + 1.5rem));
      transition: transform 0.3s ease;
      z-index: 1000;
      max-width: 400px;
    }

    .notification.show {
      transform: translateX(0);
    }

    .notification-icon {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .notification-success .notification-icon {
      background-color: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .notification-error .notification-icon {
      background-color: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .notification-warning .notification-icon {
      background-color: rgba(245, 158, 11, 0.1);
      color: var(--warning);
    }

    .notification-info .notification-icon {
      background-color: rgba(59, 130, 246, 0.1);
      color: var(--info);
    }

    .notification-content {
      flex: 1;
    }

    .notification-title {
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .notification-message {
      font-size: 0.875rem;
      color: #64748b;
    }

    .notification-close {
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      font-size: 1rem;
      padding: 0.25rem;
      transition: var(--transition);
    }

    .notification-close:hover {
      color: var(--text-dark);
    }

    .notification-progress {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 3px;
      background-color: var(--primary-light);
      width: 100%;
      transform-origin: left;
      animation: progress 5s linear forwards;
    }

    @keyframes progress {
      from { transform: scaleX(1); }
      to { transform: scaleX(0); }
    }

    .password-strength {
      height: 5px;
      margin-top: 0.5rem;
      border-radius: 2.5px;
      background-color: #e2e8f0;
      overflow: hidden;
    }

    .password-strength-meter {
      height: 100%;
      width: 0;
      transition: width 0.3s ease, background-color 0.3s ease;
    }

    .password-strength-text {
      font-size: 0.75rem;
      margin-top: 0.25rem;
      text-align: right;
    }

    .strength-weak {
      width: 25%;
      background-color: var(--danger);
    }

    .strength-fair {
      width: 50%;
      background-color: var(--warning);
    }

    .strength-good {
      width: 75%;
      background-color: var(--info);
    }

    .strength-strong {
      width: 100%;
      background-color: var(--success);
    }

    /* Responsive */
    @media (max-width: 640px) {
      .register-container {
        max-width: 100%;
      }

      .form-row {
        flex-direction: column;
        gap: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="register-container">
    <div class="register-header">
      <div class="logo">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <h1>Create Your Account</h1>
    </div>
    <form class="register-form" id="registerForm">
      <div class="form-row">
        <div class="form-group">
          <label for="firstName">First Name</label>
          <div class="input-with-icon">
            <i class="fas fa-user"></i>
            <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
          </div>
        </div>
        <div class="form-group">
          <label for="lastName">Last Name</label>
          <div class="input-with-icon">
            <i class="fas fa-user"></i>
            <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <div class="input-with-icon">
          <i class="fas fa-envelope"></i>
          <input type="email" id="email" name="email" placeholder="Enter your email address" required>
        </div>
      </div>
      <div class="form-group">
        <label for="userType">User Type</label>
        <div class="input-with-icon">
          <i class="fas fa-user-tag"></i>
          <select id="userType" name="userType" required>
            <option value="" disabled selected>Select user type</option>
            <option value="admin">Administrator</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
            <option value="parent">Parent</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-with-icon">
          <i class="fas fa-lock"></i>
          <input type="password" id="password" name="password" placeholder="Create a password" required>
        </div>
        <div class="password-strength">
          <div class="password-strength-meter" id="passwordStrengthMeter"></div>
        </div>
        <div class="password-strength-text" id="passwordStrengthText"></div>
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <div class="input-with-icon">
          <i class="fas fa-lock"></i>
          <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
        </div>
      </div>
      <div class="form-check">
        <input type="checkbox" id="terms" name="terms" required>
        <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
      </div>
      <button type="submit" class="register-btn">
        <i class="fas fa-user-plus"></i> Create Account
      </button>
    </form>
    <div class="register-footer">
      <p>Already have an account? <a href="login.html">Login here</a></p>
    </div>
  </div>

  <!-- Notification Templates -->
  <div class="notification notification-success" id="notificationSuccess">
    <div class="notification-icon">
      <i class="fas fa-check"></i>
    </div>
    <div class="notification-content">
      <div class="notification-title">Success</div>
      <div class="notification-message">Your account has been created successfully!</div>
    </div>
    <button class="notification-close">
      <i class="fas fa-times"></i>
    </button>
    <div class="notification-progress"></div>
  </div>

  <div class="notification notification-error" id="notificationError">
    <div class="notification-icon">
      <i class="fas fa-exclamation-triangle"></i>
    </div>
    <div class="notification-content">
      <div class="notification-title">Error</div>
      <div class="notification-message">There was an error creating your account. Please try again.</div>
    </div>
    <button class="notification-close">
      <i class="fas fa-times"></i>
    </button>
    <div class="notification-progress"></div>
  </div>

  <div class="notification notification-warning" id="notificationWarning">
    <div class="notification-icon">
      <i class="fas fa-exclamation-circle"></i>
    </div>
    <div class="notification-content">
      <div class="notification-title">Warning</div>
      <div class="notification-message">Passwords do not match. Please check and try again.</div>
    </div>
    <button class="notification-close">
      <i class="fas fa-times"></i>
    </button>
    <div class="notification-progress"></div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const registerForm = document.getElementById('registerForm');
      const notificationSuccess = document.getElementById('notificationSuccess');
      const notificationError = document.getElementById('notificationError');
      const notificationWarning = document.getElementById('notificationWarning');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('confirmPassword');
      const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');
      const passwordStrengthText = document.getElementById('passwordStrengthText');
      
      // Close notification when close button is clicked
      document.querySelectorAll('.notification-close').forEach(button => {
        button.addEventListener('click', function() {
          this.closest('.notification').classList.remove('show');
        });
      });

      // Auto-close notifications after 5 seconds
      function autoCloseNotification(notification) {
        setTimeout(() => {
          notification.classList.remove('show');
        }, 5000);
      }

      // Show notification
      function showNotification(notification) {
        notification.classList.add('show');
        autoCloseNotification(notification);
      }

      // Check password strength
      function checkPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]+/)) strength += 1;
        if (password.match(/[A-Z]+/)) strength += 1;
        if (password.match(/[0-9]+/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
        
        passwordStrengthMeter.className = '';
        
        if (password.length === 0) {
          passwordStrengthMeter.style.width = '0';
          passwordStrengthText.textContent = '';
        } else if (strength < 2) {
          passwordStrengthMeter.classList.add('strength-weak');
          passwordStrengthText.textContent = 'Weak';
          passwordStrengthText.style.color = '#ef4444';
        } else if (strength < 3) {
          passwordStrengthMeter.classList.add('strength-fair');
          passwordStrengthText.textContent = 'Fair';
          passwordStrengthText.style.color = '#f59e0b';
        } else if (strength < 5) {
          passwordStrengthMeter.classList.add('strength-good');
          passwordStrengthText.textContent = 'Good';
          passwordStrengthText.style.color = '#3b82f6';
        } else {
          passwordStrengthMeter.classList.add('strength-strong');
          passwordStrengthText.textContent = 'Strong';
          passwordStrengthText.style.color = '#10b981';
        }
      }

      // Password strength meter
      passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
      });

      // Handle form submission
      registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const firstName = document.getElementById('firstName').value;
        const lastName = document.getElementById('lastName').value;
        const email = document.getElementById('email').value;
        const userType = document.getElementById('userType').value;
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const terms = document.getElementById('terms').checked;
        
        // Check if passwords match
        if (password !== confirmPassword) {
          showNotification(notificationWarning);
          return;
        }
        
        // Check if terms are accepted
        if (!terms) {
          const notificationMessage = notificationError.querySelector('.notification-message');
          notificationMessage.textContent = 'You must accept the Terms of Service and Privacy Policy.';
          showNotification(notificationError);
          return;
        }
        
        // In a real application, you would send this data to a server for registration
        // For demo purposes, we'll just show a success notification
        showNotification(notificationSuccess);
        
        // Redirect to login page after a delay (for demo purposes)
        setTimeout(() => {
          window.location.href = 'login.html';
        }, 2000);
      });

      // Add animation to form elements
      const formGroups = document.querySelectorAll('.form-group, .form-row, .form-check');
      formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        group.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        group.style.transitionDelay = `${index * 0.1}s`;
        
        setTimeout(() => {
          group.style.opacity = '1';
          group.style.transform = 'translateY(0)';
        }, 100);
      });
    });
  </script>
</body>
</html>