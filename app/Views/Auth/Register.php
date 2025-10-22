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
      --bg-gradient-from: #1a1a1a;
      --bg-gradient-to: #2d2d2d;
      --primary: #4AE54A;
      --primary-dark: #3AD03A;
      --primary-light: #5FF25F;
      --secondary: #333333;
      --text-primary: #ffffff;
      --text-secondary: #a0a0a0;
      --card-bg: rgba(40, 40, 40, 0.8);
      --input-bg: rgba(30, 30, 30, 0.6);
      --input-border: #444444;
      --input-focus-border: #5FF25F;
      --success: #31c48d;
      --warning: #f59e0b;
      --danger: #ef4444;
      --info: #3b82f6;
      --border-radius: 12px;
      --button-radius: 50px;
      --shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, var(--bg-gradient-from), var(--bg-gradient-to));
      color: var(--text-primary);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(74, 229, 74, 0.03) 0%, rgba(0, 0, 0, 0) 70%);
      z-index: -1;
    }

    .register-container {
      width: 100%;
      max-width: 500px;
      background-color: var(--card-bg);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      position: relative;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .register-container::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
    }

    .register-header {
      background-color: rgba(0, 0, 0, 0.2);
      color: var(--text-primary);
      padding: 1.25rem;
      text-align: center;
      position: relative;
    }

    .register-header h1 {
      font-size: 1.4rem;
      font-weight: 600;
      margin-top: 0.25rem;
    }

    .register-header .logo {
      font-size: 2rem;
      margin-bottom: 0.25rem;
      color: var(--primary);
    }

    .register-form {
      padding: 1.5rem;
    }

    .form-row {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .form-group {
      margin-bottom: 1rem;
      flex: 1;
    }

    .form-row .form-group {
      margin-bottom: 0;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--text-primary);
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 0.85rem 1rem;
      border: 1px solid var(--input-border);
      border-radius: var(--border-radius);
      font-size: 1rem;
      transition: var(--transition);
      background-color: var(--input-bg);
      color: var(--text-primary);
    }

    .form-group input:focus, .form-group select:focus {
      outline: none;
      border-color: var(--input-focus-border);
      box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
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
      color: var(--text-secondary);
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
      accent-color: var(--primary);
    }

    .form-check label {
      font-size: 0.875rem;
      color: var(--text-secondary);
      line-height: 1.4;
    }

    .register-btn {
      width: 100%;
      padding: 0.85rem;
      background-color: var(--primary);
      color: black;
      border: none;
      border-radius: var(--button-radius);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
    }

    .register-btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
    }

    .register-footer {
      text-align: center;
      padding: 0.75rem 1.5rem 1.5rem;
    }

    .register-footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .register-footer a:hover {
      color: var(--primary-light);
      text-decoration: underline;
    }

    /* ================== ALERT STYLES ================== */
    .alert {
      position: fixed;
      top: 1.5rem;
      right: 1.5rem;
      padding: 1rem 1.5rem;
      border-radius: var(--border-radius);
      background-color: rgba(30, 30, 30, 0.9);
      color: var(--text-primary);
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
      backdrop-filter: blur(10px);
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
      color: var(--text-secondary);
    }

    .alert-close {
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      font-size: 1rem;
      padding: 0.25rem;
      transition: var(--transition);
    }

    .alert-close:hover {
      color: var(--text-primary);
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

    .password-strength {
      height: 5px;
      margin-top: 0.5rem;
      border-radius: 2.5px;
      background-color: rgba(255, 255, 255, 0.1);
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

    .decorative-element {
      position: absolute;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(74, 229, 74, 0.05) 0%, rgba(0, 0, 0, 0) 70%);
      z-index: -1;
    }

    .decorative-element-1 {
      top: -150px;
      right: -150px;
    }

    .decorative-element-2 {
      bottom: -150px;
      left: -150px;
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
  <div class="decorative-element decorative-element-1"></div>
  <div class="decorative-element decorative-element-2"></div>

  <!-- Alerts Container -->
  <div id="alerts-container">
    <?php if (session("error")): ?>
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div class="alert-content">
          <div class="alert-title">Error</div>
          <div class="alert-message"><?= session("error") ?></div>
        </div>
        <button class="alert-close">
          <i class="fas fa-times"></i>
        </button>
        <div class="alert-progress"></div>
      </div>
    <?php endif; ?>

    <?php if (session("errors")): ?>
      <?php foreach (session("errors") as $error): ?>
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
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if (session("message")): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <div class="alert-content">
          <div class="alert-title">Success</div>
          <div class="alert-message"><?= session("message") ?></div>
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

    <?= view("Auth/_message_block") ?>

    <form class="register-form" action="<?= base_url(
        "register",
    ) ?>" method="post">
      <?= csrf_field() ?>

      <div class="form-row">
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-with-icon">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email"
                   value="<?= old("email") ?>"
                   class="<?php if (
                       session("errors.email")
                   ): ?>is-invalid<?php endif; ?>"
                   placeholder="Enter your email address" required>
          </div>
          <?php if (session("errors.email")): ?>
            <div class="invalid-feedback">
              <?= session("errors.email") ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-with-icon">
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username"
                   value="<?= old("username") ?>"
                   class="<?php if (
                       session("errors.username")
                   ): ?>is-invalid<?php endif; ?>"
                   placeholder="Choose a username" required>
          </div>
          <?php if (session("errors.username")): ?>
            <div class="invalid-feedback">
              <?= session("errors.username") ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-with-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password"
                   class="<?php if (
                       session("errors.password")
                   ): ?>is-invalid<?php endif; ?>"
                   placeholder="Create a password" required>
          </div>
          <?php if (session("errors.password")): ?>
            <div class="invalid-feedback">
              <?= session("errors.password") ?>
            </div>
          <?php endif; ?>
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
                   class="<?php if (
                       session("errors.password_confirm")
                   ): ?>is-invalid<?php endif; ?>"
                   placeholder="Confirm your password" required>
          </div>
          <?php if (session("errors.password_confirm")): ?>
            <div class="invalid-feedback">
              <?= session("errors.password_confirm") ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <button type="submit" class="register-btn">
        <i class="fas fa-user-plus"></i> Create Account
      </button>

      <div style="margin-top: 1.5rem; text-align: center;">
        <a href="<?= base_url(
            "/",
        ) ?>" class="register-btn" style="background-color: var(--secondary); color: var(--text-primary); box-shadow: none;">
          <i class="fas fa-home"></i> Back to Home
        </a>
      </div>
    </form>

    <div class="register-footer">
      <p>Already have an account? <a href="<?= base_url(
          "login",
      ) ?>">Login here</a></p>
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
          passwordStrengthText.style.color = '#31c48d';
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
