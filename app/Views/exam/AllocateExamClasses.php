<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Class Allocation</title>
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
            margin-bottom: 2rem;
            position: relative;
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
            margin: 0;
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

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            min-width: 600px;
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
                    <h1>Exam Class Allocation</h1>
                    <p>Allocate classes to exams for the selected academic session</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Allocate Classes to Exams</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="session">Academic Session <span class="text-danger">*</span></label>
                            <select id="session" class="form-control" onchange="loadExamsAndClasses()" required aria-required="true">
                                <option value="">Select Session</option>
                                <?php foreach ($sessions as $session): ?>
                                    <option value="<?= $session['id'] ?>" <?= ($session['id'] == ($current_session['id'] ?? '')) ? 'selected' : '' ?>>
                                        <?= esc($session['session']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <form id="allocationForm">
                            <div class="row">
                                <div class="form-group">
                                    <label for="exam">Select Exam <span class="text-danger">*</span></label>
                                    <select id="exam" name="exam_id" class="form-control" required aria-required="true">
                                        <option value="">Select Exam</option>
                                        <?php foreach ($exams as $exam): ?>
                                            <option value="<?= $exam['id'] ?>">
                                                <?= esc($exam['exam_name']) ?> (<?= date('d-m-Y', strtotime($exam['exam_date'])) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="class_id">Select Class <span class="text-danger">*</span></label>
                                    <select id="class_id" name="class_id" class="form-control" required aria-required="true">
                                        <option value="">Select Class</option>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn" aria-label="Save Allocation">
                                    <i class="fas fa-save"></i> Save Allocation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>View Allocations</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="filterSession">Filter by Academic Session <span class="text-danger">*</span></label>
                            <select id="filterSession" class="form-control" onchange="loadAllocations(this.value)" required aria-required="true">
                                <option value="">Select Session</option>
                                <?php foreach ($sessions as $session): ?>
                                    <option value="<?= $session['id'] ?>">
                                        <?= esc($session['session']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Exam Name</th>
                                    <th>Date</th>
                                    <th>Class</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="allocationsTableBody">
                                <tr>
                                    <td colspan="4" class="text-center">Please select a session to view allocations</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
            });

            const sessionId = document.getElementById('session').value;
            if (sessionId) {
                loadExamsAndClasses();
            }
        });

        async function loadExamsAndClasses() {
            const sessionId = document.getElementById('session').value;
            if (!sessionId) return;

            try {
                const examResponse = await fetch(`<?= base_url('exam/allocation/exams/') ?>/${sessionId}`);
                const examData = await examResponse.json();

                const examSelect = document.getElementById('exam');
                examSelect.innerHTML = '<option value="">Select Exam</option>' + 
                    examData.data.map(exam => `
                        <option value="${exam.id}">
                            ${exam.exam_name} (${new Date(exam.exam_date).toLocaleDateString('en-GB')})
                        </option>
                    `).join('');
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load exam data'
                });
            }
        }

        async function loadAllocations(sessionId) {
            if (!sessionId) {
                document.getElementById('allocationsTableBody').innerHTML = 
                    '<tr><td colspan="4" class="text-center">Please select a session to view allocations</td></tr>';
                return;
            }

            try {
                const response = await fetch(`<?= base_url('exam/allocation/list/') ?>/${sessionId}`);
                const result = await response.json();

                const tbody = document.getElementById('allocationsTableBody');
                if (result.status === 'success' && result.data.length > 0) {
                    tbody.innerHTML = result.data.map(allocation => {
                        const examDate = allocation.exam_date ? 
                            new Date(allocation.exam_date).toLocaleDateString('en-GB') : 'N/A';

                        return `
                            <tr>
                                <td>${allocation.exam_name || 'N/A'}</td>
                                <td>${examDate}</td>
                                <td>${allocation.class || 'N/A'}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" 
                                        onclick="deallocate(${allocation.exam_id}, ${allocation.class_id})"
                                        aria-label="Remove Allocation">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">No allocations found for this session</td></tr>';
                }
            } catch (error) {
                console.error('Error loading allocations:', error);
                document.getElementById('allocationsTableBody').innerHTML = 
                    '<tr><td colspan="4" class="text-center">Error loading allocations</td></tr>';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load allocations'
                });
            }
        }

        document.getElementById('allocationForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const examId = document.getElementById('exam').value;
            const sessionId = document.getElementById('session').value;
            const classId = document.getElementById('class_id').value;

            if (!examId || !classId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select both an exam and a class'
                });
                return;
            }

            try {
                const formData = new FormData();
                formData.append('exam_id', examId);
                formData.append('session_id', sessionId);
                formData.append('class_id', classId);

                const response = await fetch('<?= base_url('exam/allocation/store') ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Allocation saved successfully'
                    });
                    loadAllocations(sessionId);
                    this.reset();
                } else {
                    throw new Error(result.message || 'Failed to save allocation');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to save allocation'
                });
            }
        });

        async function deallocate(examId, classId) {
            try {
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove the class allocation",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                });

                if (result.isConfirmed) {
                    const response = await fetch(
                        `<?= base_url('exam/allocation/delete') ?>/${examId}/${classId}`,
                        { method: 'POST' }
                    );
                    const data = await response.json();

                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Allocation has been removed.'
                        });
                        loadAllocations(document.getElementById('session').value);
                    } else {
                        throw new Error(data.message || 'Failed to remove allocation');
                    }
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to remove allocation'
                });
            }
        }
    </script>
</body>
</html>