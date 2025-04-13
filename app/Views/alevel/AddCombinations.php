<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Manage A-Level Combinations</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Light Theme with Green Accents */
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

        .app-container {
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
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            min-height: 100vh;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .header p {
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        .logo {
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo i {
            color: var(--primary);
            font-size: 1.5rem;
        }

        .logo span {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        /* Sidebar Styles */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0.25rem 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        /* Icon styling for menu items */
        .sidebar-menu li a i:first-child {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
            color: var(--text-secondary);
        }

        /* Active and hover states */
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--text-primary);
        }

        /* Toggle icon styling */
        .toggle-icon {
            margin-left: auto;
            font-size: 0.875rem;
            color: var(--text-secondary);
            transition: transform 0.2s ease;
        }

        /* Submenu styling */
        .submenu {
            display: none;
            list-style: none;
            padding: 0.5rem 0;
            background-color: transparent;
        }

        .submenu li a {
            padding: 0.5rem 1.5rem 0.5rem 3.25rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .submenu li a:hover {
            background-color: rgba(74, 229, 74, 0.05);
            color: var(--text-primary);
        }

        /* Active states */
        .sidebar-menu li a.active {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--text-primary);
        }

        .sidebar-menu li a.active i:first-child {
            color: var(--primary);
        }

        /* Expanded state */
        .sidebar-menu li a.active .toggle-icon {
            transform: rotate(180deg);
        }

        /* Form Container */
        .form-container {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            position: relative;
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

        .form-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-title i {
            color: var(--primary);
        }

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 0.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
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

        .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.85rem 2rem;
            border-radius: var(--button-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn i {
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: black;
            font-weight: 700;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: var(--border);
        }

        /* Results Table */
        .results-table-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-top: 2rem;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
        }

        .results-table th,
        .results-table td {
            padding: 1rem;
            text-align: left;
        }

        .results-table th {
            background-color: var(--primary);
            color: black;
            font-weight: 700;
        }

        .results-table tr:nth-child(even) {
            background-color: var(--secondary);
        }

        .results-table tr:hover {
            background-color: rgba(74, 229, 74, 0.05);
        }

        .results-table td {
            border-bottom: 1px solid var(--border);
        }

        .results-table tr:last-child td {
            border-bottom: none;
        }

        .empty-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .empty-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--border);
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        /* SweetAlert2 Custom Styles */
        .swal2-popup {
            border-radius: var(--radius);
            padding: 2rem;
        }

        .swal2-title {
            color: var(--text-primary);
        }

        .swal2-confirm {
            background-color: var(--primary) !important;
            color: black !important;
            border-radius: var(--button-radius) !important;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3) !important;
        }

        .swal2-confirm:hover {
            background-color: var(--primary-dark) !important;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }

            .results-table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Sidebar toggle button */
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

    <div class="app-container">
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>ExamResults</span>
            </div>
            
            <ul class="sidebar-menu">
                <?php include(APPPATH . 'Views/shared/sidebar_menu.php'); ?>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="container">
                <div class="header">
            <h1>Manage A-Level Combinations</h1>
            <p>Add, edit, or delete A-Level subject combinations for your institution</p>
        </div>
        
        <div class="form-container">
            <h2 class="form-title"><i class="fas fa-plus-circle"></i> Add New Combination</h2>
            <?php if (session()->has('message')): ?>
                <div class="alert alert-success" style="background-color: rgba(74, 229, 74, 0.1); color: var(--primary-dark); padding: 1rem; margin-bottom: 1rem; border-radius: var(--radius);">
                    <?= session('message') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; margin-bottom: 1rem; border-radius: var(--radius);">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; margin-bottom: 1rem; border-radius: var(--radius);">
                    <ul>
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="<?= base_url('alevel/combinations/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="form-group">
                        <label for="combination_code">Combination Code <span class="required">*</span></label>
                        <input type="text" id="combination_code" name="combination_code" class="form-control" placeholder="e.g., PCM" required value="<?= old('combination_code') ?>">
                    </div>

                    <div class="form-group">
                        <label for="combination_name">Combination Name <span class="required">*</span></label>
                        <input type="text" id="combination_name" name="combination_name" class="form-control" placeholder="e.g., Physics, Chemistry, Maths" required value="<?= old('combination_name') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="is_active">Status <span class="required">*</span></label>
                        <select id="is_active" name="is_active" class="form-control" required>
                            <option value="yes" <?= old('is_active', 'yes') == 'yes' ? 'selected' : '' ?>>Active</option>
                            <option value="no" <?= old('is_active') == 'no' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Combination
                    </button>
                </div>
            </form>
        </div>

        <div class="results-table-container">
            <h2 class="form-title" style="padding: 1rem; margin-bottom: 0;"><i class="fas fa-list"></i> Existing Combinations</h2>
            <?php if (empty($combinations)): ?>
                <div class="empty-results">
                    <i class="fas fa-database"></i>
                    <p>No combinations found. Add a new combination to get started.</p>
                </div>
            <?php else: ?>
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Combination Code</th>
                            <th>Combination Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($combinations as $combination): ?>
                            <tr>
                                <td><?= esc($combination['combination_code']) ?></td>
                                <td><?= esc($combination['combination_name']) ?></td>
                                <td>
                                    <span class="badge badge-success"><?= esc($combination['is_active'] == 'yes' ? 'Active' : 'Inactive') ?></span>
                                </td>
                                <td>
                                    <a href="<?= base_url('alevel/combinations/edit/' . $combination['id']) ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; margin-right: 0.5rem;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-primary" style="padding: 0.5rem 1rem; background-color: #ef4444;" onclick="confirmDelete(<?= $combination['id'] ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            // Toggle sidebar on button click (mobile)
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Add expandable sidebar functionality
            const expandableLinks = document.querySelectorAll('.expandable');
            expandableLinks.forEach(link => {
                // Add toggle icon if not present
                if (!link.querySelector('.toggle-icon')) {
                    const icon = document.createElement('i');
                    icon.className = 'fas fa-chevron-down toggle-icon';
                    link.appendChild(icon);
                }

                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    const toggleIcon = this.querySelector('.toggle-icon');
                    
                    if (submenu) {
                        if (submenu.style.display === 'none' || submenu.style.display === '') {
                            // Close all other submenus first
                            document.querySelectorAll('.submenu').forEach(menu => {
                                if (menu !== submenu) {
                                    menu.style.display = 'none';
                                    const icon = menu.previousElementSibling.querySelector('.toggle-icon');
                                    if (icon) {
                                        icon.classList.remove('fa-chevron-up');
                                        icon.classList.add('fa-chevron-down');
                                    }
                                }
                            });

                            // Open this submenu
                            submenu.style.display = 'block';
                            toggleIcon.classList.remove('fa-chevron-down');
                            toggleIcon.classList.add('fa-chevron-up');
                        } else {
                            // Close this submenu
                            submenu.style.display = 'none';
                            toggleIcon.classList.remove('fa-chevron-up');
                            toggleIcon.classList.add('fa-chevron-down');
                        }
                    }
                });
            });

            // Set initial state for active menu items
            expandableLinks.forEach(link => {
                if (link.classList.contains('active')) {
                    const submenu = link.nextElementSibling;
                    const toggleIcon = link.querySelector('.toggle-icon');
                    if (submenu) {
                        submenu.style.display = 'block';
                        if (toggleIcon) {
                            toggleIcon.classList.remove('fa-chevron-down');
                            toggleIcon.classList.add('fa-chevron-up');
                        }
                    }
                }
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: 'Do you want to delete this combination? This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form to submit the delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `<?= base_url('alevel/combinations/delete') ?>/${id}`;
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '<?= csrf_token() ?>';
                    csrfToken.value = '<?= csrf_hash() ?>';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>