<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Add Exam Subjects</title>
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

        /* Sidebar styles */
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

        .exam-info {
            margin-bottom: 2rem;
            padding: 1rem;
            background: var(--primary-dark);
            border-radius: var(--radius);
        }

        .exam-info h3 {
            margin-bottom: 0.5rem;
            color: var(--accent);
        }

        .subject-list {
            margin-top: 2rem;
        }

        .subject-item {
            display: grid;
            grid-template-columns: 1fr auto auto auto auto;
            gap: 1rem;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.625rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.875rem;
        }

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
        }

        .btn-primary {
            background-color: var(--accent);
            color: var(--primary);
        }

        .btn-danger {
            background-color: var(--danger);
            color: var(--primary);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table th, .table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .table th {
            background-color: var(--primary-dark);
            font-weight: 600;
        }

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
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Result Management</h2>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="<?= base_url('student') ?>"><i class="fas fa-users"></i> Students</a></li>
                <li><a href="<?= base_url('exam') ?>" class="active"><i class="fas fa-file-alt"></i> Exams</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Results</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Add Exam Subjects</h1>
            </div>

            <!-- Exam Information -->
            <div class="form-container exam-info">
                <h3>Exam Details</h3>
                <div class="row">
                    <p><strong>Exam Name:</strong> <?= esc($exam['exam_name']) ?></p>
                    <p><strong>Exam Date:</strong> <?= esc($exam['exam_date']) ?></p>
                </div>
            </div>

            <!-- Add Subject Form -->
            <div class="form-container">
                <form id="addSubjectForm">
                    <input type="hidden" name="exam_id" value="<?= esc($exam['id']) ?>">
                    <div class="row">
                        <div class="form-group">
                            <label for="subject_name">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                        </div>
                        <div class="form-group">
                            <label for="max_marks">Maximum Marks <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="max_marks" name="max_marks" required min="0">
                        </div>
                        <div class="form-group">
                            <label for="passing_marks">Passing Marks <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="passing_marks" name="passing_marks" required min="0">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Subject
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Subjects Table -->
            <div class="form-container">
                <h3>Added Subjects</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Subject Name</th>
                            <th>Maximum Marks</th>
                            <th>Passing Marks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="subjectsTableBody">
                        <?php if (!empty($existingSubjects)): ?>
                            <?php foreach ($existingSubjects as $subject): ?>
                                <tr>
                                    <td><?= esc($subject['subject_name']) ?></td>
                                    <td><?= esc($subject['max_marks']) ?></td>
                                    <td><?= esc($subject['passing_marks']) ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSubject(<?= $subject['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addSubjectForm');
        const maxMarksInput = document.getElementById('max_marks');
        const passingMarksInput = document.getElementById('passing_marks');

        // Validate passing marks cannot exceed maximum marks
        passingMarksInput.addEventListener('input', function() {
            const maxMarks = parseInt(maxMarksInput.value) || 0;
            const passingMarks = parseInt(this.value) || 0;
            
            if (passingMarks > maxMarks) {
                this.setCustomValidity('Passing marks cannot exceed maximum marks');
            } else {
                this.setCustomValidity('');
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(form);
                const response = await fetch('<?= base_url('exam/subjects/add') ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Reset form and refresh subjects list
                    form.reset();
                    loadSubjects();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add subject'
                });
            }
        });

        async function loadSubjects() {
            try {
                const response = await fetch('<?= base_url('exam/subjects/list/') ?>/<?= $exam['id'] ?>');
                const result = await response.json();

                if (result.status === 'success') {
                    const tbody = document.getElementById('subjectsTableBody');
                    tbody.innerHTML = result.data.map(subject => `
                        <tr>
                            <td>${subject.subject_name}</td>
                            <td>${subject.max_marks}</td>
                            <td>${subject.passing_marks}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="deleteSubject(${subject.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading subjects:', error);
            }
        }

        // Initial load of subjects
        loadSubjects();
    });

    async function deleteSubject(subjectId) {
        try {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                const response = await fetch('<?= base_url('exam/subjects/') ?>' + subjectId, {
                    method: 'DELETE'
                });

                const data = await response.json();

                if (data.status === 'success') {
                    Swal.fire('Deleted!', data.message, 'success');
                    loadSubjects();
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error!', 'Failed to delete subject', 'error');
        }
    }
    </script>
</body>
</html>
