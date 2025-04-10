<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - View Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Color Scheme */
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

        /* Sidebar */
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
            padding-bottom: 1rem;
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
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 2rem;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
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
            font-size: 1.5rem;
            letter-spacing: -0.025em;
        }

        /* Form Container */
        .form-container {
            background: var(--primary);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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

        .text-danger {
            color: var(--danger);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn i {
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--accent);
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--accent-light);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: #dde1e4;
        }

        /* Results Table */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: var(--primary);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .results-table th,
        .results-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .results-table th {
            background-color: var(--accent);
            color: white;
            font-weight: 500;
        }

        .results-table tr:last-child td {
            border-bottom: none;
        }

        .results-table tr:hover {
            background-color: var(--primary-dark);
        }

        /* Responsive Styles */
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

            .row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }

            .results-table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Additional utility classes */
        .bg-purple-soft { background-color: rgba(126, 87, 194, 0.1); }
        .text-purple { color: #7e57c2; }
        .bg-blue-soft { background-color: rgba(66, 153, 225, 0.1); }
        .text-blue { color: #4299e1; }
        .bg-orange-soft { background-color: rgba(245, 158, 11, 0.1); }
        .text-orange { color: #f59e0b; }
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
            <?= view('shared/sidebar_menu') ?>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>View Exam Results</h1>
            </div>
            
            <div class="form-container">
                <div class="row">
                    <div class="form-group">
                        <label for="session">Academic Session <span class="text-danger">*</span></label>
                        <select id="session" class="form-control" required>
                            <option value="">Select Session</option>
                            <?php foreach ($sessions as $session): ?>
                                <option value="<?= $session['id'] ?>"><?= $session['session'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="class">Class <span class="text-danger">*</span></label>
                        <select id="class" class="form-control" required>
                            <option value="">Select Class</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>"><?= $class['class'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="exam">Exam <span class="text-danger">*</span></label>
                        <select id="exam" class="form-control" required>
                            <option value="">Select Exam</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-primary" onclick="fetchResults()">
                        <i class="fas fa-search"></i> View Results
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="downloadResults()">
                        <i class="fas fa-download"></i> Download Results
                    </button>
                </div>

                <div id="results-container" style="display: none;">
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Total Points</th>
                                <th>Division</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="results-body">
                        </tbody>
                    </table>
                </div>

                <script>
                    document.getElementById('session').addEventListener('change', updateExamDropdown);
                    document.getElementById('class').addEventListener('change', updateExamDropdown);
                    
                    async function updateExamDropdown() {
                        const sessionId = document.getElementById('session').value;
                        const classId = document.getElementById('class').value;
                        
                        if (!sessionId || !classId) {
                            document.getElementById('exam').innerHTML = '<option value="">Select Exam</option>';
                            return;
                        }

                        try {
                            const response = await fetch(`<?= base_url('results/view/getExams') ?>?session_id=${sessionId}&class_id=${classId}`);
                            const data = await response.json();
                            
                            const examSelect = document.getElementById('exam');
                            examSelect.innerHTML = '<option value="">Select Exam</option>';
                            
                            if (data.status === 'success') {
                                data.data.forEach(exam => {
                                    const option = document.createElement('option');
                                    option.value = exam.exam_id;
                                    option.textContent = exam.exam_name;
                                    examSelect.appendChild(option);
                                });
                            }
                        } catch (error) {
                            console.error('Error fetching exams:', error);
                        }
                    }

                    async function fetchResults() {
                        const examId = document.getElementById('exam').value;
                        const classId = document.getElementById('class').value;
                        const sessionId = document.getElementById('session').value;

                        if (!examId || !classId || !sessionId) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please select all required fields'
                            });
                            return;
                        }

                        try {
                            const response = await fetch('<?= base_url('results/view/getFilteredResults') ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `exam_id=${examId}&class_id=${classId}&session_id=${sessionId}`
                            });

                            const data = await response.json();
                            if (data.status === 'success') {
                                displayResults(data.data);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Failed to fetch results'
                                });
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while fetching results'
                            });
                        }
                    }

                    function displayResults(results) {
                        const container = document.getElementById('results-container');
                        const tbody = document.getElementById('results-body');
                        tbody.innerHTML = '';

                        results.forEach(result => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${result.full_name}</td>
                                <td>${result.class_name}</td>
                                <td>${result.section}</td>
                                <td>${result.total_points}</td>
                                <td>${result.division}</td>
                                <td>
                                    <button class="btn btn-secondary" onclick="viewDetails(${result.student_id})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });

                        container.style.display = 'block';
                    }

                    async function downloadResults() {
                        const examId = document.getElementById('exam').value;
                        const classId = document.getElementById('class').value;
                        const sessionId = document.getElementById('session').value;

                        if (!examId || !classId || !sessionId) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please select all required fields'
                            });
                            return;
                        }

                        try {
                            const response = await fetch('<?= base_url('results/view/downloadPDF') ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `exam_id=${examId}&class_id=${classId}&session_id=${sessionId}`
                            });

                            if (response.ok) {
                                const blob = await response.blob();
                                const url = window.URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = 'class_results.pdf';
                                document.body.appendChild(a);
                                a.click();
                                window.URL.revokeObjectURL(url);
                                a.remove();
                            } else {
                                throw new Error('Failed to download PDF');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to download results'
                            });
                        }
                    }

                    async function viewDetails(studentId) {
                        const examId = document.getElementById('exam').value;
                        
                        try {
                            const response = await fetch('<?= base_url('results/view/getStudentSubjectMarks') ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `student_id=${studentId}&exam_id=${examId}`
                            });

                            const data = await response.json();
                            if (data.status === 'success') {
                                showSubjectMarksModal(data.data);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Failed to fetch subject marks'
                                });
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while fetching subject marks'
                            });
                        }
                    }

                    function showSubjectMarksModal(subjectMarks) {
                        let tableHtml = `
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Maximum Marks</th>
                                        <th>Marks Obtained</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        subjectMarks.forEach(mark => {
                            tableHtml += `
                                <tr>
                                    <td>${mark.subject_name}</td>
                                    <td>${mark.max_marks}</td>
                                    <td>${mark.marks_obtained}</td>
                                    <td>${mark.grade}</td>
                                </tr>
                            `;
                        });

                        tableHtml += `
                                </tbody>
                            </table>
                        `;

                        Swal.fire({
                            title: 'Subject-wise Marks',
                            html: tableHtml,
                            width: '800px',
                            showCloseButton: true,
                            showConfirmButton: false
                        });
                    }

                    // Add a new function to download individual student result
                    async function downloadStudentResult(studentId) {
                        const examId = document.getElementById('exam').value;
                        
                        try {
                            const response = await fetch('<?= base_url('results/view/downloadStudentPDF') ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `student_id=${studentId}&exam_id=${examId}`
                            });

                            if (response.ok) {
                                const blob = await response.blob();
                                const url = window.URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = 'student_result.pdf';
                                document.body.appendChild(a);
                                a.click();
                                window.URL.revokeObjectURL(url);
                                a.remove();
                            } else {
                                throw new Error('Failed to download PDF');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to download student result'
                            });
                        }
                    }
                </script>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add this script for expandable sidebar -->
    <script>
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
        });
    </script>
</body>
</html>