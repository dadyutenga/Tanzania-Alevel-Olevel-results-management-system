<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Exams</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #ffffff;
            --primary-dark: #f8f9fa;
            --secondary: #e9ecef;
            --accent: #1a1f36;
            --accent-light: #2d3748;
            --text-primary: #1a1f36;
            --text-secondary: #4a5568;
            --border: #e2e8f0;
            --success: #31c48d;
            --warning: #f59e0b;
            --danger: #e53e3e;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --radius: 6px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            background-color: var(--primary-dark);
            color: var(--text-primary);
            line-height: 1.5;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background-color: var(--accent);
            color: white;
            padding: 1.5rem 1rem;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0 0.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header i {
            font-size: 1.8rem;
            margin-right: 0.75rem;
            opacity: 0.9;
        }

        .sidebar-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            opacity: 0.9;
        }

        .main-content {
            grid-column: 2;
            padding: 2rem;
            background-color: var(--primary-dark);
        }

        .card {
            background: var(--primary);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-control {
            width: 200px;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.875rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 0.875rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .table th {
            background-color: var(--primary-dark);
            font-weight: 600;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--accent);
            color: white;
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: var(--primary);
            margin: 10% auto;
            padding: 20px;
            width: 50%;
            border-radius: var(--radius);
        }

        @media (max-width: 1024px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .main-content {
                grid-column: 1;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Management</h2>
            </div>
            <?= $this->include('shared/sidebar_menu') ?>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h3>View Exams</h3>
                    <select id="sessionFilter" class="form-control">
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
            <h3>Edit Exam</h3>
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
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" onclick="closeModal('editModal')">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Delete Exam</h3>
            <p>Are you sure you want to delete this exam?</p>
            <input type="hidden" id="deleteId">
            <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
            <button class="btn btn-primary" onclick="closeModal('deleteModal')">Cancel</button>
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
                                        <button class="btn btn-primary" onclick="editExam(${exam.id})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger" onclick="deleteExam(${exam.id})">
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
            const row = document.querySelector(`#examTable tr:has(td:first-child:contains(${id}))`);
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = row.cells[1].textContent;
            document.getElementById('editDate').value = row.cells[2].textContent;
            document.getElementById('editStatus').value = row.cells[3].textContent === 'Active' ? 'yes' : 'no';
            document.getElementById('editSession').value = document.getElementById('sessionFilter').value;
            
            document.getElementById('editModal').style.display = 'block';
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
            document.getElementById('deleteModal').style.display = 'block';
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
    </script>
</body>
</html>