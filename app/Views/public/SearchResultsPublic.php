<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Search Student Results</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Light Theme with Green Accents */
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

        .container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .header p {
            color: var(--text-secondary);
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

        /* Form Container */
        .form-container {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            position: relative;
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

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 0.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
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

        .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.85rem 2rem;
            border-radius: var(--button-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
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

        /* Results Table */
        .results-table-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-top: 2rem;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
        }

        .results-table th,
        .results-table td {
            padding: 1rem;
            text-align: left;
        }

        .results-table th {
            background-color: var(--primary);
            color: black;
            font-weight: 600;
        }

        .results-table tr:nth-child(even) {
            background-color: var(--secondary);
        }

        .results-table tr:hover {
            background-color: rgba(74, 229, 74, 0.05);
        }

        .results-table td {
            border-bottom: 1px solid var(--border);
        }

        .results-table tr:last-child td {
            border-bottom: none;
        }

        .empty-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .empty-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--border);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
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

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>ExamResults</span>
            </div>
            <h1>Search Student Exam Results</h1>
            <p>Find and view detailed examination results for all students</p>
        </div>
        
        <div class="form-container">
            <h2 class="form-title"><i class="fas fa-filter"></i> Search Filters</h2>
            
            <div class="row">
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
            </div>

            <div class="row">
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

            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="fetchStudentResults()">
                    <i class="fas fa-search"></i> Search Results
                </button>
                <a href="<?= base_url('/') ?>" class="btn" style="background-color: var(--secondary); color: var(--text-primary); border: 1px solid var(--border);">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
        </div>

        <div id="results-container" style="display: none;">
            <div id="empty-results" class="empty-results" style="display: none;">
                <i class="fas fa-search"></i>
                <p>No results found matching your search criteria</p>
            </div>
            
            <div id="results-table-container" class="results-table-container" style="display: none;">
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
        </div>
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
            const tableContainer = document.getElementById('results-table-container');
            const emptyResults = document.getElementById('empty-results');
            const tbody = document.getElementById('results-body');
            
            container.style.display = 'block';
            tbody.innerHTML = '';

            if (results.length === 0) {
                tableContainer.style.display = 'none';
                emptyResults.style.display = 'block';
            } else {
                emptyResults.style.display = 'none';
                tableContainer.style.display = 'block';
                
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
                            <button class="btn btn-primary" style="padding: 0.5rem 1rem; margin-right: 0.5rem;" onclick="viewDetails(${result.student_id})">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-primary" style="padding: 0.5rem 1rem; background-color: #3b82f6;" onclick="viewReportCard(${result.student_id})">
                                <i class="fas fa-file-alt"></i> Report Card
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
                let gradeColor = '#3AD03A'; // Default green for A grades
                if (mark.grade === 'B' || mark.grade === 'C') {
                    gradeColor = '#3b82f6'; // Blue for B and C grades
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
            `;

            Swal.fire({
                title: 'Subject-wise Marks',
                html: tableHtml,
                width: '800px',
                showCloseButton: true,
                showConfirmButton: false
            });
        }

        async function viewReportCard(studentId) {
            const examId = document.getElementById('exam').value;
            
            try {
                const response = await fetch('<?= base_url('public/results/getReportCard') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `student_id=${studentId}&exam_id=${examId}`
                });

                if (response.ok) {
                    // Check if the response is JSON (error case) or PDF (success case)
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to generate report card'
                        });
                    } else {
                        // Handle PDF download
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = `report_card_${studentId}.pdf`;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        window.URL.revokeObjectURL(url);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to download report card'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while generating the report card'
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>