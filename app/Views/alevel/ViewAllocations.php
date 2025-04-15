<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View A-Level Allocations</title>
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
            max-width: 1200px;
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

        .dashboard-card {
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

        .dashboard-card::before {
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

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .filters-section {
            margin-bottom: 2rem;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 0;
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
            background-color: var(--secondary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .search-box {
            max-width: 300px;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            min-width: 900px;
        }

        .table th {
            background: var(--primary);
            color: black;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
        }

        .table tbody tr {
            background-color: var(--card-bg);
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:nth-child(even) {
            background-color: var(--secondary);
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: rgba(74, 229, 74, 0.05);
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-info {
            background-color: #3b82f6;
            color: white;
        }

        .btn-info:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .text-muted {
            color: var(--text-secondary);
        }

        .d-block {
            display: block;
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

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background-color: var(--secondary);
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background-color: var(--card-bg);
            color: var(--text-primary);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: var(--primary);
            color: black;
            border-color: var(--primary);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: var(--primary);
            color: black;
            border-color: var(--primary);
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

        .swal2-cancel {
            background-color: var(--danger) !important;
            color: white !important;
            border-radius: var(--button-radius) !important;
        }

        .swal2-cancel:hover {
            background-color: #dc2626 !important;
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

            .card-header {
                flex-direction: column;
                gap: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .search-box {
                max-width: 100%;
            }

            .btn-sm {
                padding: 0.4rem 0.8rem;
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
                    <h1>A-Level Allocations</h1>
                    <p>View and manage A-Level combination allocations</p>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">A-Level Combination Allocations</h3>
                        <div class="card-tools">
                            <a href="<?= base_url('alevel/allocations') ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Allocation
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('message')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('message') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Search and Filter Section -->
                        <div class="filters-section">
                            <div class="filters-grid">
                                <div class="form-group">
                                    <label for="combination_filter">Filter by Combination</label>
                                    <select class="form-control" id="combination_filter">
                                        <option value="">All Combinations</option>
                                        <?php foreach ($combinations as $combination): ?>
                                            <option value="<?= $combination['id'] ?>">
                                                <?= esc($combination['combination_name']) ?> (<?= esc($combination['combination_code']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="class_filter">Filter by Class</label>
                                    <select class="form-control" id="class_filter">
                                        <option value="">All Classes</option>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="session_filter">Filter by Session</label>
                                    <select class="form-control" id="session_filter">
                                        <option value="">All Sessions</option>
                                        <?php foreach ($sessions as $session): ?>
                                            <option value="<?= $session['id'] ?>"><?= esc($session['session']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status_filter">Filter by Status</label>
                                    <select class="form-control" id="status_filter">
                                        <option value="">All Status</option>
                                        <option value="yes">Active</option>
                                        <option value="no">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-box">
                                <input type="text" id="search_input" class="form-control" placeholder="Search in any column...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Combination</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Session</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allocations as $index => $allocation): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <?= esc($allocation['combination_name']) ?>
                                                <small class="d-block text-muted"><?= esc($allocation['combination_code']) ?></small>
                                            </td>
                                            <td><?= esc($allocation['class_name']) ?></td>
                                            <td><?= esc($allocation['section_name'] ?? 'N/A') ?></td>
                                            <td><?= esc($allocation['session_name']) ?></td>
                                            <td>
                                                <span class="badge badge-<?= $allocation['is_active'] == 'yes' ? 'success' : 'danger' ?>">
                                                    <?= ucfirst($allocation['is_active']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('alevel/allocations/edit/' . $allocation['id']) ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?= $allocation['id'] ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($allocations)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No allocations found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
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

            // Initialize DataTable
            var table = $('.table').DataTable({
                pageLength: 10,
                order: [[1, 'asc']],
                dom: '<"top"l>rt<"bottom"ip>',
                language: {
                    search: '',
                    searchPlaceholder: 'Search...'
                }
            });

            // Custom search input
            $('#search_input').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filter by Combination
            $('#combination_filter').on('change', function() {
                var searchTerm = $(this).find('option:selected').text();
                if ($(this).val() === '') {
                    table.column(1).search('').draw();
                } else {
                    table.column(1).search(searchTerm).draw();
                }
            });

            // Filter by Class
            $('#class_filter').on('change', function() {
                var searchTerm = $(this).find('option:selected').text();
                if ($(this).val() === '') {
                    table.column(2).search('').draw();
                } else {
                    table.column(2).search(searchTerm).draw();
                }
            });

            // Filter by Session
            $('#session_filter').on('change', function() {
                var searchTerm = $(this).find('option:selected').text();
                if ($(this).val() === '') {
                    table.column(4).search('').draw();
                } else {
                    table.column(4).search(searchTerm).draw();
                }
            });

            // Filter by Status
            $('#status_filter').on('change', function() {
                var searchTerm = $(this).val() === '' ? '' : 
                                $(this).val() === 'yes' ? 'Active' : 'Inactive';
                table.column(5).search(searchTerm).draw();
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('alevel/allocations/delete/') ?>' + id;
                }
            });
        }
    </script>
</body>
</html>