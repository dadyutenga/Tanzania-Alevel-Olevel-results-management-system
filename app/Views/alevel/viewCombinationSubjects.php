<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View A-Level Combination Subjects</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
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

        .filters-container {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            position: relative;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .filters-container::before {
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

        .filters-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filters-title i {
            color: var(--primary);
        }

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
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

        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background-color: var(--secondary);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 15px;
            color: var(--text-primary);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .results-table-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 1.5rem;
            margin-bottom: 2rem;
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            min-width: 700px;
        }

        .table thead th {
            background-color: var(--primary);
            color: #000000; /* Improved contrast */
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table tbody tr {
            background-color: var(--card-bg);
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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

        .table th:nth-child(1),
        .table td:nth-child(1) { width: 30%; } /* Combination */
        .table th:nth-child(2),
        .table td:nth-child(2) { width: 25%; } /* Subject Name */
        .table th:nth-child(3),
        .table td:nth-child(3) { width: 15%; } /* Type */
        .table th:nth-child(4),
        .table td:nth-child(4) { width: 15%; } /* Status */
        .table th:nth-child(5),
        .table td:nth-child(5) { width: 15%; } /* Actions */

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

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 0.5rem;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 0.875rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .status-badge {
            padding: 0.25rem 0.6rem; /* Reduced padding for better fit */
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-active {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .status-inactive {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* DataTables Custom Styles */
        .dataTables_wrapper .dataTables_length {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_length label {
            margin: 0;
            font-weight: 500;
            color: var(--text-primary);
        }

        .dataTables_wrapper .dataTables_length select {
            padding: 0.5rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background-color: var(--secondary);
            color: var(--text-primary);
            font-size: 0.875rem;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 200px;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background-color: var(--secondary);
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            justify-content: flex-end;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            color: var(--text-primary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: var(--primary);
            color: black;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: var(--primary);
            color: black;
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: var(--text-secondary);
            cursor: not-allowed;
        }

        /* Fix Sorting Arrows */
        .dataTables_wrapper .sorting::after,
        .dataTables_wrapper .sorting_asc::after,
        .dataTables_wrapper .sorting_desc::after {
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .sorting::after {
            content: '\f0dc'; /* Font Awesome sort icon */
            color: var(--text-secondary);
        }

        .dataTables_wrapper .sorting_asc::after {
            content: '\f062'; /* Font Awesome up arrow */
            color: var(--text-primary);
        }

        .dataTables_wrapper .sorting_desc::after {
            content: '\f063'; /* Font Awesome down arrow */
            color: var(--text-primary);
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

            .row {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .filters-container {
                padding: 1rem;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
            }

            .dataTables_wrapper .dataTables_paginate {
                justify-content: center;
            }
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
            .sidebar-toggle {
                display: block;
            }
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
            background-color: #ef4444 !important;
            color: white !important;
            border-radius: var(--button-radius) !important;
        }

        .swal2-cancel:hover {
            background-color: #dc2626 !important;
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
                    <h1>View A-Level Combination Subjects</h1>
                    <p>Search, filter, and manage subjects across different combinations</p>
                </div>

                <div class="filters-container">
                    <h3 class="filters-title">
                        <i class="fas fa-filter"></i> Filter Subjects
                    </h3>
                    <div class="row">
                        <div class="form-group">
                            <label for="combination-filter">Combination</label>
                            <select id="combination-filter" class="form-control select2">
                                <option value="">All Combinations</option>
                                <?php foreach ($combinations as $combination): ?>
                                    <option value="<?= esc($combination['id']) ?>">
                                        <?= esc($combination['combination_code']) ?> - <?= esc($combination['combination_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type-filter">Subject Type</label>
                            <select id="type-filter" class="form-control select2">
                                <option value="">All Types</option>
                                <option value="major">Major</option>
                                <option value="additional">Additional</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status-filter">Status</label>
                            <select id="status-filter" class="form-control select2">
                                <option value="">All Status</option>
                                <option value="yes">Active</option>
                                <option value="no">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="results-table-container">
                    <div class="table-header">
                        <h3 class="filters-title">
                            <i class="fas fa-list"></i> Subject List
                        </h3>
                        <a href="<?= base_url('alevel/subjects') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Subject
                        </a>
                    </div>
                    
                    <table id="subjects-table" class="table">
                        <thead>
                            <tr>
                                <th>Combination</th>
                                <th>Subject Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects as $subject): ?>
                                <tr>
                                    <td>
                                        <?php 
                                        $combination = array_filter($combinations, function($c) use ($subject) {
                                            return $c['id'] == $subject['combination_id'];
                                        });
                                        $combination = reset($combination);
                                        echo esc($combination['combination_code']) . ' - ' . esc($combination['combination_name']);
                                        ?>
                                    </td>
                                    <td><?= esc($subject['subject_name']) ?></td>
                                    <td><?= ucfirst(esc($subject['subject_type'])) ?></td>
                                    <td>
                                        <span class="status-badge <?= $subject['is_active'] == 'yes' ? 'status-active' : 'status-inactive' ?>">
                                            <?= $subject['is_active'] == 'yes' ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= base_url('alevel/subjects/edit/' . $subject['id']) ?>" 
                                               class="btn btn-primary btn-sm" 
                                               title="Edit Subject">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete(<?= $subject['id'] ?>)" 
                                                    class="btn btn-danger btn-sm"
                                                    title="Delete Subject">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        });

        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            const table = $('#subjects-table').DataTable({
                pageLength: 10,
                order: [[0, 'asc']],
                responsive: true,
                columnDefs: [
                    {
                        targets: 0,
                        searchable: true,
                        render: function(data, type, row) {
                            return data;
                        }
                    },
                    {
                        targets: 4, // Actions column
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#combination-filter').on('change', function() {
                const value = $(this).val();
                table.column(0).search(value ? '^' + value + '$' : '', true, false).draw();
            });

            $('#type-filter').on('change', function() {
                table.column(2).search(this.value).draw();
            });

            $('#status-filter').on('change', function() {
                table.column(3).search(this.value === 'yes' ? 'Active' : this.value === 'no' ? 'Inactive' : '').draw();
            });

            // Check for success messages from session
            <?php if (session()->has('edit_success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Subject edited successfully!',
                    confirmButtonColor: '#4AE54A'
                });
            <?php endif; ?>

            <?php if (session()->has('delete_success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Subject deleted successfully!',
                    confirmButtonColor: '#4AE54A'
                });
            <?php endif; ?>
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4AE54A',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `<?= base_url('alevel/subjects/delete') ?>/${id}`;
                    
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