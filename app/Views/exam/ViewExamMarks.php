<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Exam Marks</title>
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
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .card-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body {
            padding: 0;
        }

        /* Form Elements */
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
                    <h3>View Exam Marks</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Session</label>
                        <select id="sessionFilter" class="form-control">
                            <option value="">Select Session</option>
                            <?php foreach ($sessions as $session): ?>
                                <option value="<?= $session['id'] ?>" <?= isset($current_session) && $current_session['id'] == $session['id'] ? 'selected' : '' ?>>
                                    <?= $session['session'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Exam</label>
                        <select id="examFilter" class="form-control">
                            <option value="">Select Exam</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Class</label>
                        <select id="classFilter" class="form-control">
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary" onclick="searchMarks()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                    <table class="table" id="marksTable">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Subject</th>
                                <th>Marks Obtained</th>
                                <th>Max Marks</th>
                                <th>Passing Marks</th>
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
                <h3>Edit Marks</h3>
            </div>
            <form id="editForm">
                <input type="hidden" id="editId">
                <div class="form-group">
                    <label>Student Name</label>
                    <input type="text" id="editStudentName" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" id="editSubject" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>Marks Obtained</label>
                    <input type="number" id="editMarks" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Maximum Marks</label>
                    <input type="number" id="editMaxMarks" class="form-control" readonly>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sessionFilter = document.getElementById('sessionFilter');
            sessionFilter.addEventListener('change', loadExams);
            
            document.getElementById('examFilter').addEventListener('change', loadClasses);
            
            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateMarks();
            });
        });

        function loadExams() {
            const sessionId = document.getElementById('sessionFilter').value;
            if (!sessionId) return;

            fetch(`<?= base_url('exam/marks/view/getExams') ?>?session_id=${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const examSelect = document.getElementById('examFilter');
                        examSelect.innerHTML = '<option value="">Select Exam</option>';
                        data.data.forEach(exam => {
                            examSelect.innerHTML += `<option value="${exam.id}">${exam.exam_name}</option>`;
                        });
                    }
                });
        }

        function loadClasses() {
            const examId = document.getElementById('examFilter').value;
            const sessionId = document.getElementById('sessionFilter').value;
            if (!examId || !sessionId) return;

            fetch(`<?= base_url('exam/marks/view/getExamClasses') ?>?exam_id=${examId}&session_id=${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const classSelect = document.getElementById('classFilter');
                        classSelect.innerHTML = '<option value="">Select Class</option>';
                        data.data.forEach(cls => {
                            classSelect.innerHTML += `<option value="${cls.class_id}">${cls.class_name}</option>`;
                        });
                    }
                });
        }

        function searchMarks() {
            const examId = document.getElementById('examFilter').value;
            const classId = document.getElementById('classFilter').value;
            const sessionId = document.getElementById('sessionFilter').value;

            if (!examId || !classId || !sessionId) {
                Swal.fire('Error', 'Please select all filters', 'error');
                return;
            }

            fetch(`<?= base_url('exam/marks/view/getStudentMarks') ?>?exam_id=${examId}&class_id=${classId}&session_id=${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const tbody = document.querySelector('#marksTable tbody');
                        tbody.innerHTML = '';
                        
                        data.data.forEach(mark => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${mark.roll_no}</td>
                                    <td>${mark.firstname} ${mark.lastname}</td>
                                    <td>${mark.subject_name}</td>
                                    <td>${mark.marks_obtained}</td>
                                    <td>${mark.max_marks}</td>
                                    <td>${mark.passing_marks}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="editMark(${mark.id}, '${mark.firstname} ${mark.lastname}', '${mark.subject_name}', ${mark.marks_obtained}, ${mark.max_marks})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteMark(${mark.id})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                });
        }

        function editMark(id, studentName, subject, marks, maxMarks) {
            document.getElementById('editId').value = id;
            document.getElementById('editStudentName').value = studentName;
            document.getElementById('editSubject').value = subject;
            document.getElementById('editMarks').value = marks;
            document.getElementById('editMaxMarks').value = maxMarks;
            
            document.getElementById('editModal').style.display = 'flex';
        }

        function updateMarks() {
            const id = document.getElementById('editId').value;
            const marks = document.getElementById('editMarks').value;
            const maxMarks = document.getElementById('editMaxMarks').value;

            if (parseFloat(marks) > parseFloat(maxMarks)) {
                Swal.fire('Error', 'Marks cannot be greater than maximum marks', 'error');
                return;
            }

            fetch(`<?= base_url('exam/marks/view/update') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `marks_obtained=${marks}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success', 'Marks updated successfully', 'success');
                    closeModal('editModal');
                    searchMarks();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }

        function deleteMark(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= base_url('exam/marks/view/delete') ?>/${id}`, {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Deleted!', 'Mark has been deleted.', 'success');
                            searchMarks();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>