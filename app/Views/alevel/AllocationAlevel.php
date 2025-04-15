<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add A-Level Allocation</title>
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
            --success: #31c48d;
            --danger: #ef4444;
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
            padding: 1.5rem 1rem;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .form-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards;
            animation-delay: 0.2s;
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

        .form-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .form-body {
            padding: 1.5rem;
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

        .form-group label .text-danger {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            background-color: var(--secondary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .is-invalid {
            border-color: var(--danger);
        }

        .is-valid {
            border-color: var(--success);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            justify-content: center;
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
        }

        .btn i {
            font-size: 1rem;
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

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: var(--primary);
            color: black;
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .alert ul {
            margin: 0;
            padding-left: 1.5rem;
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

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

            .form-header {
                flex-direction: column;
                gap: 1rem;
            }

            .form-body {
                padding: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
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
                    <h1>Add A-Level Allocation</h1>
                    <p>Assign a combination to a class and session</p>
                </div>
                
                <div class="form-container">
                    <div class="form-header">
                        <h3 class="form-title">
                            <i class="fas fa-plus-circle"></i>
                            Add New A-Level Combination Allocation
                        </h3>
                        <div class="form-tools">
                        </div>
                    </div>
                    <div class="form-body">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->has('errors')): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form id="allocationForm" class="needs-validation" novalidate action="<?= base_url('alevel/allocations/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="combination_id">Combination <span class="text-danger">*</span></label>
                                <select name="combination_id" id="combination_id" class="form-control" required>
                                    <option value="">Select Combination</option>
                                    <?php foreach ($combinations as $combination): ?>
                                        <option value="<?= $combination['id'] ?>">
                                            <?= esc($combination['combination_name']) ?> 
                                            (<?= esc($combination['combination_code']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="class_id">Class <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="form-control" required>
                                    <option value="">Select Class</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="section_id">Section (Optional)</label>
                                <select name="section_id" id="section_id" class="form-control">
                                    <option value="">Select Section</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="session_id">Session <span class="text-danger">*</span></label>
                                <select name="session_id" id="session_id" class="form-control" required>
                                    <option value="">Select Session</option>
                                    <?php foreach ($sessions as $session): ?>
                                        <option value="<?= $session['id'] ?>"><?= esc($session['session']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active">Status <span class="text-danger">*</span></label>
                                <select name="is_active" id="is_active" class="form-control" required>
                                    <option value="yes">Active</option>
                                    <option value="no">Inactive</option>
                                </select>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Allocation
                                </button>
                                <a href="<?= base_url('alevel/allocations') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const form = document.getElementById('allocationForm');
            const classSelect = document.getElementById('class_id');
            const sectionSelect = document.getElementById('section_id');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
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
                    
                    link.addEventListener('click', function(e) {
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

            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            });

            classSelect.addEventListener('change', function() {
                const classId = this.value;
                sectionSelect.innerHTML = '<option value="">Select Section</option>';
                
                if (classId) {
                    fetch('<?= base_url('alevel/allocations/get-sections') ?>?class_id=' + classId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.sections) {
                                data.sections.forEach(section => {
                                    const option = document.createElement('option');
                                    option.value = section.id;
                                    option.textContent = section.section_name;
                                    sectionSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to load sections',
                                confirmButtonColor: '#4AE54A'
                            });
                        });
                }
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: result.message || 'Allocation saved successfully',
                            confirmButtonColor: '#4AE54A',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= base_url('alevel/allocations') ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message || 'Failed to save allocation',
                            confirmButtonColor: '#4AE54A'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while saving the allocation',
                        confirmButtonColor: '#4AE54A'
                    });
                }
            });

            const inputs = form.querySelectorAll('select');
            inputs.forEach(input => {
                input.addEventListener('invalid', function() {
                    input.classList.add('is-invalid');
                });
                input.addEventListener('change', function() {
                    if (input.validity.valid) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                });
            });
        });
    </script>
</body>
</html>