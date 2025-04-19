<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Exam Marks</title>
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

        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
            margin-bottom: 2rem;
        }

        .card::before {
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

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .card-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            display: block;
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

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
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

        .subject-mark {
            background-color: var(--secondary);
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .subject-name {
            font-weight: 500;
            color: var(--text-primary);
        }

        .mark {
            font-weight: 600;
            color: var(--accent);
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

        .btn-sm {
            padding: 0.5rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            font-size: 0.875rem;
            justify-content: center;
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

        .btn-secondary {
            background-color: #6b7280;
            color: white;
            box-shadow: 0 0 20px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(107, 114, 128, 0.4);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 2rem;
            width: 100%;
            max-width: 600px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
        }

        .modal-content::before {
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

        .modal-header {
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .table-responsive {
            overflow-x: auto;
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

            .row {
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

            .table {
                display: block;
                overflow-x: auto;
            }

            .modal-content {
                width: 90%;
                margin: 1rem;
            }

            .modal-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .sidebar-toggle {
                display: block;
            }

            .subject-mark {
                display: flex;
                flex-wrap: wrap;
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
                    <h1>View Exam Marks</h1>
                    <p>View and manage student marks for selected exams and classes</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Filter Marks</h3>
                    </div>
                    <div class="card-body">
                        <form id="marksFilterForm">
                            <div class="row">
                                <div class="form-group">
                                    <label for="sessionFilter">Session <span class="text-danger">*</span></label>
                                    <select id="sessionFilter" class="form-control" required aria-required="true">
                                        <option value="">Select Session</option>
                                        <?php foreach ($sessions as $session): ?>
                                            <option value="<?= $session['id'] ?>" 
                                                <?= isset($current_session) && $current_session['id'] == $session['id'] ? 'selected' : '' ?>>
                                                <?= esc($session['session']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="examFilter">Exam <span class="text-danger">*</span></label>
                                    <select id="examFilter" class="form-control" required aria-required="true">
                                        <option value="">Select Exam</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="classFilter">Class <span class="text-danger">*</span></label>
                                    <select id="classFilter" class="form-control" required aria-required="true">
                                        <option value="">Select Class</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn" onclick="searchMarks()" aria-label="Search Marks">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table" id="marksTable">
                                <thead>
                                    <tr>
                                        <th>Roll No</th>
                                        <th>Student Name</th>
                                        <th>Subject Marks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editAllModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit All Marks for <span id="editAllStudentName"></span></h3>
            </div>
            <form id="editAllForm">
                <input type="hidden" id="editAllStudentId">
                <input type="hidden" id="editAllExamId">
                <div id="marksContainer"></div>
                <div class="modal-actions">
                    <button type="submit" class="btn" aria-label="Save All Marks">Save All</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editAllModal')" aria-label="Cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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

                if (!e.target.closest('.modal-content') && !e.target.closest('.btn') && 
                    document.getElementById('editAllModal').style.display === 'flex') {
                    closeModal('editAllModal');
                }
            });

            const sessionId = document.getElementById('sessionFilter').value;
            if (sessionId) {
                loadExams();
            }

            document.getElementById('sessionFilter').addEventListener('change', loadExams);
            document.getElementById('examFilter').addEventListener('change', loadClasses);
            document.getElementById('editAllForm').addEventListener('submit', function (e) {
                e.preventDefault();
                updateAllMarks();
            });
        });

        async function loadExams() {
            const sessionId = document.getElementById('sessionFilter').value;
            if (!sessionId) {
                document.getElementById('examFilter').innerHTML = '<option value="">Select Exam</option>';
                document.getElementById('classFilter').innerHTML = '<option value="">Select Class</option>';
                document.querySelector('#marksTable tbody').innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`<?= base_url('exam/marks/view/getExams') ?>?session_id=${sessionId}`);
                const data = await response.json();
                if (data.status === 'success') {
                    const examSelect = document.getElementById('examFilter');
                    examSelect.innerHTML = '<option value="">Select Exam</option>';
                    data.data.forEach(exam => {
                        examSelect.innerHTML += `<option value="${exam.id}">${exam.exam_name} (${new Date(exam.exam_date).toLocaleDateString('en-GB')})</option>`;
                    });
                    document.getElementById('classFilter').innerHTML = '<option value="">Select Class</option>';
                    document.querySelector('#marksTable tbody').innerHTML = '';
                } else {
                    throw new Error(data.message || 'Failed to load exams');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to load exams'
                });
            }
        }

        async function loadClasses() {
            const examId = document.getElementById('examFilter').value;
            const sessionId = document.getElementById('sessionFilter').value;
            if (!examId || !sessionId) {
                document.getElementById('classFilter').innerHTML = '<option value="">Select Class</option>';
                document.querySelector('#marksTable tbody').innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`<?= base_url('exam/marks/view/getExamClasses') ?>?exam_id=${examId}&session_id=${sessionId}`);
                const data = await response.json();
                if (data.status === 'success') {
                    const classSelect = document.getElementById('classFilter');
                    classSelect.innerHTML = '<option value="">Select Class</option>';
                    data.data.forEach(cls => {
                        classSelect.innerHTML += `<option value="${cls.class_id}">${cls.class_name}</option>`;
                    });
                    document.querySelector('#marksTable tbody').innerHTML = '';
                } else {
                    throw new Error(data.message || 'Failed to load classes');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to load classes'
                });
            }
        }

        async function searchMarks() {
            const examId = document.getElementById('examFilter').value;
            const classId = document.getElementById('classFilter').value;
            const sessionId = document.getElementById('sessionFilter').value;

            if (!examId || !classId || !sessionId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select all filters'
                });
                return;
            }

            try {
                const response = await fetch(`<?= base_url('exam/marks/view/getStudentMarks') ?>?exam_id=${examId}&class_id=${classId}&session_id=${sessionId}`);
                const data = await response.json();
                if (data.status === 'success') {
                    const tbody = document.querySelector('#marksTable tbody');
                    tbody.innerHTML = '';

                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center">No marks found</td></tr>';
                        return;
                    }

                    const students = {};
                    data.data.forEach(mark => {
                        if (!students[mark.student_id]) {
                            students[mark.student_id] = {
                                roll_no: mark.roll_no,
                                name: `${mark.firstname} ${mark.lastname}`.trim(),
                                marks: [],
                                markIds: []
                            };
                        }
                        students[mark.student_id].marks.push({
                            subject: mark.subject_name,
                            obtained: mark.marks_obtained,
                            max: mark.max_marks,
                            passing: mark.passing_marks,
                            id: mark.id,
                            subject_id: mark.subject_id
                        });
                        students[mark.student_id].markIds.push(mark.id);
                    });

                    Object.entries(students).forEach(([studentId, student]) => {
                        const row = document.createElement('tr');

                        const rollCell = document.createElement('td');
                        rollCell.textContent = student.roll_no || 'N/A';
                        row.appendChild(rollCell);

                        const nameCell = document.createElement('td');
                        nameCell.textContent = student.name;
                        row.appendChild(nameCell);

                        const marksCell = document.createElement('td');
                        student.marks.forEach(mark => {
                            const markDiv = document.createElement('div');
                            markDiv.className = 'subject-mark';

                            const subjectSpan = document.createElement('span');
                            subjectSpan.className = 'subject-name';
                            subjectSpan.textContent = `${mark.subject}:`;

                            const markSpan = document.createElement('span');
                            markSpan.className = 'mark';
                            markSpan.textContent = `${mark.obtained}/${mark.max}`;

                            markDiv.appendChild(subjectSpan);
                            markDiv.appendChild(markSpan);
                            marksCell.appendChild(markDiv);
                        });
                        row.appendChild(marksCell);

                        const actionsCell = document.createElement('td');
                        actionsCell.style.whiteSpace = 'nowrap';

                        const editBtn = document.createElement('button');
                        editBtn.className = 'btn btn-sm';
                        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                        editBtn.setAttribute('aria-label', 'Edit All Marks');
                        editBtn.onclick = () => editAllMarks(studentId, student.name, student.marks, examId);
                        editBtn.style.marginRight = '5px';

                        const deleteBtn = document.createElement('button');
                        deleteBtn.className = 'btn btn-danger btn-sm';
                        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
                        deleteBtn.setAttribute('aria-label', 'Delete All Marks');
                        deleteBtn.onclick = () => deleteAllMarks(student.markIds, student.name);

                        actionsCell.appendChild(editBtn);
                        actionsCell.appendChild(deleteBtn);

                        row.appendChild(actionsCell);
                        tbody.appendChild(row);
                    });
                } else {
                    throw new Error(data.message || 'Failed to load marks');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to load marks'
                });
            }
        }

        function editAllMarks(studentId, studentName, marks, examId) {
            document.getElementById('editAllStudentName').textContent = studentName;
            document.getElementById('editAllStudentId').value = studentId;
            document.getElementById('editAllExamId').value = examId;

            const container = document.getElementById('marksContainer');
            container.innerHTML = '';

            marks.forEach(mark => {
                const group = document.createElement('div');
                group.className = 'form-group';

                const label = document.createElement('label');
                label.textContent = mark.subject;
                label.setAttribute('for', `mark_${mark.id}`);

                const inputGroup = document.createElement('div');
                inputGroup.style.display = 'flex';
                inputGroup.style.gap = '10px';
                inputGroup.style.alignItems = 'center';

                const input = document.createElement('input');
                input.type = 'number';
                input.className = 'form-control';
                input.id = `mark_${mark.id}`;
                input.value = mark.obtained;
                input.dataset.markId = mark.id;
                input.dataset.subjectId = mark.subject_id;
                input.min = '0';
                input.max = mark.max;
                input.style.flex = '1';
                input.setAttribute('aria-label', `Marks for ${mark.subject}`);

                const maxSpan = document.createElement('span');
                maxSpan.textContent = `/ ${mark.max}`;
                maxSpan.style.minWidth = '50px';

                inputGroup.appendChild(input);
                inputGroup.appendChild(maxSpan);

                group.appendChild(label);
                group.appendChild(inputGroup);
                container.appendChild(group);
            });

            document.getElementById('editAllModal').style.display = 'flex';
        }

        async function updateAllMarks() {
            const studentId = document.getElementById('editAllStudentId').value;
            const examId = document.getElementById('editAllExamId').value;
            const inputs = document.querySelectorAll('#marksContainer input');

            const updates = [];
            let hasError = false;

            inputs.forEach(input => {
                const markId = input.dataset.markId;
                const subjectId = input.dataset.subjectId;
                const marksObtained = input.value;
                const maxMarks = input.nextElementSibling.textContent.split('/')[1].trim();

                if (marksObtained === '' || parseFloat(marksObtained) < 0 || parseFloat(marksObtained) > parseFloat(maxMarks)) {
                    hasError = true;
                    input.style.borderColor = '#ef4444';
                } else {
                    input.style.borderColor = '';
                    updates.push({
                        mark_id: markId,
                        student_id: studentId,
                        exam_id: examId,
                        subject_id: subjectId,
                        marks_obtained: marksObtained
                    });
                }
            });

            if (hasError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter valid marks (between 0 and max marks)'
                });
                return;
            }

            try {
                const response = await fetch(`<?= base_url('exam/marks/view/updateAll') ?>`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ updates })
                });
                const data = await response.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'All marks updated successfully'
                    });
                    closeModal('editAllModal');
                    searchMarks();
                } else {
                    throw new Error(data.message || 'Failed to update marks');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to update marks'
                });
            }
        }

        function deleteAllMarks(markIds, studentName) {
            Swal.fire({
                title: `Delete all marks for ${studentName}?`,
                text: "This will remove all subject marks for this student in this exam!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4AE54A',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete all!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`<?= base_url('exam/marks/view/deleteAll') ?>`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ mark_ids: markIds })
                        });
                        const data = await response.json();
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'All marks have been deleted.'
                            });
                            searchMarks();
                        } else {
                            throw new Error(data.message || 'Failed to delete marks');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to delete marks'
                        });
                    }
                }
            });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>