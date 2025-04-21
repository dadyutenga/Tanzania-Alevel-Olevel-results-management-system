<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Settings</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --primary: #4AE54A;
            --primary-dark: #3AD03A;
            --primary-light: #5FF25F;
            --secondary: #f1f5f9;
            --accent: #1a1a1a;
            --accent-hover: #2d2d2d;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
            --button-radius: 50px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.925rem;
            background-color: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--card-bg);
            border-right: 1px solid var(--border);
            padding: 1rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .logo {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .logo i {
            color: var(--primary);
            font-size: 1.75rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            position: relative;
            margin-bottom: 0.25rem;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background-color: var(--primary);
            color: black;
        }

        .sidebar-menu li a i {
            margin-right: 0.75rem;
            width: 16px;
            text-align: center;
        }

        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: var(--secondary);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu li a {
            padding: 0.5rem 1.5rem 0.5rem 2.5rem;
            font-size: 0.85rem;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            margin-left: auto;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem 1rem;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 1.5rem;
            position: relative;
            margin-bottom: 2rem;
        }

        .form-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary);
            border-top-left-radius: var(--radius);
            border-top-right-radius: var(--radius);
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

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            transition: all 0.3s ease;
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--button-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            transition: all 0.3s ease;
            background-color: var(--primary);
            color: black;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .btn-success {
            background-color: #22c55e;
            color: white;
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
        }

        .btn-success:hover {
            background-color: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(34, 197, 94, 0.4);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            border: 1px solid transparent;
        }

        .alert i {
            font-size: 1.25rem;
        }

        .alert-info {
            background-color: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
            color: var(--text-primary);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.5);
            color: var(--text-primary);
            display: none;
        }

        .alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.5);
            color: var(--text-primary);
            display: none;
        }

        hr {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.5rem 0;
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background: var(--primary);
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: var(--shadow);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem 0.5rem;
            }

            .container {
                padding: 0 0.5rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
                margin-bottom: 1rem;
            }

            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>ExamResults</span>
            </div>
            <?= $this->include('shared/sidebar_menu') ?>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>Web Settings</h1>
                    <p>Manage your school's web application settings</p>
                </div>

                <div class="form-container">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Update your school information:</strong>
                            <p style="margin-top: 0.5rem;">Changes made here will reflect across the application.</p>
                        </div>
                    </div>

                    <div class="alert alert-danger" id="errorAlert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div id="errorMessage"></div>
                    </div>
                    <div class="alert alert-success" id="successAlert">
                        <i class="fas fa-check-circle"></i>
                        <div id="successMessage"></div>
                    </div>

                    <form id="settingsForm">
                        <div class="form-group">
                            <label for="school_name">School Name <span class="text-danger">*</span></label>
                            <input type="text" id="school_name" name="school_name" class="form-control" 
                                   value="<?= esc($settings['school_name'] ?? '') ?>" required aria-required="true">
                        </div>

                        <div class="form-group">
                            <label for="total_classes">Total Classes <span class="text-danger">*</span></label>
                            <input type="number" id="total_classes" name="total_classes" class="form-control" 
                                   value="<?= esc($settings['total_classes'] ?? 0) ?>" required aria-required="true">
                        </div>

                        <div class="form-group">
                            <label for="school_year">School Year (YYYY-YYYY) <span class="text-danger">*</span></label>
                            <input type="text" id="school_year" name="school_year" class="form-control" 
                                   value="<?= esc($settings['school_year'] ?? '') ?>" required aria-required="true">
                        </div>

                        <div class="form-group">
                            <label for="school_address">School Address</label>
                            <textarea id="school_address" name="school_address" class="form-control" rows="3"><?= esc($settings['school_address'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="school_logo_url">School Logo URL</label>
                            <input type="text" id="school_logo_url" name="school_logo_url" class="form-control" 
                                   value="<?= esc($settings['school_logo_url'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="email" id="contact_email" name="contact_email" class="form-control" 
                                   value="<?= esc($settings['contact_email'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="contact_phone">Contact Phone</label>
                            <input type="text" id="contact_phone" name="contact_phone" class="form-control" 
                                   value="<?= esc($settings['contact_phone'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="is_active">Active Status <span class="text-danger">*</span></label>
                            <select id="is_active" name="is_active" class="form-control" required aria-required="true">
                                <option value="yes" <?= (isset($settings['is_active']) && $settings['is_active'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                <option value="no" <?= (isset($settings['is_active']) && $settings['is_active'] == 'no') ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-success" onclick="saveSettings()" aria-label="Save Settings">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                });
            }

            const menuItems = document.querySelectorAll('.sidebar-menu > li');

            menuItems.forEach(item => {
                const link = item.querySelector('.expandable');
                const submenu = item.querySelector('.submenu');

                if (link && submenu) {
                    if (!link.querySelector('.toggle-icon')) {
                        const icon = document.createElement('i');
                        icon.className = 'fas fa-chevron-up toggle-icon';
                        link.appendChild(icon);
                    }

                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const toggleIcon = this.querySelector('.toggle-icon');

                        document.querySelectorAll('.submenu').forEach(menu => {
                            if (menu !== submenu) {
                                menu.classList.remove('show');
                                const otherIcon = menu.previousElementSibling.querySelector('.toggle-icon');
                                if (otherIcon) {
                                    otherIcon.style.transform = 'rotate(0deg)';
                                }
                            }
                        });

                        submenu.classList.toggle('show');
                        toggleIcon.style.transform = submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                    });
                }
            });

            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            });
        });

        async function saveSettings() {
            const form = document.getElementById('settingsForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch('/settings/update/1', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.status === 'success') {
                    showSuccess(data.message);
                } else {
                    throw new Error(data.message || 'Failed to update settings');
                }
            } catch (error) {
                showError(error.message || 'Failed to update settings');
            }
        }

        function showError(message) {
            const alert = document.getElementById('errorAlert');
            const messageDiv = document.getElementById('errorMessage');
            messageDiv.textContent = message;
            alert.style.display = 'flex';
            setTimeout(() => alert.style.display = 'none', 5000);
        }

        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            const messageDiv = document.getElementById('successMessage');
            messageDiv.textContent = message;
            alert.style.display = 'flex';
            setTimeout(() => alert.style.display = 'none', 5000);
        }
    </script>
</body>
</html>
