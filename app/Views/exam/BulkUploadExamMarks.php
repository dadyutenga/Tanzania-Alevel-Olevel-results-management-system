<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Upload Exam Marks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

        /* Sidebar styles (consistent with AddExamSubject.php) */
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
            padding: 1.5rem 1rem;
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
            font-weight: 600;
            opacity: 0.9;
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 2rem;
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
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Form Container */
        .form-container {
            background: var(--primary);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        /* Form Elements */
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

        /* Buttons */
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

        .btn-success {
            background-color: var(--success);
            color: white;
            border: none;
        }

        .btn-success:hover {
            background-color: #2daa7d;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--radius);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert i {
            font-size: 1.25rem;
            margin-top: 0.125rem;
        }

        .alert-info {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.5);
            color: var(--text-primary);
        }

        .alert-danger {
            background-color: rgba(229, 62, 62, 0.1);
            border: 1px solid rgba(229, 62, 62, 0.5);
            color: var(--text-primary);
            display: none;
        }

        .alert-success {
            background-color: rgba(49, 196, 141, 0.1);
            border: 1px solid rgba(49, 196, 141, 0.5);
            color: var(--text-primary);
            display: none;
        }

        hr {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.5rem 0;
        }

        /* Responsive */
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
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar (consistent with AddExamSubject.php) -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Results Management</h2>
            </div>
            <?= $this->include('shared/sidebar_menu') ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Bulk Upload Exam Marks</h1>
            </div>

            <div class="form-container">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Follow these steps:</strong>
                        <ol style="margin-top: 0.5rem; padding-left: 1.25rem;">
                            <li>Select the exam details below</li>
                            <li>Download the CSV template</li>
                            <li>Fill in the marks in the downloaded template</li>
                            <li>Upload the completed CSV file</li>
                        </ol>
                    </div>
                </div>

                <div class="alert alert-danger" id="errorAlert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div id="errorMessage"></div>
                </div>
                <div class="alert alert-success" id="successAlert">
                    <i class="fas fa-check-circle"></i>
                    <div id="successMessage"></div>
                </div>

                <form id="examSelectionForm">
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

                    <div class="form-group">
                        <label for="exam">Select Exam</label>
                        <select id="exam" class="form-control" required>
                            <option value="">Select Exam</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="class">Select Class</label>
                        <select id="class" class="form-control" required>
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="downloadTemplate()">
                        <i class="fas fa-download"></i> Download Template
                    </button>
                </form>

                <hr>

                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="csv_file">Upload Completed CSV</label>
                        <input type="file" id="csv_file" name="csv_file" class="form-control" accept=".csv" required>
                    </div>

                    <button type="button" class="btn btn-success" onclick="uploadMarks()">
                        <i class="fas fa-upload"></i> Upload Marks
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript remains unchanged (only CSS/structure updated)
        document.getElementById('session').addEventListener('change', function() {
            fetchExams(this.value);
        });

        document.getElementById('exam').addEventListener('change', function() {
            if (document.getElementById('session').value) {
                fetchClasses(document.getElementById('session').value);
            }
        });

        function fetchExams(sessionId) {
            fetch(`/exam/marks/bulk/getExams/${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateSelect('exam', data.data);
                    }
                })
                .catch(error => showError('Failed to fetch exams'));
        }

        function fetchClasses(sessionId) {
            fetch(`/exam/marks/bulk/getClasses/${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateSelect('class', data.data);
                    }
                })
                .catch(error => showError('Failed to fetch classes'));
        }

        function updateSelect(elementId, data) {
            const select = document.getElementById(elementId);
            select.innerHTML = `<option value="">Select ${elementId.charAt(0).toUpperCase() + elementId.slice(1)}</option>`;
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.exam_name || item.class;
                select.appendChild(option);
            });
        }

        function downloadTemplate() {
            const sessionId = document.getElementById('session').value;
            const examId = document.getElementById('exam').value;
            const classId = document.getElementById('class').value;

            if (!sessionId || !examId || !classId) {
                showError('Please select all required fields');
                return;
            }

            window.location.href = `/exam/marks/bulk/downloadTemplate?session_id=${sessionId}&exam_id=${examId}&class_id=${classId}`;
        }

        function uploadMarks() {
            const sessionId = document.getElementById('session').value;
            const examId = document.getElementById('exam').value;
            const classId = document.getElementById('class').value;
            const fileInput = document.getElementById('csv_file');

            if (!sessionId || !examId || !classId || !fileInput.files[0]) {
                showError('Please select all required fields and a CSV file');
                return;
            }

            const formData = new FormData();
            formData.append('csv_file', fileInput.files[0]);
            formData.append('session_id', sessionId);
            formData.append('exam_id', examId);
            formData.append('class_id', classId);

            fetch('/exam/marks/bulk/uploadMarks', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showSuccess(data.message);
                    if (data.errors && data.errors.length > 0) {
                        showError('Some entries had errors:\n' + data.errors.join('\n'));
                    }
                } else {
                    showError(data.message);
                }
            })
            .catch(error => showError('Failed to upload marks'));
        }

        function showError(message) {
            const alert = document.getElementById('errorAlert');
            const messageDiv = document.getElementById('errorMessage');
            messageDiv.textContent = message;
            alert.style.display = 'flex';
            setTimeout(() => alert.style.display = 'none', 5000);
        }

        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            const messageDiv = document.getElementById('successMessage');
            messageDiv.textContent = message;
            alert.style.display = 'flex';
            setTimeout(() => alert.style.display = 'none', 5000);
        }
    </script>
</body>
</html>