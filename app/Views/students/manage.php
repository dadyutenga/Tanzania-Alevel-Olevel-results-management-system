<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Student Management</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .logout-section {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }

        .logout-link {
            color: #ef4444 !important;
        }

        .logout-link:hover {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem 1rem;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: -0.025em;
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
            text-decoration: none;
            font-size: 0.875rem;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .form-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
        }

        .form-card::before {
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

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h3 {
            color: var(--accent);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary);
            font-size: 1.25rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-group label .required {
            color: #dc3545;
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            transition: all 0.3s ease;
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .btn-primary {
            background-color: var(--primary);
            color: black;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
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

            .sidebar-toggle {
                display: block;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
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
            <?= $this->include("shared/sidebar_menu") ?>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="page-header">
                    <h1><i class="fas fa-user-edit"></i> <?= esc($title) ?></h1>
                    <a href="<?= base_url('students') ?>" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="form-card">
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-error">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-error">
                            <ul style="margin: 0; padding-left: 20px;">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= isset($student) ? base_url('students/update/' . $student['id']) : base_url('students/store') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="form-section">
                            <h3>Personal Information</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstname">First Name <span class="required">*</span></label>
                                    <input type="text" id="firstname" name="firstname" 
                                           value="<?= old('firstname', $student['firstname'] ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" id="middlename" name="middlename" 
                                           value="<?= old('middlename', $student['middlename'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="lastname">Last Name <span class="required">*</span></label>
                                    <input type="text" id="lastname" name="lastname" 
                                           value="<?= old('lastname', $student['lastname'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="dob">Date of Birth <span class="required">*</span></label>
                                    <input type="date" id="dob" name="dob" 
                                           value="<?= old('dob', $student['dob'] ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="gender">Gender <span class="required">*</span></label>
                                    <select id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" <?= (old('gender', $student['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= (old('gender', $student['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Female</option>
                                        <option value="other" <?= (old('gender', $student['gender'] ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="guardian_phone">Guardian Phone <span class="required">*</span></label>
                                    <input type="tel" id="guardian_phone" name="guardian_phone" 
                                           value="<?= old('guardian_phone', $student['guardian_phone'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Academic Information</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="session_id">Session <span class="required">*</span></label>
                                    <select id="session_id" name="session_id" required>
                                        <option value="">Select Session</option>
                                        <?php foreach ($sessions as $sess): ?>
                                            <option value="<?= $sess['id'] ?>" 
                                                    <?= (old('session_id', $studentSession['session_id'] ?? $currentSession['id'] ?? '') === $sess['id']) ? 'selected' : '' ?>>
                                                <?= esc($sess['session']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="class_id">Class <span class="required">*</span></label>
                                    <select id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= $class['id'] ?>" 
                                                    <?= (old('class_id', $studentSession['class_id'] ?? '') === $class['id']) ? 'selected' : '' ?>>
                                                <?= esc($class['class']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="section_id">Section <span class="required">*</span></label>
                                    <select id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                        <?php foreach ($sections as $section): ?>
                                            <option value="<?= $section['id'] ?>" 
                                                    <?= (old('section_id', $studentSession['section_id'] ?? '') === $section['id']) ? 'selected' : '' ?>>
                                                <?= esc($section['section']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="is_active">Status <span class="required">*</span></label>
                                    <select id="is_active" name="is_active" required>
                                        <option value="yes" <?= (old('is_active', $student['is_active'] ?? 'yes') === 'yes') ? 'selected' : '' ?>>Active</option>
                                        <option value="no" <?= (old('is_active', $student['is_active'] ?? 'yes') === 'no') ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="<?= base_url('students') ?>" class="btn btn-cancel">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?= isset($student) ? 'Update' : 'Save' ?> Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
            }
            
            // Submenu Toggle
            const menuItems = document.querySelectorAll('.sidebar-menu > li');
            
            menuItems.forEach(item => {
                const link = item.querySelector('.expandable');
                const submenu = item.querySelector('.submenu');
                const toggleIcon = item.querySelector('.toggle-icon');
                
                if (link && submenu) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        submenu.classList.toggle('show');
                        if (toggleIcon) {
                            toggleIcon.style.transform = submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                        }
                    });
                }
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>
