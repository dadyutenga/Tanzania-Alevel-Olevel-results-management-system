<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Class Allocation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Add before closing </body> tag -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Root Variables and Base Styles */
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

        /* Updated Sidebar Styles */
        .sidebar {
            background-color: var(--accent);
            color: var(--primary);
            padding: 1rem;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin: -1rem -1rem 1rem -1rem;
            background-color: var(--accent-light);
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

        /* Keep your existing styles but update the container structure */
        .main-content {
            grid-column: 2;
            padding: 2rem;
            background-color: var(--primary-dark);
        }

        .card {
            background: var(--primary);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Your existing form and table styles... */
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
            <?= view('shared/sidebar_menu') ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Keep your existing content but wrapped in the new structure -->
            <div class="card">
                <div class="card-header">
                    <h3>Exam Class Allocation</h3>
                </div>
                <div class="card-body">
                    <!-- Session Selection -->
                    <div class="form-group mb-4">
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
                    <form id="allocationForm" class="mb-4">
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
                                    <label>Select Classes</label>
                                    <div class="class-selection">
                                        <?php foreach ($classes as $class): ?>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="class_ids[]" 
                                                    value="<?= $class['id'] ?>" id="class_<?= $class['id'] ?>">
                                                <label class="form-check-label" for="class_<?= $class['id'] ?>">
                                                    <?= esc($class['class']) ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Allocation
                            </button>
                        </div>
                    </form>

                    <!-- Existing Allocations Table -->
                    <div class="table-responsive">
                        <h4>Current Allocations</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Exam Name</th>
                                    <th>Date</th>
                                    <th>Classes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="allocationsTableBody">
                                <?php if (!empty($allocations)): ?>
                                    <?php foreach ($allocations as $allocation): ?>
                                        <tr>
                                            <td><?= esc($allocation['exam_name']) ?></td>
                                            <td><?= date('d-m-Y', strtotime($allocation['exam_date'])) ?></td>
                                            <td><?= esc($allocation['class']) ?></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" 
                                                    onclick="deallocate(<?= $allocation['exam_id'] ?>, <?= $allocation['class_id'] ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No allocations found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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

                // Load allocations
                loadAllocations(sessionId);
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
                    tbody.innerHTML = result.data.map(allocation => `
                        <tr>
                            <td>${allocation.exam_name}</td>
                            <td>${new Date(allocation.exam_date).toLocaleDateString()}</td>
                            <td>${allocation.class}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" 
                                    onclick="deallocate(${allocation.exam_id}, ${allocation.class_id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">No allocations found</td></tr>';
                }
            } catch (error) {
                console.error('Error loading allocations:', error);
            }
        }

        // Form submission handler
        document.getElementById('allocationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const examId = document.getElementById('exam').value;
            const sessionId = document.getElementById('session').value;
            const classCheckboxes = document.querySelectorAll('input[name="class_ids[]"]:checked');
            const classIds = Array.from(classCheckboxes).map(cb => cb.value);

            if (!examId || classIds.length === 0) {
                Swal.fire('Error', 'Please select an exam and at least one class', 'error');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('exam_id', examId);
                formData.append('session_id', sessionId);
                formData.append('class_ids', JSON.stringify(classIds));

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
        });
    </script>

    <style>
        .class-selection {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }
        .form-check {
            margin-bottom: 8px;
        }
        .form-actions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .table th {
            background-color: #f8f9fa;
        }
    </style>
</body>
</html>

<style>
    :root {
        /* Update accent colors to match the image */
        --accent: #1e2837;
        --accent-light: #2a374b;
        --accent-hover: #2f3f57;
    }

    /* Updated Sidebar Styles */
    .sidebar {
        background-color: var(--accent);
        color: #fff;
        padding: 0;
        position: fixed;
        width: 250px;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        padding: 2rem 1.5rem;
        margin: 0;
        background-color: var(--accent);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header i {
        font-size: 2.5rem;
        margin-right: 1rem;
        color: #fff;
    }

    .sidebar-header h2 {
        font-size: 1.25rem;
        color: #fff;
        margin: 0;
        line-height: 1.2;
    }

    /* Menu Items Styling */
    .sidebar-menu {
        list-style: none;
        padding: 1rem 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin: 0;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: #fff;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background-color: var(--accent-hover);
    }

    .sidebar-menu i {
        width: 24px;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .sidebar-menu .expandable {
        position: relative;
    }

    .sidebar-menu .toggle-icon {
        position: absolute;
        right: 1.5rem;
        transition: transform 0.3s;
    }

    .sidebar-menu .expandable.active .toggle-icon {
        transform: rotate(180deg);
    }

    /* Updated submenu styles */
    .sidebar-menu .submenu {
        display: none;
        list-style: none;
        padding: 0;
        margin: 0;
        background-color: var(--accent-light);
    }

    .sidebar-menu .expandable.active + .submenu {
        display: block;
    }

    .sidebar-menu .submenu a {
        padding-left: 3.5rem;
        font-size: 0.95rem;
        opacity: 0.9;
    }

    /* Adjust main content margin */
    .main-content {
        margin-left: 250px;
        padding: 2rem;
    }

    /* Make dashboard grid layout work with fixed sidebar */
    .dashboard {
        display: block;
    }
</style>