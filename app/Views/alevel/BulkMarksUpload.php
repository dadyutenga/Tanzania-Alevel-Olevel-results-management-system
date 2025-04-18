<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Bulk Marks Upload</title>
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

        /* File Input Styling */
        .file-input-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .file-input-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .file-input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px dashed var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            background-color: var(--secondary);
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input:hover {
            border-color: var(--primary);
            background-color: rgba(74, 229, 74, 0.05);
        }

        .file-input::file-selector-button {
            background-color: var(--primary);
            color: black;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--button-radius);
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .file-input::file-selector-button:hover {
            background-color: var(--primary-dark);
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
                    <h1>Bulk Marks Upload</h1>
                    <p>Upload marks for A-Level students in bulk using Excel files</p>
                </div>
                
                <div class="form-container">
                    <h2 class="form-title"><i class="fas fa-upload"></i> Bulk Marks Upload</h2>
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
                    <form id="bulkUploadForm" action="<?= base_url('alevel/marks/bulk/upload') ?>" method="post" enctype="multipart/form-data">
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
                        <div class="row">
                            <div class="form-group">
                                <label class="file-input-label" for="marks_file">Upload Marks File (XLSX) <span class="required">*</span></label>
                                <input type="file" id="marks_file" name="marks_file" class="file-input" accept=".xlsx" required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-primary" id="downloadTemplateBtn">
                                <i class="fas fa-download"></i> Download Template
                            </button>
                            <button type="submit" class="btn btn-primary" id="uploadMarksBtn">
                                <i class="fas fa-upload"></i> Upload Marks
                            </button>
                        </div>
                    </form>
                </div>

                <div class="results-table-container">
                    <h2 class="form-title" style="padding: 1rem; margin-bottom: 0;"><i class="fas fa-info-circle"></i> Instructions</h2>
                    <div style="padding: 2rem;">
                        <ul style="list-style-type: none; padding: 0; color: var(--text-primary);">
                            <li style="margin-bottom: 1rem; display: flex; align-items: start; gap: 0.75rem;">
                                <i class="fas fa-chevron-right" style="color: var(--primary); margin-top: 0.25rem;"></i>
                                <span>Select the session, exam, class, and combination to download the appropriate Excel template.</span>
                            </li>
                            <li style="margin-bottom: 1rem; display: flex; align-items: start; gap: 0.75rem;">
                                <i class="fas fa-chevron-right" style="color: var(--primary); margin-top: 0.25rem;"></i>
                                <span>Fill in the marks for each student in the downloaded template. Ensure marks are between 0 and 100.</span>
                            </li>
                            <li style="margin-bottom: 1rem; display: flex; align-items: start; gap: 0.75rem;">
                                <i class="fas fa-chevron-right" style="color: var(--primary); margin-top: 0.25rem;"></i>
                                <span>Upload the completed Excel file using the form above to save the marks in bulk.</span>
                            </li>
                            <li style="margin-bottom: 1rem; display: flex; align-items: start; gap: 0.75rem;">
                                <i class="fas fa-chevron-right" style="color: var(--primary); margin-top: 0.25rem;"></i>
                                <span>Only XLSX file format is supported for upload.</span>
                            </li>
                        </ul>
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
    const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    const uploadMarksBtn = document.getElementById('uploadMarksBtn');

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
    });

    // Download Template button: Generate and download Excel template
    downloadTemplateBtn.addEventListener('click', function() {
        const combinationId = combinationSelect.value;
        const classId = classSelect.value;
        const sessionId = sessionSelect.value;
        const examId = examSelect.value;
        debugLog('Download Template clicked with:', { combinationId, classId, sessionId, examId });

        if (combinationId && classId && sessionId && examId) {
            const templateUrl = `<?= base_url('alevel/marks/bulk/downloadTemplate') ?>?exam_id=${examId}&class_id=${classId}&session_id=${sessionId}&combination_id=${combinationId}`;
            debugLog('Downloading template from:', templateUrl);

            window.location.href = templateUrl;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Missing Selection',
                text: 'Please select session, exam, class, and combination before downloading the template.'
            });
        }
    });

    // Upload Marks button: Submit the form
    uploadMarksBtn.addEventListener('click', function(e) {
        const form = document.getElementById('bulkUploadForm');
        const fileInput = document.getElementById('marks_file');
        const combinationId = combinationSelect.value;
        const classId = classSelect.value;
        const sessionId = sessionSelect.value;
        const examId = examSelect.value;

        if (!combinationId || !classId || !sessionId || !examId) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Missing Selection',
                text: 'Please select session, exam, class, and combination before uploading.'
            });
            return;
        }

        if (!fileInput.files.length) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'No File Selected',
                text: 'Please select an Excel file to upload.'
            });
            return;
        }

        if (fileInput.files[0].type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload an XLSX file.'
            });
            return;
        }

        // Show loading spinner
        Swal.fire({
            title: 'Uploading...',
            text: 'Please wait while the marks are being uploaded.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Allow form submission to the action URL
        form.submit();
    });

    // Handle form submission response (if redirected with message)
    <?php if (session()->has('message')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= session('message') ?>',
            showConfirmButton: false,
            timer: 3000
        });
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?= session('error') ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
});
</script>
</body>
</html>
