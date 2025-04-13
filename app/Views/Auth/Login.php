<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Exam Results Management</title>
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

    .login-container {
      width: 100%;
      max-width: 420px;
      background-color: var(--card-bg);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      position: relative;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .login-container::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
    }

    .login-header {
      background-color: rgba(0, 0, 0, 0.2);
      color: var(--text-primary);
      padding: 2rem;
      text-align: center;
      position: relative;
    }

    .login-header h1 {
      font-size: 1.5rem;
      font-weight: 600;
      margin-top: 0.5rem;
    }

    .login-header .logo {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      color: var(--primary);
    }

    .login-form {
      padding: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--text-primary);
    }

    .form-group input {
      width: 100%;
      padding: 0.85rem 1rem;
      border: 1px solid var(--input-border);
      border-radius: var(--border-radius);
      font-size: 1rem;
      transition: var(--transition);
      background-color: var(--input-bg);
      color: var(--text-primary);
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--input-focus-border);
      box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
    }

    .form-group .input-with-icon {
      position: relative;
    }

    .form-group .input-with-icon input {
      padding-left: 2.5rem;
      padding-right: 2.5rem;
    }

    .form-group .input-with-icon i:not(.password-toggle) {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
    }

    .form-group .password-toggle {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      cursor: pointer;
      transition: var(--transition);
    }

    .form-group .password-toggle:hover {
      color: var(--primary);
    }

    .form-check {
      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .form-check input {
      margin-right: 0.5rem;
      width: 1rem;
      height: 1rem;
      accent-color: var(--primary);
    }

    .form-check label {
      font-size: 0.875rem;
      color: var(--text-secondary);
    }

    .login-btn {
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

    .login-btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
    }

    .login-footer {
      text-align: center;
      padding: 1rem 2rem 2rem;
    }

    .login-footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .login-footer a:hover {
      color: var(--primary-light);
      text-decoration: underline;
    }

    .forgot-password {
      text-align: right;
      margin-bottom: 1.5rem;
    }

    .forgot-password a {
      font-size: 0.875rem;
      color: var(--primary);
      text-decoration: none;
      transition: var(--transition);
    }

    .forgot-password a:hover {
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
      .login-container {
        max-width: 100%;
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

  <div class="login-container">
    <div class="login-header">
      <div class="logo">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <h1>Exam Results Management</h1>
    </div>

    <form class="login-form" action="<?= base_url('login') ?>" method="post">
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="email">Email</label>
        <div class="input-with-icon">
          <i class="fas fa-envelope"></i>
          <input type="email" id="email" name="email" 
                 value="<?= old('email') ?>"
                 class="<?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                 placeholder="Enter your email" required>
        </div>
        <?php if (session('errors.email')) : ?>
          <div class="invalid-feedback">
            <?= session('errors.email') ?>
          </div>
        <?php endif ?>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-with-icon">
          <i class="fas fa-lock"></i>
          <input type="password" id="password" name="password" 
                 class="<?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                 placeholder="Enter your password" required>
          <i class="fas fa-eye password-toggle" id="togglePassword"></i>
        </div>
        <?php if (session('errors.password')) : ?>
          <div class="invalid-feedback">
            <?= session('errors.password') ?>
          </div>
        <?php endif ?>
      </div>

      <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
      <div class="form-check">
        <input type="checkbox" id="remember" name="remember" class="form-check-input" 
               <?php if (old('remember')): ?> checked<?php endif ?>>
        <label for="remember">Remember me</label>
      </div>
      <?php endif; ?>

      <div class="forgot-password">
        <?php if (setting('Auth.allowForgotPassword')) : ?>
          <a href="<?= url_to('forgot') ?>">Forgot password?</a>
        <?php endif; ?>
      </div>

      <button type="submit" class="login-btn">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>
    </form>

    <div class="login-footer">
      <?php if (setting('Auth.allowRegistration')) : ?>
        <p>Don't have an account? <a href="<?= base_url('register') ?>">Register here</a></p>
      <?php endif; ?>
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

      // Password toggle functionality
      const togglePassword = document.querySelector('#togglePassword');
      const password = document.querySelector('#password');
      
      if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
          // Toggle the type attribute
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          
          // Toggle the eye icon
          this.classList.toggle('fa-eye');
          this.classList.toggle('fa-eye-slash');
        });
      }
    });
  </script>
</body>
</html>