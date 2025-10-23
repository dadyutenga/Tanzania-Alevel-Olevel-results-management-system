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

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h3 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4AE54A;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group label .required {
            color: #dc3545;
        }

        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .students-list {
            margin-top: 20px;
        }

        .student-entry {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            position: relative;
        }

        .student-entry h4 {
            color: #333;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-remove {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-remove:hover {
            background: #c82333;
        }

        .student-fields {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .student-fields input,
        .student-fields select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-add-student {
            background: #667eea;
            color: white;
            width: 100%;
            padding: 15px;
            font-size: 16px;
            margin-top: 10px;
        }

        .btn-add-student:hover {
            background: #5568d3;
        }

        .btn-primary {
            background: #4AE54A;
            color: white;
        }

        .btn-primary:hover {
            background: #3dd33d;
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background: #c82333;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .info-box i {
            color: #667eea;
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .form-card {
                padding: 20px;
            }

            .form-row,
            .student-fields {
                grid-template-columns: 1fr;
            }

            .form-actions {
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
                <h1><i class="fas fa-users-cog"></i> <?= esc($title) ?></h1>
                <a href="<?= base_url('students') ?>" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <div class="form-card">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <strong>Bulk Registration:</strong> Select class, section, and session, then add multiple students at once.
                </div>

                <div class="form-section">
                    <h3>Class Assignment</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="session_id">Session <span class="required">*</span></label>
                            <select id="session_id" required>
                                <option value="">Select Session</option>
                                <?php foreach ($sessions as $sess): ?>
                                    <option value="<?= $sess['id'] ?>" 
                                            <?= ($currentSession && $currentSession['id'] === $sess['id']) ? 'selected' : '' ?>>
                                        <?= esc($sess['session']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class_id">Class <span class="required">*</span></label>
                            <select id="class_id" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>">
                                        <?= esc($class['class']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section_id">Section <span class="required">*</span></label>
                            <select id="section_id" required>
                                <option value="">Select Section</option>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?= $section['id'] ?>">
                                        <?= esc($section['section']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Students Information</h3>
                    <div id="studentsList" class="students-list">
                        <!-- Students will be added here -->
                    </div>
                    <button type="button" class="btn btn-add-student" onclick="addStudentRow()">
                        <i class="fas fa-plus"></i> Add Student
                    </button>
                </div>

                <div class="form-actions">
                    <a href="<?= base_url('students') ?>" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-primary" onclick="submitBulkRegistration()">
                        <i class="fas fa-save"></i> Register All Students
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let studentCount = 0;

        function addStudentRow() {
            studentCount++;
            const studentsList = document.getElementById('studentsList');
            
            const studentEntry = document.createElement('div');
            studentEntry.className = 'student-entry';
            studentEntry.id = `student-${studentCount}`;
            studentEntry.innerHTML = `
                <h4>
                    Student #${studentCount}
                    <button type="button" class="btn-remove" onclick="removeStudent(${studentCount})">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </h4>
                <div class="student-fields">
                    <input type="text" name="firstname" placeholder="First Name *" required>
                    <input type="text" name="middlename" placeholder="Middle Name">
                    <input type="text" name="lastname" placeholder="Last Name *" required>
                    <input type="date" name="dob" placeholder="Date of Birth *" required>
                    <select name="gender" required>
                        <option value="">Select Gender *</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="tel" name="guardian_phone" placeholder="Guardian Phone *" required>
                </div>
            `;
            
            studentsList.appendChild(studentEntry);
        }

        function removeStudent(id) {
            const element = document.getElementById(`student-${id}`);
            if (element) {
                element.remove();
            }
        }

        function collectStudentsData() {
            const students = [];
            const studentEntries = document.querySelectorAll('.student-entry');
            
            studentEntries.forEach(entry => {
                const firstname = entry.querySelector('input[name="firstname"]').value.trim();
                const middlename = entry.querySelector('input[name="middlename"]').value.trim();
                const lastname = entry.querySelector('input[name="lastname"]').value.trim();
                const dob = entry.querySelector('input[name="dob"]').value;
                const gender = entry.querySelector('select[name="gender"]').value;
                const guardian_phone = entry.querySelector('input[name="guardian_phone"]').value.trim();
                
                if (firstname && lastname && dob && gender && guardian_phone) {
                    students.push({
                        firstname,
                        middlename,
                        lastname,
                        dob,
                        gender,
                        guardian_phone
                    });
                }
            });
            
            return students;
        }

        function submitBulkRegistration() {
            const session_id = document.getElementById('session_id').value;
            const class_id = document.getElementById('class_id').value;
            const section_id = document.getElementById('section_id').value;
            
            if (!session_id || !class_id || !section_id) {
                Swal.fire('Error!', 'Please select session, class, and section', 'error');
                return;
            }
            
            const students = collectStudentsData();
            
            if (students.length === 0) {
                Swal.fire('Error!', 'Please add at least one student', 'error');
                return;
            }
            
            Swal.fire({
                title: 'Confirm Registration',
                text: `You are about to register ${students.length} student(s). Continue?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4AE54A',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Yes, register them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    registerStudents(session_id, class_id, section_id, students);
                }
            });
        }

        function registerStudents(session_id, class_id, section_id, students) {
            Swal.fire({
                title: 'Registering Students...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const formData = new FormData();
            formData.append('session_id', session_id);
            formData.append('class_id', class_id);
            formData.append('section_id', section_id);
            formData.append('students', JSON.stringify(students));
            
            fetch('<?= base_url('students/store-bulk') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        html: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4AE54A'
                    }).then(() => {
                        window.location.href = '<?= base_url('students') ?>';
                    });
                } else {
                    let errorMessage = data.message;
                    if (data.errors && data.errors.length > 0) {
                        errorMessage += '<br><br><strong>Errors:</strong><br>';
                        errorMessage += data.errors.join('<br>');
                    }
                    Swal.fire('Error!', errorMessage, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Failed to register students', 'error');
            });
        }

        // Add first student row on load
        document.addEventListener('DOMContentLoaded', function() {
            addStudentRow();
        });
    </script>
</body>
</html>
