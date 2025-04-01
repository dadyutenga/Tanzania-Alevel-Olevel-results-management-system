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
                <h2>Exam Results Management</h2>
            </div>
            <?= $this->include('shared/sidebar_menu') ?>
        </div>

        <!-- Main Content -->
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
                                <option value="<?= $examItem['id'] ?>" <?= (isset($exam['id']) && $exam['id'] == $examItem['id']) ? 'selected' : '' ?>>
                                    <?= esc($examItem['exam_name']) ?> (<?= esc($examItem['exam_date']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        
            <!-- Dynamic Content Area -->
            <div id="examContentArea">
                <?php if (isset($exam) && $exam): ?>
                    <!-- Add New Subjects Form -->
                    <div class="form-container">
                        <div class="header-action">
                            <h3>Add New Subjects</h3>
                            <button type="button" class="btn btn-primary" onclick="addSubjectRow()">
                                <i class="fas fa-plus"></i> Add More Subjects
                            </button>
                        </div>
                        
                        <form id="multiSubjectForm" class="mt-3">
                            <input type="hidden" name="exam_id" value="<?= $exam['id'] ?? '' ?>">
                            <div id="subjectsContainer">
                                <!-- Subject rows will be added here -->
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save All Subjects
                                </button>
                            </div>
                        </form>
                    </div>
                
                    <!-- Existing Subjects Table -->
                    <div class="form-container">
                        <h3>Existing Subjects</h3>
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
                                            <td><?= esc($subject['subject_name'] ?? '') ?></td>
                                            <td><?= esc($subject['max_marks'] ?? '') ?></td>
                                            <td><?= esc($subject['passing_marks'] ?? '') ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" onclick="editSubject(<?= htmlspecialchars(json_encode($subject), ENT_QUOTES, 'UTF-8') ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteSubject(<?= $subject['id'] ?? '' ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No subjects found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</div>

<!-- Add SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Main Script -->
<script>
let subjectRowCount = 0;

// Initialize form when document loads
document.addEventListener('DOMContentLoaded', function() {
    const examId = '<?= isset($exam['id']) ? $exam['id'] : '' ?>';
    if (examId) {
        addSubjectRow();
    }
});

function loadExamSubjects(examId) {
    if (!examId) return;
    window.location.href = '<?= base_url('exam/subjects') ?>/' + examId;
}

function addSubjectRow() {
    const container = document.getElementById('subjectsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'subject-row';
    newRow.innerHTML = `
        <div class="form-group">
            <label>Subject Name</label>
            <input type="text" class="form-control" name="subjects[${subjectRowCount}][subject_name]" 
                placeholder="Enter subject name" required maxlength="100">
        </div>
        <div class="form-group">
            <label>Maximum Marks</label>
            <input type="number" class="form-control" name="subjects[${subjectRowCount}][max_marks]" 
                placeholder="Enter max marks" required min="1">
        </div>
        <div class="form-group">
            <label>Passing Marks</label>
            <input type="number" class="form-control" name="subjects[${subjectRowCount}][passing_marks]" 
                placeholder="Enter passing marks" required min="1">
        </div>
        <div class="form-group" style="display: flex; align-items: flex-end;">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeSubjectRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
    subjectRowCount++;
}

function removeSubjectRow(button) {
    button.closest('.subject-row').remove();
}

// Form submission handler
document.getElementById('multiSubjectForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        const formData = new FormData();
        const examId = this.querySelector('[name="exam_id"]').value;
        formData.append('exam_id', examId);
        
        const subjectRows = document.querySelectorAll('.subject-row');
        const subjects = [];
        
        for (let row of subjectRows) {
            const subjectName = row.querySelector('[name$="[subject_name]"]').value.trim();
            const maxMarks = parseInt(row.querySelector('[name$="[max_marks]"]').value);
            const passingMarks = parseInt(row.querySelector('[name$="[passing_marks]"]').value);
            
            if (!subjectName || isNaN(maxMarks) || isNaN(passingMarks)) {
                throw new Error('Please fill all fields for each subject');
            }
            
            if (passingMarks > maxMarks) {
                throw new Error('Passing marks cannot be greater than maximum marks');
            }
            
            subjects.push({
                subject_name: subjectName,
                max_marks: maxMarks,
                passing_marks: passingMarks
            });
        }
        
        // Convert subjects array to JSON string
        formData.append('subjects', JSON.stringify(subjects));

        const response = await fetch('<?= base_url('exam/subjects/store-batch') ?>', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        console.log('Server response:', result); // Debug log

        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Subjects added successfully',
                timer: 1500
            });
            
            this.reset();
            document.getElementById('subjectsContainer').innerHTML = '';
            addSubjectRow();
            window.location.reload();
        } else {
            throw new Error(result.message || 'Failed to add subjects');
        }
    } catch (error) {
        console.error('Error:', error); // Debug log
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to add subjects'
        });
    }
});

async function deleteSubject(subjectId) {
    try {
        const result = await Swal.fire({
            title: 'Delete Subject',
            text: 'Are you sure you want to delete this subject?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            const response = await fetch(`<?= base_url('exam/subjects/delete') ?>/${subjectId}`, {
                method: 'POST'
            });

            const data = await response.json();

            if (data.status === 'success') {
                Swal.fire('Deleted!', 'Subject has been deleted.', 'success');
                document.getElementById(`subject-row-${subjectId}`).remove();
            } else {
                throw new Error(data.message || 'Failed to delete subject');
            }
        }
    } catch (error) {
        Swal.fire('Error!', error.message || 'Failed to delete subject', 'error');
    }
}

function editSubject(subject) {
    Swal.fire({
        title: 'Edit Subject',
        html: `
            <input type="text" id="edit_subject_name" class="swal2-input" placeholder="Subject Name" 
                value="${subject.subject_name}" required>
            <input type="number" id="edit_max_marks" class="swal2-input" placeholder="Maximum Marks" 
                value="${subject.max_marks}" required min="1">
            <input type="number" id="edit_passing_marks" class="swal2-input" placeholder="Passing Marks" 
                value="${subject.passing_marks}" required min="1">
        `,
        showCancelButton: true,
        confirmButtonText: 'Update',
        showLoaderOnConfirm: true,
        preConfirm: async () => {
            try {
                const formData = new FormData();
                formData.append('subject_name', document.getElementById('edit_subject_name').value);
                formData.append('max_marks', document.getElementById('edit_max_marks').value);
                formData.append('passing_marks', document.getElementById('edit_passing_marks').value);

                const response = await fetch(`<?= base_url('exam/subjects/update') ?>/${subject.id}`, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.status !== 'success') {
                    throw new Error(result.message || 'Failed to update subject');
                }
                
                return result;
            } catch (error) {
                Swal.showValidationMessage(error.message);
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Success!', 'Subject has been updated.', 'success');
            window.location.reload();
        }
    });
}
</script>
</body>
</html>

<script>
    // Add sidebar menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        const expandableMenus = document.querySelectorAll('.expandable');
        expandableMenus.forEach(menu => {
            menu.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                const toggleIcon = this.querySelector('.toggle-icon');
                
                // Toggle submenu visibility
                if (submenu.style.display === 'none' || submenu.style.display === '') {
                    submenu.style.display = 'block';
                    toggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    submenu.style.display = 'none';
                    toggleIcon.style.transform = 'rotate(0deg)';
                }
            });
        });
    });
</script>
