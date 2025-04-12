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

    /* ================== ALERT STYLES ================== */
    .alert {
      position: fixed;
      top: 1.5rem;
      right: 1.5rem;
      padding: 1rem 1.5rem;
      border-radius: var(--border-radius);
      background-color: white;
      color: var(--text-dark);
      box-shadow: var(--shadow);
      display: flex;
      align-items: center;
      gap: 0.75rem;
      z-index: 1000;
      max-width: 400px;
      border-left: 4px solid;
      opacity: 0;
      transform: translateX(calc(100% + 1.5rem));
      transition: all 0.3s ease;
    }

    .alert.show {
      opacity: 1;
      transform: translateX(0);
    }

    /* Alert Types */
    .alert-success {
      border-left-color: var(--success);
    }

    .alert-danger {
      border-left-color: var(--danger);
    }

    .alert-warning {
      border-left-color: var(--warning);
    }

    .alert-info {
      border-left-color: var(--info);
    }

    /* Alert Icons */
    .alert i {
      font-size: 1.25rem;
    }

    .alert-success i {
      color: var(--success);
    }

    .alert-danger i {
      color: var(--danger);
    }

    .alert-warning i {
      color: var(--warning);
    }

    .alert-info i {
      color: var(--info);
    }

    .alert-content {
      flex: 1;
    }

    .alert-title {
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .alert-message {
      font-size: 0.875rem;
      color: #64748b;
    }

    .alert-close {
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      font-size: 1rem;
      padding: 0.25rem;
      transition: var(--transition);
    }

    .alert-close:hover {
      color: var(--text-dark);
    }

    /* Alert Progress Bar */
    .alert-progress {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 3px;
      width: 100%;
      transform-origin: left;
      background-color: currentColor;
      opacity: 0.2;
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
      
      .alert {
        max-width: 90%;
        right: 1rem;
      }
    }

    .invalid-feedback {
      color: var(--danger);
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }

    .is-invalid {
      border-color: var(--danger) !important;
    }

    .is-invalid:focus {
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
    }
  </style>
</head>
<body>
  <!-- Alerts Container -->
  <div id="alerts-container">
    <?php if (session('error')) : ?>
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div class="alert-content">
          <div class="alert-title">Error</div>
          <div class="alert-message"><?= session('error') ?></div>
        </div>
        <button class="alert-close">
          <i class="fas fa-times"></i>
        </button>
        <div class="alert-progress"></div>
      </div>
    <?php endif; ?>

    <?php if (session('errors')) : ?>
      <?php foreach (session('errors') as $error) : ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle"></i>
          <div class="alert-content">
            <div class="alert-title">Validation Error</div>
            <div class="alert-message"><?= $error ?></div>
          </div>
          <button class="alert-close">
            <i class="fas fa-times"></i>
          </button>
          <div class="alert-progress"></div>
        </div>
      <?php endforeach ?>
    <?php endif; ?>

    <?php if (session('message')) : ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <div class="alert-content">
          <div class="alert-title">Success</div>
          <div class="alert-message"><?= session('message') ?></div>
        </div>
        <button class="alert-close">
          <i class="fas fa-times"></i>
        </button>
        <div class="alert-progress"></div>
      </div>
    <?php endif; ?>
  </div>

  <div class="register-container">
    <div class="register-header">
      <div class="logo">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <h1>Create Your Account</h1>
    </div>

    <?= view('Auth/_message_block') ?>

    <form class="register-form" action="<?= base_url('register') ?>" method="post">
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="email">Email Address</label>
        <div class="input-with-icon">
          <i class="fas fa-envelope"></i>
          <input type="email" id="email" name="email" 
                 value="<?= old('email') ?>"
                 class="<?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                 placeholder="Enter your email address" required>
        </div>
        <?php if (session('errors.email')) : ?>
          <div class="invalid-feedback">
            <?= session('errors.email') ?>
          </div>
        <?php endif ?>
      </div>

      <div class="form-group">
        <label for="username">Username</label>
        <div class="input-with-icon">
          <i class="fas fa-user"></i>
          <input type="text" id="username" name="username" 
                 value="<?= old('username') ?>"
                 class="<?php if (session('errors.username')) : ?>is-invalid<?php endif ?>"
                 placeholder="Choose a username" required>
        </div>
        <?php if (session('errors.username')) : ?>
          <div class="invalid-feedback">
            <?= session('errors.username') ?>
          </div>
        <?php endif ?>
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-with-icon">
          <i class="fas fa-lock"></i>
          <input type="password" id="password" name="password"
                 class="<?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                 placeholder="Create a password" required>
        </div>
        <?php if (session('errors.password')) : ?>
          <div class="invalid-feedback">
            <?= session('errors.password') ?>
          </div>
        <?php endif ?>
        <div class="password-strength">
          <div class="password-strength-meter" id="passwordStrengthMeter"></div>
        </div>
        <div class="password-strength-text" id="passwordStrengthText"></div>
      </div>

      <div class="form-group">
        <label for="password_confirm">Confirm Password</label>
        <div class="input-with-icon">
          <i class="fas fa-lock"></i>
          <input type="password" id="password_confirm" name="password_confirm"
                 class="<?php if (session('errors.password_confirm')) : ?>is-invalid<?php endif ?>"
                 placeholder="Confirm your password" required>
        </div>
        <?php if (session('errors.password_confirm')) : ?>
          <div class="invalid-feedback">
            <?= session('errors.password_confirm') ?>
          </div>
        <?php endif ?>
      </div>

      <button type="submit" class="register-btn">
        <i class="fas fa-user-plus"></i> Create Account
      </button>
    </form>

    <div class="register-footer">
      <p>Already have an account? <a href="<?= base_url('login') ?>">Login here</a></p>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Show all alerts
      const alerts = document.querySelectorAll('.alert');
      
      alerts.forEach(alert => {
        // Show alert
        setTimeout(() => {
          alert.classList.add('show');
        }, 100);

        // Auto-hide after 5 seconds
        const autoHide = setTimeout(() => {
          alert.classList.remove('show');
          setTimeout(() => alert.remove(), 300);
        }, 5000);

        // Close button functionality
        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
          closeBtn.addEventListener('click', () => {
            clearTimeout(autoHide);
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
          });
        }
      });

      // Password strength meter
      const passwordInput = document.getElementById('password');
      const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');
      const passwordStrengthText = document.getElementById('passwordStrengthText');

      // Check password strength
      function checkPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]+/)) strength += 1;
        if (password.match(/[A-Z]+/)) strength += 1;
        if (password.match(/[0-9]+/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
        
        passwordStrengthMeter.className = 'password-strength-meter';
        
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

      // Form animations
      const formGroups = document.querySelectorAll('.form-group');
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