<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Exam Allocations</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .btn-success {
            background-color: var(--success);
            color: white;
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

        .table .select-checkbox {
            width: 20px;
        }

        /* Bulk actions */
        .bulk-actions {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        /* Form actions */
        .form-actions {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
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
            <div class="header">
                <h1>Manage Exam Allocations</h1>
            </div>

            <!-- Filter Form -->
            <div class="card">
                <div class="card-header">
                    <h3>Filter Allocations</h3>
                </div>
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_session">Academic Session</label>
                                    <select id="filter_session" class="form-control">
                                        <option value="">All Sessions</option>
                                        <?php foreach ($sessions as $session): ?>
                                            <option value="<?= $session['id'] ?>"><?= esc($session['session']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_exam">Exam</label>
                                    <select id="filter_exam" class="form-control">
                                        <option value="">All Exams</option>
                                        <?php foreach ($exams as $exam): ?>
                                            <option value="<?= $exam['id'] ?>"><?= esc($exam['exam_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_class">Class</label>
                                    <select id="filter_class" class="form-control">
                                        <option value="">All Classes</option>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Allocations Table -->
            <div class="card">
                <div class="card-header">
                    <h3>Exam Allocations</h3>
                    <div class="bulk-actions">
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteSelected()">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="select-checkbox">
                                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                </th>
                                <th>Exam Name</th>
                                <th>Date</th>
                                <th>Class</th>
                                <th>Session</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="allocationsTableBody">
                            <?php if (!empty($allocations)): ?>
                                <?php foreach ($allocations as $allocation): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="select-checkbox" value="<?= $allocation['id'] ?>">
                                        </td>
                                        <td><?= esc($allocation['exam_name']) ?></td>
                                        <td><?= date('d-m-Y', strtotime($allocation['exam_date'])) ?></td>
                                        <td><?= esc($allocation['class']) ?></td>
                                        <td><?= esc($allocation['session']) ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" onclick="editAllocation(<?= $allocation['id'] ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteAllocation(<?= $allocation['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No allocations found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Allocation Modal -->
    <div class="modal fade" id="editAllocationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Allocation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAllocationForm">
                        <input type="hidden" id="edit_allocation_id" name="id">
                        <div class="form-group">
                            <label for="edit_exam_id">Exam</label>
                            <select id="edit_exam_id" name="exam_id" class="form-control" required>
                                <option value="">Select Exam</option>
                                <?php foreach ($exams as $exam): ?>
                                    <option value="<?= $exam['id'] ?>"><?= esc($exam['exam_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_class_id">Class</label>
                            <select id="edit_class_id" name="class_id" class="form-control" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_session_id">Session</label>
                            <select id="edit_session_id" name="session_id" class="form-control" required>
                                <option value="">Select Session</option>
                                <?php foreach ($sessions as $session): ?>
                                    <option value="<?= $session['id'] ?>"><?= esc($session['session']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateAllocation()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize modal
        const editModal = new bootstrap.Modal(document.getElementById('editAllocationModal'));

        // Toggle select all checkboxes
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.select-checkbox');
            checkboxes.forEach(cb => {
                if (cb !== checkbox) {
                    cb.checked = checkbox.checked;
                }
            });
        }

        // Get selected allocation IDs
        function getSelectedIds() {
            const checkboxes = document.querySelectorAll('.select-checkbox:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        // Delete selected allocations
        function deleteSelected() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Error', 'Please select at least one allocation to delete', 'error');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${selectedIds.length} allocation(s)`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= base_url('exam/allocation/bulk-delete') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ ids: selectedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Deleted!', 'Selected allocations have been deleted.', 'success');
                            loadAllocations();
                        } else {
                            throw new Error(data.message || 'Failed to delete allocations');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error!', error.message, 'error');
                    });
                }
            });
        }

        // Delete single allocation
        function deleteAllocation(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= base_url('exam/allocation/delete') ?>/${id}`, {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Deleted!', 'Allocation has been deleted.', 'success');
                            loadAllocations();
                        } else {
                            throw new Error(data.message || 'Failed to delete allocation');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error!', error.message, 'error');
                    });
                }
            });
        }

        // Edit allocation - open modal
        function editAllocation(id) {
            fetch(`<?= base_url('exam/allocation/get') ?>/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('edit_allocation_id').value = data.data.id;
                        document.getElementById('edit_exam_id').value = data.data.exam_id;
                        document.getElementById('edit_class_id').value = data.data.class_id;
                        document.getElementById('edit_session_id').value = data.data.session_id;
                        editModal.show();
                    } else {
                        throw new Error(data.message || 'Failed to load allocation data');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', error.message, 'error');
                });
        }

        // Update allocation
        function updateAllocation() {
            const formData = new FormData(document.getElementById('editAllocationForm'));
            
            fetch('<?= base_url('exam/allocation/update') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success', 'Allocation updated successfully', 'success');
                    editModal.hide();
                    loadAllocations();
                } else {
                    throw new Error(data.message || 'Failed to update allocation');
                }
            })
            .catch(error => {
                Swal.fire('Error', error.message, 'error');
            });
        }

        // Load allocations with filters
        function loadAllocations() {
            const sessionId = document.getElementById('filter_session').value;
            const examId = document.getElementById('filter_exam').value;
            const classId = document.getElementById('filter_class').value;

            let url = '<?= base_url('exam/allocation/list') ?>';
            const params = new URLSearchParams();
            
            if (sessionId) params.append('session_id', sessionId);
            if (examId) params.append('exam_id', examId);
            if (classId) params.append('class_id', classId);

            if (params.toString()) {
                url += `?${params.toString()}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('allocationsTableBody');
                    if (data.status === 'success' && data.data.length > 0) {
                        tbody.innerHTML = data.data.map(allocation => `
                            <tr>
                                <td>
                                    <input type="checkbox" class="select-checkbox" value="${allocation.id}">
                                </td>
                                <td>${allocation.exam_name}</td>
                                <td>${new Date(allocation.exam_date).toLocaleDateString()}</td>
                                <td>${allocation.class}</td>
                                <td>${allocation.session}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editAllocation(${allocation.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteAllocation(${allocation.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No allocations found</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error loading allocations:', error);
                });
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('filter_session').value = '';
            document.getElementById('filter_exam').value = '';
            document.getElementById('filter_class').value = '';
            loadAllocations();
        }

        // Initialize filters form
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            loadAllocations();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllocations();
        });
    </script>
</body>
</html>