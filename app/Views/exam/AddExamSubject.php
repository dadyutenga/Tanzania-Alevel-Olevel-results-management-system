<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Add Exam Subjects</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container {
            background: var(--card-bg);
            padding: 1.5rem;
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

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
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
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .subject-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 80px;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: end;
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
            background-color: var(--primary);
            color: black;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.4);
        }

        .btn-sm {
            padding: 0.5rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            font-size: 0.875rem;
            justify-content: center;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .header-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .header-action h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            min-width: 800px;
        }

        .table thead th {
            background-color: var(--primary);
            color: #000000;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table tbody tr {
            background-color: var(--card-bg);
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: rgba(74, 229, 74, 0.05);
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .text-center {
            text-align: center;
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

            .subject-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .header-action {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .sidebar-toggle {
                display: block;
            }

            .table {
                display: block;
                overflow-x: auto;
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
            <?= $this->include('shared/sidebar_menu') ?>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>Exam Subjects Management</h1>
                    <p>Add, edit, or delete subjects for selected exams</p>
                </div>

                <div class="form-container">
                    <div class="form-group">
                        <label for="examSelect">Select Exam <span class="text-danger">*</span></label>
                        <select class="form-control" id="examSelect" onchange="loadExamSubjects(this.value)" required aria-required="true">
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

                <div id="examContentArea">
                    <?php if (isset($exam) && $exam): ?>
                        <div class="form-container">
                            <div class="header-action">
                                <h3>Add New Subjects</h3>
                                <button type="button" class="btn" onclick="addSubjectRow()" aria-label="Add More Subjects">
                                    <i class="fas fa-plus"></i> Add More Subjects
                                </button>
                            </div>

                            <form id="multiSubjectForm">
                                <input type="hidden" name="exam_id" value="<?= $exam['id'] ?? '' ?>">
                                <div id="subjectsContainer">
                                    <!-- Subject rows will be added here -->
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn" aria-label="Save All Subjects">
                                        <i class="fas fa-save"></i> Save All Subjects
                                    </button>
                                </div>
                            </form>
                        </div>

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
                                                    <button class="btn btn-sm" onclick="editSubject(<?= htmlspecialchars(json_encode($subject), ENT_QUOTES, 'UTF-8') ?>)" aria-label="Edit Subject">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteSubject(<?= $subject['id'] ?? '' ?>)" aria-label="Delete Subject">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let subjectRowCount = 0;

        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                });
            }

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

                    link.addEventListener('click', function (e) {
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

            document.addEventListener('click', function (e) {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            });

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
                        placeholder="Enter subject name" required maxlength="100" aria-required="true">
                </div>
                <div class="form-group">
                    <label>Maximum Marks</label>
                    <input type="number" class="form-control" name="subjects[${subjectRowCount}][max_marks]" 
                        placeholder="Enter max marks" required min="1" aria-required="true">
                </div>
                <div class="form-group">
                    <label>Passing Marks</label>
                    <input type="number" class="form-control" name="subjects[${subjectRowCount}][passing_marks]" 
                        placeholder="Enter passing marks" required min="1" aria-required="true">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeSubjectRow(this)" aria-label="Remove Subject">
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

        document.getElementById('multiSubjectForm').addEventListener('submit', async function (e) {
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

                formData.append('subjects', JSON.stringify(subjects));

                const response = await fetch('<?= base_url('exam/subjects/store-batch') ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

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
                console.error('Error:', error);
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
                    confirmButtonColor: '#ef4444',
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