<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Exams</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #f8f9fa;
            --primary-dark: #f1f3f5;
            --secondary: #e9ecef;
            --accent: #1a1f36;
            --accent-light: #2d3748;
            --text-primary: #1a1f36;
            --text-secondary: #4a5568;
            --border: #e2e8f0;
            --success: #31c48d;
            --warning: #f59e0b;
            --danger: #e53e3e;
            --shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            --radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.925rem;
            background-color: var(--primary-dark);
            color: var(--text-primary);
            line-height: 1.5;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        /* Sidebar styles (consistent with AddExamSubject.php) */
        .sidebar {
            background-color: var(--accent);
            color: var(--primary);
            padding: 2rem 1rem;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header i {
            font-size: 2rem;
            margin-right: 0.75rem;
            opacity: 0.9;
        }

        .sidebar-header h2 {
            font-size: 1.25rem;
            letter-spacing: -0.025em;
            font-weight: 600;
            opacity: 0.9;
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 2rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.675rem 1rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.9);
        }

        .sidebar-menu i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        /* Main Content */
        .main-content {
            grid-column: 2;
            padding: 2rem;
            background-color: var(--primary-dark);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Card styles */
        .card {
            background: var(--primary);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .card-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Form Elements */
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(30, 40, 55, 0.1);
            outline: none;
        }

        /* Table styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table th, .table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .table th {
            background-color: var(--primary-dark);
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Buttons */
        .btn {
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.8125rem;
        }

        .btn-primary {
            background-color: var(--accent);
            color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--accent-light);
        }

        .btn-danger {
            background-color: var(--danger);
            color: var(--primary);
        }

        .btn-danger:hover {
            background-color: #c53030;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--primary);
            border-radius: var(--radius);
            padding: 2rem;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
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

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .main-content {
                grid-column: 1;
                padding: 1.5rem;
            }

            .modal-content {
                width: 90%;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Results Management</h2>
            </div>
            <?= $this->include('shared/sidebar_menu') ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h3>View Exams</h3>
                    <select id="sessionFilter" class="form-control" style="width: 200px;">
                        <option value="">Select Session</option>
                    </select>
                </div>
                <div class="card-body">
                    <table class="table" id="examTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Exam Name</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Exam</h3>
            </div>
            <form id="editForm">
                <input type="hidden" id="editId">
                <div class="form-group">
                    <label>Exam Name</label>
                    <input type="text" id="editName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Exam Date</label>
                    <input type="date" id="editDate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Session</label>
                    <select id="editSession" class="form-control" required></select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="editStatus" class="form-control">
                        <option value="yes">Active</option>
                        <option value="no">Inactive</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete Exam</h3>
            </div>
            <p>Are you sure you want to delete this exam?</p>
            <input type="hidden" id="deleteId">
            <div class="modal-actions">
                <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadSessions();
            
            document.getElementById('sessionFilter').addEventListener('change', function() {
                loadExams(this.value);
            });

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateExam();
            });
        });

        function loadSessions() {
            fetch('<?= base_url('exam/view/getSessions') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const options = ['sessionFilter', 'editSession'].map(id => 
                            document.getElementById(id));
                        
                        options.forEach(select => {
                            select.innerHTML = '<option value="">Select Session</option>';
                            data.data.forEach(session => {
                                select.innerHTML += `<option value="${session.id}">${session.session}</option>`;
                            });
                        });
                    }
                });
        }

        function loadExams(sessionId) {
            if (!sessionId) return;
            
            fetch(`<?= base_url('exam/view/getExams') ?>?session_id=${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const tbody = document.querySelector('#examTable tbody');
                        tbody.innerHTML = '';
                        
                        data.data.forEach(exam => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${exam.id}</td>
                                    <td>${exam.exam_name}</td>
                                    <td>${exam.exam_date}</td>
                                    <td>${exam.is_active === 'yes' ? 'Active' : 'Inactive'}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="editExam(${exam.id})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteExam(${exam.id})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                });
        }

        function editExam(id) {
            // Find the row by iterating through table rows
            const rows = document.querySelectorAll('#examTable tbody tr');
            let row;
            rows.forEach(tr => {
                if (tr.cells[0].textContent == id) {
                    row = tr;
                }
            });

            if (row) {
                document.getElementById('editId').value = id;
                document.getElementById('editName').value = row.cells[1].textContent;
                document.getElementById('editDate').value = row.cells[2].textContent;
                document.getElementById('editStatus').value = row.cells[3].textContent.trim() === 'Active' ? 'yes' : 'no';
                document.getElementById('editSession').value = document.getElementById('sessionFilter').value;
                
                document.getElementById('editModal').style.display = 'flex';
            }
        }

        function updateExam() {
            const id = document.getElementById('editId').value;
            const data = {
                exam_name: document.getElementById('editName').value,
                exam_date: document.getElementById('editDate').value,
                session_id: document.getElementById('editSession').value,
                is_active: document.getElementById('editStatus').value
            };

            fetch(`<?= base_url('exam/view/update') ?>/${id}`, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success', 'Exam updated successfully', 'success');
                    closeModal('editModal');
                    loadExams(document.getElementById('sessionFilter').value);
                }
            });
        }

        function deleteExam(id) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function confirmDelete() {
            const id = document.getElementById('deleteId').value;
            
            fetch(`<?= base_url('exam/view/delete') ?>/${id}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success', 'Exam deleted successfully', 'success');
                    closeModal('deleteModal');
                    loadExams(document.getElementById('sessionFilter').value);
                }
            });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Add this script for expandable sidebar
        document.addEventListener('DOMContentLoaded', function () {
            const expandableLinks = document.querySelectorAll('.expandable');
            expandableLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    const toggleIcon = this.querySelector('.toggle-icon');
                    if (submenu.style.display === 'none' || submenu.style.display === '') {
                        submenu.style.display = 'block';
                        toggleIcon.classList.remove('fa-chevron-down');
                        toggleIcon.classList.add('fa-chevron-up');
                    } else {
                        submenu.style.display = 'none';
                        toggleIcon.classList.remove('fa-chevron-up');
                        toggleIcon.classList.add('fa-chevron-down');
                    }
                });
            });

            // Combine with your existing DOMContentLoaded event
            loadSessions();
            
            document.getElementById('sessionFilter').addEventListener('change', function() {
                loadExams(this.value);
            });

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateExam();
            });
        });
    </script>
</body>
</html>