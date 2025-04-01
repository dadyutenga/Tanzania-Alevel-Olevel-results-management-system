<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Exam Marks</title>
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

        /* Sidebar styles */
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

        .sidebar-menu {
            list-style: none;
            margin-top: 1rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.2s ease;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-menu i {
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 20px;
            text-align: center;
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
            margin-bottom: 1.5rem;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Card styles */
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
            background-color: var(--primary);
        }

        .card-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--accent);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.875rem;
            background-color: var(--primary);
            color: var(--text-primary);
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-light);
        }

        /* Button styles */
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
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--accent-light);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            opacity: 0.9;
        }

        /* Table styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .table th, .table td {
            padding: 0.875rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .table th {
            background-color: var(--primary-dark);
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Subject marks input styles */
        .subject-group {
            display: inline-block;
            margin: 0.5rem 1rem 0.5rem 0;
            vertical-align: top;
        }

        .subject-label {
            display: block;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.8125rem;
            margin-bottom: 0.25rem;
        }

        .marks-input {
            width: 80px;
            padding: 0.5rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            text-align: center;
            font-size: 0.875rem;
        }

        .marks-input:focus {
            border-color: var(--accent-light);
            outline: none;
        }

        .marks-max {
            color: var(--text-secondary);
            font-size: 0.75rem;
            margin-left: 0.25rem;
        }

        /* Form actions */
        .form-actions {
            margin-top: 1.5rem;
            display: flex;
            justify-content: flex-end;
        }

        /* Responsive adjustments */
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

            .subject-group {
                display: block;
                margin: 0.75rem 0;
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
            <?= view('shared/sidebar_menu') ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Add Exam Marks</h1>
            </div>

            <!-- Exam Selection Card -->
            <div class="card">
                <div class="card-header">
                    <h3>Select Exam Details</h3>
                </div>
                <div class="card-body">
                    <form id="examSelectionForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="session">Academic Session</label>
                                    <select id="session" class="form-control" required>
                                        <option value="">Select Session</option>
                                        <?php foreach ($sessions as $session): ?>
                                            <option value="<?= $session['id'] ?>" 
                                                <?= ($session['id'] == ($current_session['id'] ?? '')) ? 'selected' : '' ?>>
                                                <?= esc($session['session']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exam">Select Exam</label>
                                    <select id="exam" class="form-control" required>
                                        <option value="">Select Exam</option>
                                        <?php foreach ($exams as $exam): ?>
                                            <option value="<?= $exam['id'] ?>">
                                                <?= esc($exam['exam_name']) ?> 
                                                (<?= date('d-m-Y', strtotime($exam['exam_date'])) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class">Select Class</label>
                                    <select id="class" class="form-control" required>
                                        <option value="">Select Class</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Get Students
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Marks Entry Card -->
            <div class="card" id="marksEntryCard" style="display: none;">
                <div class="card-header">
                    <h3>Enter Marks</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Roll Number</th>
                                    <th>Subjects</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="marksTableBody">
                                <!-- Students and marks will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('session').addEventListener('change', loadExamAndClasses);
        document.getElementById('examSelectionForm').addEventListener('submit', loadStudents);

        async function loadExamAndClasses() {
            const sessionId = document.getElementById('session').value;
            if (!sessionId) return;

            try {
                // Load exams
                const examResponse = await fetch(`<?= base_url('exam/marks/exams/') ?>/${sessionId}`);
                const examData = await examResponse.json();
                
                updateSelect('exam', examData.data);

                // Load classes
                const classResponse = await fetch(`<?= base_url('exam/marks/classes/') ?>/${sessionId}`);
                const classData = await classResponse.json();
                
                updateSelect('class', classData.data);
            } catch (error) {
                Swal.fire('Error', 'Failed to load exam and class data', 'error');
            }
        }

        async function loadStudents(e) {
            e.preventDefault();
            
            const examId = document.getElementById('exam').value;
            const classId = document.getElementById('class').value;
            const sessionId = document.getElementById('session').value;

            if (!examId || !classId || !sessionId) {
                Swal.fire('Error', 'Please select all required fields', 'error');
                return;
            }

            try {
                // Load subjects first
                const subjectsResponse = await fetch(`<?= base_url('exam/marks/subjects') ?>?exam_id=${examId}`);
                const subjectsData = await subjectsResponse.json();

                if (!subjectsData.data.length) {
                    throw new Error('No subjects found for this exam');
                }

                // Load students
                const studentsResponse = await fetch(
                    `<?= base_url('exam/marks/students') ?>?exam_id=${examId}&class_id=${classId}&session_id=${sessionId}`
                );
                const studentsData = await studentsResponse.json();

                if (!studentsData.data.length) {
                    throw new Error('No students found for this class');
                }

                // Update the table
                updateMarksTable(studentsData.data, subjectsData.data);
                document.getElementById('marksEntryCard').style.display = 'block';
            } catch (error) {
                Swal.fire('Error', error.message || 'Failed to load data', 'error');
            }
        }

        function updateSelect(elementId, data) {
            const select = document.getElementById(elementId);
            select.innerHTML = `<option value="">Select ${elementId.charAt(0).toUpperCase() + elementId.slice(1)}</option>`;
            
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name || item.class || item.exam_name;
                select.appendChild(option);
            });
        }

        function updateMarksTable(students, subjects) {
            const tbody = document.getElementById('marksTableBody');
            tbody.innerHTML = '';

            students.forEach(student => {
                const tr = document.createElement('tr');
                const fullName = `${student.firstname} ${student.lastname}`.trim();
                
                let subjectsHtml = '<td><div class="subjects-container">';
                subjects.forEach(subject => {
                    subjectsHtml += `
                        <div class="subject-group">
                            <span class="subject-label">${subject.subject_name}</span>
                            <input type="number" 
                                class="marks-input" 
                                data-subject="${subject.id}"
                                data-student="${student.id}"
                                min="0" 
                                max="${subject.max_marks}"
                                placeholder="Marks">
                            <span class="marks-max">/ ${subject.max_marks}</span>
                        </div>
                    `;
                });
                subjectsHtml += '</div></td>';

                tr.innerHTML = `
                    <td>${fullName}</td>
                    <td>${student.roll_no || 'N/A'}</td>
                    ${subjectsHtml}
                    <td>
                        <button class="btn btn-success" onclick="saveMarks(${student.id})">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Load existing marks for each student
            students.forEach(student => loadExistingMarks(student.id));
        }

        async function loadExistingMarks(studentId) {
            const examId = document.getElementById('exam').value;
            try {
                const response = await fetch(`<?= base_url('exam/marks/existing') ?>/${examId}/${studentId}`);
                const result = await response.json();

                if (result.status === 'success' && result.data.length > 0) {
                    result.data.forEach(mark => {
                        const input = document.querySelector(
                            `input[data-subject="${mark.exam_subject_id}"][data-student="${studentId}"]`
                        );
                        if (input) {
                            input.value = mark.marks_obtained;
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading existing marks:', error);
            }
        }

        async function saveMarks(studentId) {
            const examId = document.getElementById('exam').value;
            const classId = document.getElementById('class').value;
            const sessionId = document.getElementById('session').value;

            const inputs = document.querySelectorAll(`input[data-student="${studentId}"]`);
            const marks = {};

            // Validate marks before saving
            let isValid = true;
            inputs.forEach(input => {
                const maxMarks = parseInt(input.max);
                const enteredMarks = input.value ? parseInt(input.value) : null;
                
                if (enteredMarks !== null && (enteredMarks < 0 || enteredMarks > maxMarks)) {
                    input.style.borderColor = 'var(--danger)';
                    isValid = false;
                } else {
                    input.style.borderColor = 'var(--border)';
                    marks[input.dataset.subject] = enteredMarks;
                }
            });

            if (!isValid) {
                Swal.fire('Error', 'Please enter valid marks (between 0 and max marks)', 'error');
                return;
            }

            try {
                const response = await fetch('<?= base_url('exam/marks/save') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        exam_id: examId,
                        student_id: studentId,
                        class_id: classId,
                        session_id: sessionId,
                        marks: JSON.stringify(marks)
                    })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire('Success', 'Marks saved successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to save marks');
                }
            } catch (error) {
                Swal.fire('Error', error.message || 'Failed to save marks', 'error');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sessionId = document.getElementById('session').value;
            if (sessionId) {
                loadExamAndClasses();
            }
        });
    </script>
</body>
</html>