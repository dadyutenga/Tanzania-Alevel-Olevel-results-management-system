<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Search Student Results</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Vibrant Theme */
        :root {
            --bg-color: #f0f4f8;
            --card-bg: #ffffff;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --secondary: #f0f4f8;
            --accent: #10b981;
            --accent-hover: #059669;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-light: #f8fafc;
            --border: #e2e8f0;
            --table-header: #6366f1;
            --table-stripe: #f8fafc;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius: 16px;
            --button-radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
            padding: 2rem;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.05) 0%, transparent 20%);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .logo i {
            font-size: 2rem;
            color: var(--primary);
        }

        .logo span {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Search Card */
        .search-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .search-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 0.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--button-radius);
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        /* Button Styles */
        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.85rem 2rem;
            border-radius: var(--button-radius);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2), 0 2px 4px -1px rgba(99, 102, 241, 0.1);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 10px -1px rgba(99, 102, 241, 0.3), 0 4px 6px -1px rgba(99, 102, 241, 0.15);
        }

        .btn-accent {
            background-color: var(--accent);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2), 0 2px 4px -1px rgba(16, 185, 129, 0.1);
        }

        .btn-accent:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 10px -1px rgba(16, 185, 129, 0.3), 0 4px 6px -1px rgba(16, 185, 129, 0.15);
        }

        /* Results Table */
        .results-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .results-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--accent), var(--primary));
        }

        .results-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .results-title i {
            color: var(--accent);
        }

        .results-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1rem;
        }

        .results-table th,
        .results-table td {
            padding: 1rem;
            text-align: left;
        }

        .results-table th {
            background-color: var(--table-header);
            color: white;
            font-weight: 600;
            position: relative;
        }

        .results-table th:first-child {
            border-top-left-radius: 8px;
        }

        .results-table th:last-child {
            border-top-right-radius: 8px;
        }

        .results-table tr:nth-child(even) {
            background-color: var(--table-stripe);
        }

        .results-table tr:hover {
            background-color: rgba(99, 102, 241, 0.05);
        }

        .results-table td {
            border-bottom: 1px solid var(--border);
        }

        .results-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .empty-results {
            text-align: center;
            padding: 3rem 0;
            color: var(--text-secondary);
        }

        .empty-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .empty-results p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .search-card, 
            .results-container {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.75rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .results-table {
                display: block;
                overflow-x: auto;
            }
        }

        /* SweetAlert2 Custom Styles */
        .swal2-popup {
            border-radius: var(--radius);
            padding: 2rem;
        }

        .swal2-title {
            color: var(--text-primary);
            font-weight: 600;
        }

        .swal2-html-container {
            color: var(--text-secondary);
        }

        .swal2-confirm {
            background-color: var(--primary) !important;
            border-radius: var(--button-radius) !important;
        }

        .swal2-confirm:hover {
            background-color: var(--primary-hover) !important;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>ExamResults</span>
            </div>
            <h1>Student Results Portal</h1>
            <p>Search and view examination results for all students across different academic sessions</p>
        </div>
        
        <div class="search-card animate-fade-in">
            <h2 class="form-title"><i class="fas fa-filter"></i> Filter Results</h2>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="session">Academic Session <span class="required">*</span></label>
                    <select id="session" class="form-control" required>
                        <option value="">Select Session</option>
                        <?php foreach ($sessions as $session): ?>
                            <option value="<?= $session['id'] ?>"><?= $session['session'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="class">Class <span class="required">*</span></label>
                    <select id="class" class="form-control" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= $class['class'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="exam">Exam <span class="required">*</span></label>
                    <select id="exam" class="form-control" required>
                        <option value="">Select Exam</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="studentName">Student Name</label>
                    <input type="text" id="studentName" class="form-control" placeholder="Enter student name">
                </div>
            </div>

            <div class="btn-container">
                <button type="button" class="btn btn-primary" onclick="fetchStudentResults()">
                    <i class="fas fa-search"></i> Search Results
                </button>
            </div>
        </div>

        <div id="results-container" class="results-container animate-fade-in" style="display: none;">
            <h2 class="results-title"><i class="fas fa-list-check"></i> Search Results</h2>
            
            <div id="empty-results" class="empty-results" style="display: none;">
                <i class="fas fa-search"></i>
                <p>No results found matching your search criteria</p>
                <button class="btn btn-primary" onclick="document.getElementById('studentName').focus()">
                    <i class="fas fa-filter"></i> Modify Search
                </button>
            </div>
            
            <table id="results-table" class="results-table" style="display: none;">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation classes
            document.querySelectorAll('.animate-fade-in').forEach((el, i) => {
                el.style.opacity = '0';
                el.style.animation = 'none';
                setTimeout(() => {
                    el.style.animation = `fadeIn 0.5s ease forwards ${i * 0.1}s`;
                }, 100);
            });
        });
        
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
                const response = await fetch(`<?= base_url('public/results/getExams') ?>?session_id=${sessionId}&class_id=${classId}`);
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

        async function fetchStudentResults() {
            const examId = document.getElementById('exam').value;
            const classId = document.getElementById('class').value;
            const sessionId = document.getElementById('session').value;
            const studentName = document.getElementById('studentName').value;

            if (!examId || !classId || !sessionId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select all required fields'
                });
                return;
            }

            try {
                let bodyData = `exam_id=${examId}&class_id=${classId}&session_id=${sessionId}`;
                if (studentName) {
                    bodyData += `&student_name=${encodeURIComponent(studentName)}`;
                }
                
                const response = await fetch('<?= base_url('public/results/getFilteredStudentResults') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: bodyData
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
            const table = document.getElementById('results-table');
            const emptyResults = document.getElementById('empty-results');
            const tbody = document.getElementById('results-body');
            
            container.style.display = 'block';
            tbody.innerHTML = '';

            if (results.length === 0) {
                table.style.display = 'none';
                emptyResults.style.display = 'block';
            } else {
                emptyResults.style.display = 'none';
                table.style.display = 'table';
                
                results.forEach(result => {
                    const row = document.createElement('tr');
                    
                    // Determine badge class based on division
                    let badgeClass = 'badge-success';
                    if (result.division === 'III' || result.division === 'IV') {
                        badgeClass = 'badge-warning';
                    } else if (result.division === 'F') {
                        badgeClass = 'badge-danger';
                    }
                    
                    row.innerHTML = `
                        <td>${result.full_name}</td>
                        <td>${result.class_name}</td>
                        <td>${result.section}</td>
                        <td>${result.total_points}</td>
                        <td><span class="badge ${badgeClass}">${result.division}</span></td>
                        <td>
                            <button class="btn btn-accent" onclick="viewDetails(${result.student_id})">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        }

        async function viewDetails(studentId) {
            const examId = document.getElementById('exam').value;
            
            try {
                const response = await fetch('<?= base_url('public/results/getStudentSubjectMarks') ?>', {
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
                <div style="max-height: 400px; overflow-y: auto;">
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
                // Determine grade color
                let gradeColor = '#10b981'; // Default green for A grades
                if (mark.grade === 'B' || mark.grade === 'C') {
                    gradeColor = '#6366f1'; // Purple for B and C grades
                } else if (mark.grade === 'D') {
                    gradeColor = '#f59e0b'; // Orange for D grade
                } else if (mark.grade === 'F') {
                    gradeColor = '#ef4444'; // Red for F grade
                }
                
                tableHtml += `
                    <tr>
                        <td>${mark.subject_name}</td>
                        <td>${mark.max_marks}</td>
                        <td>${mark.marks_obtained}</td>
                        <td><span style="font-weight: 600; color: ${gradeColor};">${mark.grade}</span></td>
                    </tr>
                `;
            });

            tableHtml += `
                        </tbody>
                    </table>
                </div>
            `;

            Swal.fire({
                title: 'Subject-wise Marks',
                html: tableHtml,
                width: '800px',
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>