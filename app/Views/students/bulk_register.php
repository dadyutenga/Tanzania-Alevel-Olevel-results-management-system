<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Student Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
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

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--card-bg);
            border-right: 1px solid var(--border);
            padding: 1rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: all 0.3s ease;
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

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            position: relative;
            margin-bottom: 0.25rem;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background-color: var(--primary);
            color: black;
        }

        .sidebar-menu li a i {
            margin-right: 0.75rem;
            width: 16px;
            text-align: center;
        }

        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: var(--secondary);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu li a {
            padding: 0.5rem 1.5rem 0.5rem 2.5rem;
            font-size: 0.85rem;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            margin-left: auto;
        }

        .logout-section {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }

        .logout-link {
            color: #ef4444 !important;
        }

        .logout-link:hover {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem 1rem;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: -0.025em;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--button-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .form-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
        }

        .form-card::before {
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

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h3 {
            color: var(--accent);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary);
            font-size: 1.25rem;
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
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-group label .required {
            color: #dc3545;
        }

        .form-group select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            transition: all 0.3s ease;
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .students-list {
            margin-top: 20px;
        }

        .student-entry {
            background: var(--secondary);
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            position: relative;
            border: 1px solid var(--border);
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
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            transition: all 0.3s ease;
            background-color: var(--card-bg);
            color: var(--text-primary);
        }

        .student-fields input:focus,
        .student-fields select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .btn-add-student {
            background: var(--accent);
            color: white;
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            margin-top: 0.5rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .btn-add-student:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
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

        .btn-cancel {
            background: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .info-box {
            background: rgba(74, 229, 74, 0.1);
            border-left: 4px solid var(--primary);
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--radius);
        }

        .info-box i {
            color: var(--primary-dark);
            margin-right: 0.75rem;
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background: var(--primary);
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: var(--shadow);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem 0.5rem;
            }

            .container {
                padding: 0 0.5rem;
            }

            .sidebar-toggle {
                display: block;
            }

            .form-card {
                padding: 1.5rem;
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
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>ExamResults</span>
            </div>
            <?= $this->include("shared/sidebar_menu") ?>
        </div>

        <div class="main-content">
            <div class="container">
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
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
            }
            
            // Submenu Toggle
            const menuItems = document.querySelectorAll('.sidebar-menu > li');
            
            menuItems.forEach(item => {
                const link = item.querySelector('.expandable');
                const submenu = item.querySelector('.submenu');
                const toggleIcon = item.querySelector('.toggle-icon');
                
                if (link && submenu) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        submenu.classList.toggle('show');
                        if (toggleIcon) {
                            toggleIcon.style.transform = submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                        }
                    });
                }
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            });
            
            addStudentRow();
        });
    </script>
</body>
</html>
