<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Add A-Level Marks</title>
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

        .app-container {
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

        .main-content {
            flex: 1;
            margin-left: 250px;
            min-height: 100vh;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
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

        /* Sidebar Styles */
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

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: var(--border);
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
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

        /* Sidebar toggle button */
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
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="app-container">
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>ExamResults</span>
            </div>
            
            <ul class="sidebar-menu">
                <?php include(APPPATH . 'Views/shared/sidebar_menu.php'); ?>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>Add A-Level Marks</h1>
                    <p>Record and manage marks for A-Level students</p>
                </div>
                
                <div class="form-container">
                    <h2 class="form-title"><i class="fas fa-plus-circle"></i> Add Student Marks</h2>
                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success" style="background-color: rgba(74, 229, 74, 0.1); color: var(--primary-dark); padding: 1rem; margin-bottom: 1rem; border-radius: var(--radius);">
                            <?= session('message') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; margin-bottom: 1rem; border-radius: var(--radius);">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; margin-bottom: 1rem; border-radius: var(--radius);">
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form id="marksForm" action="<?= base_url('alevel/marks/save') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="form-group">
                                <label for="session_id">Session <span class="required">*</span></label>
                                <select id="session_id" name="session_id" class="form-control" required>
                                    <option value="">Select Session</option>
                                    <?php foreach ($sessions as $session): ?>
                                        <option value="<?= $session['id'] ?>" <?= isset($current_session) && $current_session['id'] == $session['id'] ? 'selected' : '' ?>><?= esc($session['session']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exam_id">Exam <span class="required">*</span></label>
                                <select id="exam_id" name="exam_id" class="form-control" required>
                                    <option value="">Select Exam</option>
                                    <?php if (isset($exams)): ?>
                                        <?php foreach ($exams as $exam): ?>
                                            <option value="<?= $exam['id'] ?>"><?= esc($exam['exam_name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="class_id">Class <span class="required">*</span></label>
                                <select id="class_id" name="class_id" class="form-control" required>
                                    <option value="">Select Class</option>
                                    <?php if (isset($classes)): ?>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="combination_id">Combination <span class="required">*</span></label>
                                <select id="combination_id" name="combination_id" class="form-control" required>
                                    <option value="">Select Combination</option>
                                </select>
                            </div>
                        </div>

                        <div id="marksContainer" style="display: none;">
                            <h3 style="margin-top: 1.5rem; margin-bottom: 1rem; font-size: 1.1rem; color: var(--text-primary);">Enter Marks</h3>
                            <div id="subjectsMarks" class="row">
                                <!-- Marks input fields will be dynamically added here -->
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-primary" id="getStudentsBtn">
                                <i class="fas fa-search"></i> Get Students
                            </button>
                            <button type="submit" class="btn btn-primary" id="saveMarksBtn" disabled style="display: none;">
                                <i class="fas fa-save"></i> Save Marks
                            </button>
                        </div>
                    </form>
                </div>

                <div class="results-table-container">
                    <h2 class="form-title" style="padding: 1rem; margin-bottom: 0;"><i class="fas fa-list"></i> Students Marks Entry</h2>
                    <div id="studentsMarksTable">
                        <div class="empty-results">
                            <i class="fas fa-database"></i>
                            <p>Select session, exam, class, and combination, then click "Get Students" to load student data.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    // Toggle sidebar on button click (mobile)
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Handle submenu toggling
    const menuItems = document.querySelectorAll('.sidebar-menu > li');
    
    menuItems.forEach(item => {
        const link = item.querySelector('.expandable');
        const submenu = item.querySelector('.submenu');
        
        if (link && submenu) {
            if (!link.querySelector('.toggle-icon')) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-chevron-up toggle-icon';
                link.appendChild(icon);
            }
            
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const toggleIcon = this.querySelector('.toggle-icon');
                
                document.querySelectorAll('.submenu').forEach(menu => {
                    if (menu !== submenu) {
                        menu.classList.remove('show');
                        const otherIcon = menu.previousElementSibling.querySelector('.toggle-icon');
                        if (otherIcon) {
                            otherIcon.style.transform = 'rotate(0deg)';
                        }
                    }
                });
                
                submenu.classList.toggle('show');
                toggleIcon.style.transform = submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
            });
        }
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
            sidebar.classList.remove('show');
        }
    });

    // Form elements
    const sessionSelect = document.getElementById('session_id');
    const examSelect = document.getElementById('exam_id');
    const classSelect = document.getElementById('class_id');
    const combinationSelect = document.getElementById('combination_id');
    const marksContainer = document.getElementById('marksContainer');
    const subjectsMarks = document.getElementById('subjectsMarks');
    const saveMarksBtn = document.getElementById('saveMarksBtn');
    const getStudentsBtn = document.getElementById('getStudentsBtn');
    const studentsMarksTable = document.getElementById('studentsMarksTable');

    // CSRF token
    const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;
    const csrfHeader = '<?= csrf_header() ?>';
    
    function debugLog(message, data = null) {
        console.log(`Debug: ${message}`, data);
    }

    // Session change: Load exams and classes
    sessionSelect.addEventListener('change', function() {
        debugLog('Session change event triggered');
        const sessionId = this.value;
        debugLog('Selected session ID:', sessionId);

        if (sessionId) {
            const baseUrl = '<?= base_url() ?>';
            const examsUrl = `${baseUrl}/alevel/marks/getExams/${sessionId}`;
            debugLog('Exams URL:', examsUrl);

            examSelect.disabled = true;
            examSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(examsUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeader]: csrfToken
                },
                credentials: 'same-origin'
            })
            .then(response => {
                debugLog('Exams response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                debugLog('Exams data received:', data);
                examSelect.innerHTML = '<option value="">Select Exam</option>';
                examSelect.disabled = false;

                if (data.status === 'success' && Array.isArray(data.data)) {
                    data.data.forEach(exam => {
                        const option = document.createElement('option');
                        option.value = exam.id;
                        option.textContent = exam.exam_name;
                        examSelect.appendChild(option);
                    });
                } else {
                    throw new Error('Invalid data format received');
                }
            })
            .catch(error => {
                debugLog('Error in exams fetch:', error);
                examSelect.disabled = false;
                examSelect.innerHTML = '<option value="">Error loading exams</option>';
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load exams: ' + error.message
                });
            });

            const classesUrl = `${baseUrl}/alevel/marks/getClasses/${sessionId}`;
            debugLog('Classes URL:', classesUrl);

            classSelect.disabled = true;
            classSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(classesUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeader]: csrfToken
                },
                credentials: 'same-origin'
            })
            .then(response => {
                debugLog('Classes response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                debugLog('Classes data received:', data);
                classSelect.innerHTML = '<option value="">Select Class</option>';
                classSelect.disabled = false;

                if (data.status === 'success' && Array.isArray(data.data)) {
                    data.data.forEach(cls => {
                        const option = document.createElement('option');
                        option.value = cls.id;
                        option.textContent = cls.class;
                        classSelect.appendChild(option);
                    });
                } else {
                    throw new Error('Invalid data format received');
                }
            })
            .catch(error => {
                debugLog('Error in classes fetch:', error);
                classSelect.disabled = false;
                classSelect.innerHTML = '<option value="">Error loading classes</option>';
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load classes: ' + error.message
                });
            });
        }

        combinationSelect.innerHTML = '<option value="">Select Combination</option>';
        marksContainer.style.display = 'none';
        saveMarksBtn.disabled = true;
        saveMarksBtn.style.display = 'none';
        getStudentsBtn.style.display = 'inline-flex';
    });

    if (sessionSelect.value) {
        debugLog('Triggering initial session change');
        sessionSelect.dispatchEvent(new Event('change'));
    }

    // Class change: Load combinations
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        const sessionId = sessionSelect.value;
        debugLog('Class selected:', { classId, sessionId });

        if (classId && sessionId) {
            const combinationsUrl = `<?= base_url('alevel/marks/getCombinations') ?>/${sessionId}/${classId}`;
            debugLog('Fetching combinations from:', combinationsUrl);

            fetch(combinationsUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeader]: csrfToken
                }
            })
            .then(response => {
                debugLog('Combinations response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                debugLog('Combinations data:', data);
                combinationSelect.innerHTML = '<option value="">Select Combination</option>';
                if (data.status === 'success') {
                    data.data.forEach(combination => {
                        const option = document.createElement('option');
                        option.value = combination.id;
                        option.textContent = `${combination.combination_code} - ${combination.combination_name}`;
                        combinationSelect.appendChild(option);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to load combinations: ' + (data.message || 'Unknown error')
                    });
                }
            })
            .catch(error => {
                debugLog('Error fetching combinations:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error fetching combinations: ' + error.message
                });
            });
        }

        marksContainer.style.display = 'none';
        saveMarksBtn.disabled = true;
        saveMarksBtn.style.display = 'none';
        getStudentsBtn.style.display = 'inline-flex';
    });

    // Get Students button: Load students and subjects
    getStudentsBtn.addEventListener('click', function() {
        const combinationId = combinationSelect.value;
        const classId = classSelect.value;
        const sessionId = sessionSelect.value;
        const examId = examSelect.value;
        debugLog('Get Students clicked with:', { combinationId, classId, sessionId, examId });

        if (combinationId && classId && sessionId && examId) {
            // Load students
            const studentsUrl = `<?= base_url('alevel/marks/getStudents') ?>?exam_id=${examId}&class_id=${classId}&session_id=${sessionId}&combination_id=${combinationId}`;
            debugLog('Fetching students from:', studentsUrl);

            fetch(studentsUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeader]: csrfToken
                }
            })
            .then(response => {
                debugLog('Students response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                debugLog('Students data:', data);
                if (data.status === 'success' && data.data.length > 0) {
                    updateMarksTable(data.data);
                    saveMarksBtn.style.display = 'inline-flex';
                    saveMarksBtn.disabled = false;
                    getStudentsBtn.style.display = 'none';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'No Students Found',
                        text: 'No students found for this combination and class.'
                    });
                }
            })
            .catch(error => {
                debugLog('Error fetching students:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error fetching students: ' + error.message
                });
            });

            // Load subjects - but don't create input fields above table
            const subjectsUrl = `<?= base_url('alevel/marks/getSubjects') ?>?combination_id=${combinationId}`;
            debugLog('Fetching subjects from:', subjectsUrl);

            fetch(subjectsUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeader]: csrfToken
                }
            })
            .then(response => {
                debugLog('Subjects response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                debugLog('Subjects data:', data);
                // Do not create input fields here, they will be handled in the table
                marksContainer.style.display = 'none'; // Hide the "Enter Marks" section
            })
            .catch(error => {
                debugLog('Error fetching subjects:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error fetching subjects: ' + error.message
                });
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Missing Selection',
                text: 'Please select session, exam, class, and combination before proceeding.'
            });
        }
    });

    function updateMarksTable(students) {
        studentsMarksTable.innerHTML = '';
        let table = `
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Roll Number</th>
                        <th>Subjects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="marksTableBody">
        `;
        students.forEach(student => {
            const fullName = `${student.firstname} ${student.lastname}`.trim();
            table += `
                <tr>
                    <td>${fullName}</td>
                    <td>${student.roll_no || 'N/A'}</td>
                    <td><div class="subjects-container" id="subjects_${student.id}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.5rem; padding: 0.3rem;"></div></td>
                    <td>
                        <button class="btn btn-success" onclick="saveMarks(${student.id})" style="background-color: var(--primary); color: black; padding: 0.4rem 1.2rem; border-radius: var(--button-radius); font-weight: 600; border: none; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 0 8px rgba(74, 229, 74, 0.3); font-size: 0.85rem;">
                            <i class="fas fa-save" style="font-size: 0.85rem;"></i> Save
                        </button>
                    </td>
                </tr>
            `;
        });
        table += `
                </tbody>
            </table>
        `;
        studentsMarksTable.innerHTML = table;

        // Load subjects for each student
        const combinationId = combinationSelect.value;
        const subjectsUrl = `<?= base_url('alevel/marks/getSubjects') ?>?combination_id=${combinationId}`;
        debugLog('Fetching subjects for table from:', subjectsUrl);

        fetch(subjectsUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfHeader]: csrfToken
            }
        })
        .then(response => {
            debugLog('Subjects for table response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            debugLog('Subjects for table:', data);
            if (data.status === 'success' && data.data.length > 0) {
                students.forEach(student => {
                    const container = document.getElementById(`subjects_${student.id}`);
                    let subjectsHtml = '';
                    data.data.forEach(subject => {
                        subjectsHtml += `
                            <div class="subject-group" style="display: flex; flex-direction: column; background-color: var(--secondary); padding: 0.5rem; border-radius: var(--radius); border: 1px solid var(--border); transition: all 0.3s ease;">
                                <span class="subject-label" style="font-weight: 500; color: var(--text-primary); margin-bottom: 0.2rem; font-size: 0.85rem;">${subject.subject_name}</span>
                                <div style="display: flex; align-items: center; gap: 0.3rem;">
                                    <input type="number" 
                                        class="marks-input" 
                                        data-subject="${subject.id}"
                                        data-student="${student.id}"
                                        min="0" 
                                        placeholder="Marks"
                                        style="width: 100%; padding: 0.4rem 0.6rem; border: 1px solid var(--border); border-radius: var(--radius); font-size: 0.85rem; background-color: var(--card-bg); color: var(--text-primary); transition: all 0.3s ease;">
                                    <span class="marks-max" style="font-size: 0.7rem; color: var(--text-secondary); white-space: nowrap;">/ 100</span>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = subjectsHtml;
                    loadExistingMarks(student.id);
                });
            }
        })
        .catch(error => {
            debugLog('Error fetching subjects for table:', error);
        });
    }

    function loadExistingMarks(studentId) {
        const examId = examSelect.value;
        debugLog('Loading existing marks for student:', studentId, 'exam:', examId);
        const existingMarksUrl = `<?= base_url('alevel/marks/getExistingMarks') ?>/${examId}/${studentId}`;
        debugLog('Fetching existing marks from:', existingMarksUrl);

        fetch(existingMarksUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfHeader]: csrfToken
            }
        })
        .then(response => {
            debugLog('Existing marks response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            debugLog('Existing marks data:', data);
            if (data.status === 'success' && data.data.length > 0) {
                data.data.forEach(mark => {
                    const input = document.querySelector(`input[data-subject="${mark.subject_id}"][data-student="${studentId}"]`);
                    if (input) {
                        input.value = mark.marks_obtained !== null ? mark.marks_obtained : '';
                    }
                });
            }
        })
        .catch(error => {
            debugLog('Error fetching existing marks:', error);
        });
    }

    window.saveMarks = function(studentId) {
        const examId = examSelect.value;
        const classId = classSelect.value;
        const sessionId = sessionSelect.value;
        const combinationId = combinationSelect.value;
        const inputs = document.querySelectorAll(`input[data-student="${studentId}"]`);
        const marks = {};

        // Validate marks
        let isValid = true;
        const maxMarks = 100; // Default max marks
        inputs.forEach(input => {
            const enteredMarks = input.value ? parseInt(input.value) : null;
            if (enteredMarks !== null && (enteredMarks < 0 || enteredMarks > maxMarks)) {
                input.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                input.style.borderColor = 'var(--border)';
                marks[input.dataset.subject] = enteredMarks;
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Marks',
                text: 'Please enter valid marks (between 0 and 100).'
            });
            return;
        }

        debugLog('Saving marks for student:', studentId, 'with data:', { examId, classId, sessionId, combinationId, marks });
        const saveUrl = '<?= base_url('alevel/marks/save') ?>';
        debugLog('Saving marks to:', saveUrl);

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfHeader]: csrfToken
            },
            body: new URLSearchParams({
                exam_id: examId,
                student_id: studentId,
                class_id: classId,
                session_id: sessionId,
                combination_id: combinationId,
                marks: JSON.stringify(marks)
            })
        })
        .then(response => {
            debugLog('Save marks response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            debugLog('Save marks response data:', data);
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2000
                });
                loadExistingMarks(studentId);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message
                });
            }
        })
        .catch(error => {
            debugLog('Error saving marks:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An unexpected error occurred. Please try again.'
            });
        });
    };

    document.getElementById('marksForm').addEventListener('submit', function(e) {
        e.preventDefault();
    });
});
</script>