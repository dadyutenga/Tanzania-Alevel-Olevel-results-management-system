<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Search Student Results</title>
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

        .container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
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
                justify-content: center;
            }

            .results-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Search Student Exam Results</h1>
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
                
                <div class="form-group">
                    <label for="studentName">Student Name</label>
                    <input type="text" id="studentName" class="form-control" placeholder="Enter student name">
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="fetchStudentResults()">
                    <i class="fas fa-search"></i> Search Results
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
                    const tbody = document.getElementById('results-body');
                    tbody.innerHTML = '';

                    if (results.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="6" style="text-align: center;">No results found</td></tr>`;
                    } else {
                        results.forEach(result => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${result.full_name}</td>
                                <td>${result.class_name}</td>
                                <td>${result.section}</td>
                                <td>${result.total_points}</td>
                                <td>${result.division}</td>
                                <td>
                                    <button class="btn btn-primary" onclick="viewDetails(${result.student_id})">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }

                    container.style.display = 'block';
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
            </script>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
