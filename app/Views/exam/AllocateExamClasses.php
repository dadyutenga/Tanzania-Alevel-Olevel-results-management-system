<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Class Allocation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #ffffff;
            --primary-dark: #f8f9fa;
            --secondary: #e9ecef;
            --accent: #1a1f36;
            --accent-light: #2d3748;
            --text-primary: #1a1f36;
            --text-secondary: #4a5568;
            --border: #e2e8f0;
            --success: #31c48d;
            --warning: #f59e0b;
            --danger: #e53e3e;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --radius: 6px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
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
            color: white;
            padding: 1.5rem 1rem;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0 0.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header i {
            font-size: 1.8rem;
            margin-right: 0.75rem;
            opacity: 0.9;
        }

        .sidebar-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            opacity: 0.9;
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 1rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.2s ease;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-menu i {
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 20px;
            text-align: center;
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
            margin-bottom: 1.5rem;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Card styles */
        .card {
            background: var(--primary);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            background-color: var(--primary);
        }

        .card-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--accent);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.875rem;
            background-color: var(--primary);
            color: var(--text-primary);
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-light);
        }

        /* Button styles */
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
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--accent-light);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            opacity: 0.9;
        }

        .btn-sm {
            padding: 0.5rem 0.875rem;
            font-size: 0.8125rem;
        }

        /* Table styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .table th, .table td {
            padding: 0.875rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .table th {
            background-color: var(--primary-dark);
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Form actions */
        .form-actions {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .main-content {
                grid-column: 1;
                padding: 1.5rem;
            }
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

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Exam Class Allocation</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Allocate Classes to Exams</h3>
                </div>
                <div class="card-body">
                    <!-- Session Selection -->
                    <div class="form-group">
                        <label for="session">Academic Session</label>
                        <select id="session" class="form-control" onchange="loadExamsAndClasses()">
                            <option value="">Select Session</option>
                            <?php foreach ($sessions as $session): ?>
                                <option value="<?= $session['id'] ?>" <?= ($session['id'] == ($current_session['id'] ?? '')) ? 'selected' : '' ?>>
                                    <?= esc($session['session']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Allocation Form -->
                    <form id="allocationForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exam">Select Exam</label>
                                    <select id="exam" name="exam_id" class="form-control" required>
                                        <option value="">Select Exam</option>
                                        <?php foreach ($exams as $exam): ?>
                                            <option value="<?= $exam['id'] ?>">
                                                <?= esc($exam['exam_name']) ?> (<?= date('d-m-Y', strtotime($exam['exam_date'])) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">Select Class</label>
                                    <select id="class_id" name="class_id" class="form-control" required>
                                        <option value="">Select Class</option>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Allocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Allocations -->
            <div class="card">
                <div class="card-header">
                    <h3>View Allocations</h3>
                </div>
                <div class="card-body">
                    <!-- Add Session Filter -->
                    <div class="form-group">
                        <label for="filterSession">Filter by Academic Session</label>
                        <select id="filterSession" class="form-control" onchange="loadAllocations(this.value)">
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

            <!-- Update the loadAllocations function in your JavaScript -->
            <script>
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
                                                onclick="deallocate(${allocation.exam_id}, ${allocation.class_id})">
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
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Error loading allocations</td></tr>';
                    }
                }
            </script>
        </div>
    </div>

    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        async function loadExamsAndClasses() {
            const sessionId = document.getElementById('session').value;
            if (!sessionId) return;

            try {
                // Load exams
                const examResponse = await fetch(`<?= base_url('exam/allocation/exams/') ?>/${sessionId}`);
                const examData = await examResponse.json();
                
                // Update exam dropdown
                const examSelect = document.getElementById('exam');
                examSelect.innerHTML = '<option value="">Select Exam</option>' + 
                    examData.data.map(exam => `
                        <option value="${exam.id}">
                            ${exam.exam_name} (${new Date(exam.exam_date).toLocaleDateString()})
                        </option>
                    `).join('');
            } catch (error) {
                Swal.fire('Error', 'Failed to load exam data', 'error');
            }
        }

        async function loadAllocations(sessionId) {
            try {
                const response = await fetch(`<?= base_url('exam/allocation/list/') ?>/${sessionId}`);
                const result = await response.json();
                
                const tbody = document.getElementById('allocationsTableBody');
                if (result.status === 'success' && result.data.length > 0) {
                    tbody.innerHTML = result.data.map(allocation => {
                        // Format the date properly
                        const examDate = allocation.exam_date ? new Date(allocation.exam_date).toLocaleDateString() : 'N/A';
                        
                        return `
                            <tr>
                                <td>${allocation.exam_name || 'N/A'}</td>
                                <td>${examDate}</td>
                                <td>${allocation.class || 'N/A'}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" 
                                        onclick="deallocate(${allocation.exam_id}, ${allocation.class_id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">No allocations found</td></tr>';
                }
            } catch (error) {
                console.error('Error loading allocations:', error);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">Error loading allocations</td></tr>';
            }
        }

        // Form submission handler
        document.getElementById('allocationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const examId = document.getElementById('exam').value;
            const sessionId = document.getElementById('session').value;
            const classId = document.getElementById('class_id').value;

            if (!examId || !classId) {
                Swal.fire('Error', 'Please select both an exam and a class', 'error');
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
                    Swal.fire('Success', 'Allocation saved successfully', 'success');
                    loadAllocations(sessionId);
                    this.reset();
                } else {
                    throw new Error(result.message || 'Failed to save allocation');
                }
            } catch (error) {
                Swal.fire('Error', error.message || 'Failed to save allocation', 'error');
            }
        });

        async function deallocate(examId, classId) {
            try {
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove the class allocation",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
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
                        Swal.fire('Deleted!', 'Allocation has been removed.', 'success');
                        loadAllocations(document.getElementById('session').value);
                    } else {
                        throw new Error(data.message || 'Failed to remove allocation');
                    }
                }
            } catch (error) {
                Swal.fire('Error', error.message || 'Failed to remove allocation', 'error');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sessionId = document.getElementById('session').value;
            if (sessionId) {
                loadExamsAndClasses();
            }

            // Add expandable sidebar functionality
            const expandableLinks = document.querySelectorAll('.expandable');
            expandableLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    const toggleIcon = this.querySelector('.toggle-icon');
                    if (submenu.style.display === 'none' || submenu.style.display === '') {
                        submenu.style.display = 'block';
                        toggleIcon.classList.remove('fa-chevron-down');
                        toggleIcon.classList.add('fa-chevron-up');
                    } else {
                        submenu.style.display = 'none';
                        toggleIcon.classList.remove('fa-chevron-up');
                        toggleIcon.classList.add('fa-chevron-down');
                    }
                });
            });
        });
    </script>
</body>
</html>