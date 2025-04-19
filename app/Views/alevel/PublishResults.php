<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Publish A-Level Results</title>
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

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
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

        .text-danger {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
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
                    <h1>Publish A-Level Results</h1>
                    <p>Select criteria to calculate and publish A-Level exam results</p>
                </div>

                <div class="form-container">
                    <div class="row">
                        <div class="form-group">
                            <label for="session">Academic Session <span class="text-danger">*</span></label>
                            <select id="session" class="form-control" required aria-required="true">
                                <option value="">Select Session</option>
                                <?php foreach ($sessions as $session): ?>
                                    <option value="<?= $session['id'] ?>"><?= $session['session'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class">Class <span class="text-danger">*</span></label>
                            <select id="class" class="form-control" required aria-required="true">
                                <option value="">Select Class</option>
                                <?php if (isset($classes)): ?>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>"><?= $class['class'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="exam">Exam <span class="text-danger">*</span></label>
                            <select id="exam" class="form-control" required aria-required="true">
                                <option value="">Select Exam</option>
                                <?php if (isset($exams)): ?>
                                    <?php foreach ($exams as $exam): ?>
                                        <option value="<?= $exam['id'] ?>"><?= $exam['exam_name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="combination">Combination <span class="text-danger">*</span></label>
                            <select id="combination" class="form-control" required aria-required="true">
                                <option value="">Select Combination</option>
                                <?php if (isset($combinations)): ?>
                                    <?php foreach ($combinations as $combination): ?>
                                        <option value="<?= $combination['id'] ?>"><?= $combination['combination_code'] . ' - ' . $combination['combination_name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn" onclick="calculateResults()" aria-label="Calculate and Publish Results">
                            <i class="fas fa-calculator"></i> Calculate and Publish Results
                        </button>
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
        });

        document.getElementById('session').addEventListener('change', async function() {
            const sessionId = this.value;
            await updateExams(sessionId);
            await updateClasses(sessionId);
        });

        document.getElementById('class').addEventListener('change', async function() {
            const sessionId = document.getElementById('session').value;
            const classId = this.value;
            await updateCombinations(sessionId, classId);
        });

        async function updateExams(sessionId) {
            if (sessionId) {
                try {
                    const response = await fetch(`<?= base_url('alevel/results/getExams') ?>/${sessionId}`);
                    const data = await response.json();

                    const examSelect = document.getElementById('exam');
                    examSelect.innerHTML = '<option value="">Select Exam</option>';

                    if (data.status === 'success') {
                        data.data.forEach(exam => {
                            const option = document.createElement('option');
                            option.value = exam.id;
                            option.textContent = exam.exam_name;
                            examSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error fetching exams:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load exams'
                    });
                }
            }
        }

        async function updateClasses(sessionId) {
            if (sessionId) {
                try {
                    const response = await fetch(`<?= base_url('alevel/results/getClasses') ?>/${sessionId}`);
                    const data = await response.json();

                    const classSelect = document.getElementById('class');
                    classSelect.innerHTML = '<option value="">Select Class</option>';

                    if (data.status === 'success') {
                        data.data.forEach(cls => {
                            const option = document.createElement('option');
                            option.value = cls.id;
                            option.textContent = cls.class;
                            classSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error fetching classes:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load classes'
                    });
                }
            }
        }

        async function updateCombinations(sessionId, classId) {
            if (sessionId && classId) {
                try {
                    const response = await fetch(`<?= base_url('alevel/results/getCombinations') ?>/${sessionId}/${classId}`);
                    const data = await response.json();

                    const combinationSelect = document.getElementById('combination');
                    combinationSelect.innerHTML = '<option value="">Select Combination</option>';

                    if (data.status === 'success') {
                        data.data.forEach(combination => {
                            const option = document.createElement('option');
                            option.value = combination.id;
                            option.textContent = combination.combination_code + ' - ' + combination.combination_name;
                            combinationSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error fetching combinations:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load combinations'
                    });
                }
            }
        }

        async function calculateResults() {
            const examId = document.getElementById('exam').value;
            const classId = document.getElementById('class').value;
            const sessionId = document.getElementById('session').value;
            const combinationId = document.getElementById('combination').value;

            // Add this debugging section
            console.log({
                examId,
                classId,
                sessionId,
                combinationId
            });

            if (!examId || !classId || !sessionId || !combinationId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select all required fields'
                });
                return;
            }

            try {
                const url = `<?= base_url('alevel/results/calculate') ?>?exam_id=${examId}&class_id=${classId}&session_id=${sessionId}&combination_id=${combinationId}`;
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Results have been calculated and published successfully',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('exam').value = '';
                            document.getElementById('class').value = '';
                            document.getElementById('combination').value = '';
                            document.getElementById('session').value = '';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to calculate results'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while calculating results'
                });
            }
        }
    </script>
</body>
</html>
