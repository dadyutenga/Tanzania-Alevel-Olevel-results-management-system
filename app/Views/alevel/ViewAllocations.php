<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View A-Level Allocations</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --primary: #4AE54A;
            --primary-dark: #3AD03A;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.5;
        }

        .container-fluid {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--primary);
            color: black;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-info {
            background-color: #3b82f6;
            color: white;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1rem;
        }

        .table th,
        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .table th {
            background-color: var(--primary);
            color: black;
            font-weight: 600;
            text-align: left;
        }

        .table tbody tr:hover {
            background-color: rgba(74, 229, 74, 0.05);
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .text-muted {
            color: var(--text-secondary);
        }

        .d-block {
            display: block;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }

            .card-header {
                flex-direction: column;
                gap: 1rem;
            }

            .table {
                display: block;
                overflow-x: auto;
            }

            .btn-sm {
                padding: 0.4rem 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">A-Level Combination Allocations</h3>
                        <div class="card-tools">
                            <a href="<?= base_url('alevel/allocations/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Allocation
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('message')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('message') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Search and Filter Section -->
                        <div class="filters-section" style="margin-bottom: 2rem;">
                            <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div class="form-group">
                                    <label for="combination_filter">Filter by Combination</label>
                                    <select class="form-control" id="combination_filter">
                                        <option value="">All Combinations</option>
                                        <?php foreach ($combinations as $combination): ?>
                                            <option value="<?= $combination['id'] ?>">
                                                <?= esc($combination['combination_name']) ?> (<?= esc($combination['combination_code']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="class_filter">Filter by Class</label>
                                    <select class="form-control" id="class_filter">
                                        <option value="">All Classes</option>
                                        <?php foreach ($classes as $class): ?>
                                            <option value="<?= $class['id'] ?>"><?= esc($class['class']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="session_filter">Filter by Session</label>
                                    <select class="form-control" id="session_filter">
                                        <option value="">All Sessions</option>
                                        <?php foreach ($sessions as $session): ?>
                                            <option value="<?= $session['id'] ?>"><?= esc($session['session']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status_filter">Filter by Status</label>
                                    <select class="form-control" id="status_filter">
                                        <option value="">All Status</option>
                                        <option value="yes">Active</option>
                                        <option value="no">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="search-box" style="margin-top: 1rem;">
                                <input type="text" id="search_input" class="form-control" placeholder="Search in any column...">
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Combination</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Session</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allocations as $index => $allocation): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <?= esc($allocation['combination_name']) ?>
                                            <small class="d-block text-muted"><?= esc($allocation['combination_code']) ?></small>
                                        </td>
                                        <td><?= esc($allocation['class_name']) ?></td>
                                        <td><?= esc($allocation['section_name'] ?? 'N/A') ?></td>
                                        <td><?= esc($allocation['session_name']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $allocation['is_active'] == 'yes' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($allocation['is_active']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('alevel/allocations/edit/' . $allocation['id']) ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete(<?= $allocation['id'] ?>)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($allocations)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No allocations found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('.table').DataTable({
                pageLength: 10,
                order: [[1, 'asc']],
                dom: '<"top"l>rt<"bottom"ip>',
                language: {
                    search: '',
                    searchPlaceholder: 'Search...'
                }
            });

            // Custom search input
            $('#search_input').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filter by Combination
            $('#combination_filter').on('change', function() {
                var searchTerm = $(this).find('option:selected').text();
                if ($(this).val() === '') {
                    table.column(1).search('').draw();
                } else {
                    table.column(1).search(searchTerm).draw();
                }
            });

            // Filter by Class
            $('#class_filter').on('change', function() {
                var searchTerm = $(this).find('option:selected').text();
                if ($(this).val() === '') {
                    table.column(2).search('').draw();
                } else {
                    table.column(2).search(searchTerm).draw();
                }
            });

            // Filter by Session
            $('#session_filter').on('change', function() {
                var searchTerm = $(this).find('option:selected').text();
                if ($(this).val() === '') {
                    table.column(4).search('').draw();
                } else {
                    table.column(4).search(searchTerm).draw();
                }
            });

            // Filter by Status
            $('#status_filter').on('change', function() {
                var searchTerm = $(this).val() === '' ? '' : 
                                $(this).val() === 'yes' ? 'Active' : 'Inactive';
                table.column(5).search(searchTerm).draw();
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4AE54A',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('alevel/allocations/delete/') ?>' + id;
                }
            });
        }
    </script>
</body>
</html>
