<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Upload Exam Marks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
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
            margin-top: 0.5rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            margin-right: 0.5rem;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-info {
            background: #e3f2fd;
            border: 1px solid #90caf9;
        }
        .alert-danger {
            background: #ffebee;
            border: 1px solid #ffcdd2;
            display: none;
        }
        .alert-success {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
            display: none;
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
            <?= $this->include('shared/sidebar_menu') ?>
        </div>

        <div class="main-content">
            <h1>Bulk Upload Exam Marks</h1>

            <div class="card">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Follow these steps:
                    <ol>
                        <li>Select the exam details below</li>
                        <li>Download the CSV template</li>
                        <li>Fill in the marks in the downloaded template</li>
                        <li>Upload the completed CSV file</li>
                    </ol>
                </div>

                <div class="alert alert-danger" id="errorAlert"></div>
                <div class="alert alert-success" id="successAlert"></div>

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

                <hr style="margin: 2rem 0;">

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
            alert.textContent = message;
            alert.style.display = 'block';
            setTimeout(() => alert.style.display = 'none', 5000);
        }

        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            alert.textContent = message;
            alert.style.display = 'block';
            setTimeout(() => alert.style.display = 'none', 5000);
        }
    </script>
</body>
</html>