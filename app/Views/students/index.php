<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Student Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        .page-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            color: #333;
            font-size: 24px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #4AE54A;
            color: white;
        }

        .btn-primary:hover {
            background: #3dd33d;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #667eea;
            color: white;
        }

        .btn-secondary:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .content-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #ffc107;
            color: white;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .btn-group {
                width: 100%;
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
    <div class="container">
        <?= view('shared/sidebar_menu') ?>

        <div class="main-content" id="mainContent">
            <div class="page-header">
                <h1><i class="fas fa-users"></i> <?= esc($title) ?></h1>
                <div class="btn-group">
                    <a href="<?= base_url('students/bulk-register') ?>" class="btn btn-secondary">
                        <i class="fas fa-users-cog"></i> Bulk Register
                    </a>
                    <a href="<?= base_url('students/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Student
                    </a>
                </div>
            </div>

            <div class="content-card">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search students by name, class, or section...">
                </div>

                <div class="table-container">
                    <table id="studentsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>DOB</th>
                                <th>Guardian Phone</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Session</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <tr>
                                <td colspan="10" class="loading">
                                    <i class="fas fa-spinner fa-spin"></i> Loading students...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        let allStudents = [];
        
        document.addEventListener('DOMContentLoaded', function() {
            loadStudents();
            setupSearch();
        });

        function loadStudents() {
            fetch('<?= base_url('students/getStudents') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        allStudents = data.data;
                        displayStudents(allStudents);
                    } else {
                        showError('Failed to load students');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load students');
                });
        }

        function displayStudents(students) {
            const tbody = document.getElementById('studentsTableBody');
            
            if (students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" style="text-align: center;">No students found</td></tr>';
                return;
            }

            tbody.innerHTML = students.map((student, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapeHtml(student.firstname)} ${escapeHtml(student.middlename || '')} ${escapeHtml(student.lastname)}</td>
                    <td>${escapeHtml(student.gender)}</td>
                    <td>${escapeHtml(student.dob)}</td>
                    <td>${escapeHtml(student.guardian_phone)}</td>
                    <td>${escapeHtml(student.class || 'N/A')}</td>
                    <td>${escapeHtml(student.section || 'N/A')}</td>
                    <td>${escapeHtml(student.session || 'N/A')}</td>
                    <td>
                        <span class="status-badge ${student.is_active === 'yes' ? 'status-active' : 'status-inactive'}">
                            ${student.is_active === 'yes' ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?= base_url('students/edit/') ?>${student.id}" class="btn-sm btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button onclick="deleteStudent('${student.id}')" class="btn-sm btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const filtered = allStudents.filter(student => {
                    const fullName = `${student.firstname} ${student.middlename || ''} ${student.lastname}`.toLowerCase();
                    const className = (student.class || '').toLowerCase();
                    const sectionName = (student.section || '').toLowerCase();
                    return fullName.includes(searchTerm) || 
                           className.includes(searchTerm) || 
                           sectionName.includes(searchTerm);
                });
                displayStudents(filtered);
            });
        }

        function deleteStudent(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the student and all related data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= base_url('students/delete/') ?>${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Deleted!', data.message, 'success');
                            loadStudents();
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Failed to delete student', 'error');
                    });
                }
            });
        }

        function showError(message) {
            Swal.fire('Error!', message, 'error');
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }
    </script>
</body>
</html>
