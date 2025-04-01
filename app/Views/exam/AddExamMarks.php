<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Exam Marks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: #333;
            color: white;
            padding: 1rem;
        }

        .main-content {
            padding: 2rem;
            background: #f4f4f4;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            border: none;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 0.75rem;
            border: 1px solid #ddd;
        }

        .marks-input {
            width: 80px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Results Management</h2>
            </div>
            <?= view('shared/sidebar_menu') ?>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Add Exam Marks</h1>
            </div>

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

            <div class="card" id="marksEntryCard" style="display: none;">
                <div class="card-header">
                    <h3>Enter Marks</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">Student Name</th>
                                    <th style="width: 100px;">Roll Number</th>
                                    <th>Subjects</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="marksTableBody">
                                <!-- Students and marks will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <style>
                .subject-group {
                    display: inline-block;
                    margin: 5px 10px;
                }
                .subject-label {
                    font-weight: bold;
                    margin-right: 5px;
                }
                .marks-input {
                    width: 70px !important;
                    display: inline-block !important;
                    padding: 4px !important;
                    height: 30px !important;
                }
                .marks-max {
                    color: #666;
                    font-size: 0.9em;
                    margin-left: 3px;
                }
                .table td {
                    vertical-align: middle;
                }
                .btn-save {
                    background-color: #2196F3;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 4px;
                    cursor: pointer;
                }
                .btn-save:hover {
                    background-color: #1976D2;
                }
            </style>

            <script>
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
                                        class="form-control marks-input" 
                                        data-subject="${subject.id}"
                                        data-student="${student.id}"
                                        min="0" 
                                        max="${subject.max_marks}"
                                        placeholder="Enter marks">
                                    <span class="marks-max">/${subject.max_marks}</span>
                                </div>
                            `;
                        });
                        subjectsHtml += '</div></td>';

                        tr.innerHTML = `
                            <td>${fullName}</td>
                            <td>${student.roll_no || 'N/A'}</td>
                            ${subjectsHtml}
                            <td>
                                <button class="btn-save" onclick="saveMarks(${student.id})">
                                    Save
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });

                    // Load existing marks for each student
                    students.forEach(student => loadExistingMarks(student.id));
                }
            </script>
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

            inputs.forEach(input => {
                marks[input.dataset.subject] = input.value;
            });

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