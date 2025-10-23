<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Class Management</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
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
            background-color: var(--primary);
            color: black;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
            box-shadow: none;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        .results-container {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .results-table thead th {
            background-color: var(--secondary);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border);
        }

        .results-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background-color 0.2s;
        }

        .results-table tbody tr:hover {
            background-color: var(--secondary);
        }

        .results-table td {
            padding: 1rem;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.2);
            color: #16a34a;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #dc2626;
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

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: var(--radius);
            display: none;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
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

            .header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .sidebar-toggle {
                display: block;
            }

            .results-table {
                font-size: 0.85rem;
            }

            .results-table thead th,
            .results-table td {
                padding: 0.5rem;
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
                    <h1><?= esc($title) ?></h1>
                    <a href="<?= base_url('classes/sections/create') ?>" class="btn">
                        <i class="fas fa-plus"></i> Add New Section
                    </a>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" style="display: block;">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" style="display: block;">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="results-container">
                    <table class="results-table" id="classesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Section Name</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sectionsTableBody">
                            <tr>
                                <td colspan="5" style="text-align: center;">Loading sections...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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

            loadSections();
        });

        async function loadSections() {
            try {
                const response = await fetch('<?= base_url('classes/getSections') ?>');
                const result = await response.json();

                if (result.status === 'success') {
                    displaySections(result.data);
                } else {
                    throw new Error(result.message || 'Failed to load sections');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('sectionsTableBody').innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; color: #dc2626;">
                            Failed to load sections. Please refresh the page.
                        </td>
                    </tr>
                `;
            }
        }

        function displaySections(sections) {
            const tbody = document.getElementById('sectionsTableBody');
            
            if (sections.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center;">
                            No sections found. Click "Add New Section" to create one.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = sections.map((sec, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${sec.section}</td>
                    <td>
                        <span class="badge ${sec.is_active === 'yes' ? 'badge-success' : 'badge-danger'}">
                            ${sec.is_active === 'yes' ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>${new Date(sec.created_at).toLocaleDateString()}</td>
                    <td>
                        <a href="<?= base_url('classes/sections/edit/') ?>${sec.id}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button onclick="deleteSection('${sec.id}')" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        async function deleteSection(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`<?= base_url('classes/sections/delete/') ?>${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.status === 'success') {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        loadSections();
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to delete section'
                    });
                }
            }
        }
    </script>
</body>
</html>
