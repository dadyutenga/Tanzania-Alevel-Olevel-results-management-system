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
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Results Management</h2>
            </div>
            <?= view('shared/sidebar_menu') ?>
        </div>


        <!-- Main Content -->
        <!-- After the header section -->
        <div class="main-content">
            <div class="header">
                <h1>Exam Subjects Management</h1>
            </div>
        
            <!-- Exam Selection Dropdown -->
            <div class="form-container">
                <div class="form-group">
                    <label for="examSelect">Select Exam</label>
                    <select class="form-control" id="examSelect" onchange="loadExamSubjects(this.value)">
                        <option value="">Choose an exam...</option>
                        <?php if (!empty($exams)): ?>
                            <?php foreach ($exams as $examItem): ?>
                                <option value="<?= $examItem['id'] ?>" <?= ($exam && $exam['id'] == $examItem['id']) ? 'selected' : '' ?>>
                                    <?= esc($examItem['exam_name']) ?> (<?= esc($examItem['exam_date']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        
            <!-- Dynamic Content Area -->
            <div id="examContentArea">
                <?php if ($exam): ?>
                    <!-- Existing exam info and add subject form remains the same -->
                    
                    <!-- Modified Subjects Table with Edit Feature -->
                    <div class="form-container">
                        <h3>Exam Subjects</h3>
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
                                        <tr id="subject-row-<?= $subject['id'] ?>">
                                            <td><?= esc($subject['subject_name']) ?></td>
                                            <td><?= esc($subject['max_marks']) ?></td>
                                            <td><?= esc($subject['passing_marks']) ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" onclick="editSubject(<?= json_encode($subject) ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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
                <?php endif; ?>
            </div>
        </div>
</div>

<!-- Add Edit Subject Modal -->
<div class="modal" id="editSubjectModal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Subject</h5>
                <button type="button" class="close" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editSubjectForm">
                    <input type="hidden" id="edit_subject_id" name="subject_id">
                    <div class="form-group">
                        <label for="edit_subject_name">Subject Name</label>
                        <input type="text" class="form-control" id="edit_subject_name" name="subject_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_max_marks">Maximum Marks</label>
                        <input type="number" class="form-control" id="edit_max_marks" name="max_marks" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="edit_passing_marks">Passing Marks</label>
                        <input type="number" class="form-control" id="edit_passing_marks" name="passing_marks" required min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateSubject()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Add these styles to your existing CSS -->
<style>
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
    }
    .modal-dialog {
        background: white;
        border-radius: var(--radius);
        width: 500px;
        max-width: 90%;
    }
    .modal-content {
        padding: 1rem;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .modal-footer {
        margin-top: 1rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    .close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }
</style>

<!-- Add these scripts to your existing JavaScript -->
<script>
function loadExamSubjects(examId) {
    if (!examId) return;
    window.location.href = `<?= base_url('exam/subjects/add/') ?>/${examId}`;
}

function editSubject(subject) {
    document.getElementById('edit_subject_id').value = subject.id;
    document.getElementById('edit_subject_name').value = subject.subject_name;
    document.getElementById('edit_max_marks').value = subject.max_marks;
    document.getElementById('edit_passing_marks').value = subject.passing_marks;
    
    document.getElementById('editSubjectModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editSubjectModal').style.display = 'none';
}

async function updateSubject() {
    const form = document.getElementById('editSubjectForm');
    const subjectId = document.getElementById('edit_subject_id').value;
    
    try {
        const formData = new FormData(form);
        const response = await fetch(`<?= base_url('exam/subjects/update/') ?>/${subjectId}`, {
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
            closeEditModal();
            loadSubjects(); // Refresh the subjects list
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
            text: 'Failed to update subject'
        });
    }
}

// Update the existing loadSubjects function to handle the edit button
async function loadSubjects() {
    try {
        const response = await fetch('<?= base_url('exam/subjects/list/') ?>/<?= $exam['id'] ?>');
        const result = await response.json();

        if (result.status === 'success') {
            const tbody = document.getElementById('subjectsTableBody');
            tbody.innerHTML = result.data.map(subject => `
                <tr id="subject-row-${subject.id}">
                    <td>${subject.subject_name}</td>
                    <td>${subject.max_marks}</td>
                    <td>${subject.passing_marks}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick='editSubject(${JSON.stringify(subject)})'>
                            <i class="fas fa-edit"></i>
                        </button>
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
</script>
</body>
</html>
