<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View A-Level Combination Subjects</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        /* Reuse the same root styles from AddCombinationSubjects.php */
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --primary: #4AE54A;
            --primary-dark: #3AD03A;
            --primary-light: #5FF25F;
            --secondary: #f1f5f9;
            --accent: #1a1a1a;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --button-radius: 50px;
        }

        /* Add new styles specific to this view */
        .filters-container {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .filters-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            border-color: var(--border);
            border-radius: var(--radius);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.5rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.5rem;
            margin: 0 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .status-inactive {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>ExamResults</span>
            </div>
            <ul class="sidebar-menu">
                <?php include(APPPATH . 'Views/shared/sidebar_menu.php'); ?>
            </ul>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>View A-Level Combination Subjects</h1>
                    <p>Search, filter, and manage subjects across different combinations</p>
                </div>

                <div class="filters-container">
                    <h3 class="filters-title">
                        <i class="fas fa-filter"></i> Filter Subjects
                    </h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="combination-filter">Combination</label>
                            <select id="combination-filter" class="form-control select2">
                                <option value="">All Combinations</option>
                                <?php foreach ($combinations as $combination): ?>
                                    <option value="<?= $combination['id'] ?>">
                                        <?= esc($combination['combination_code']) ?> - <?= esc($combination['combination_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="type-filter">Subject Type</label>
                            <select id="type-filter" class="form-control select2">
                                <option value="">All Types</option>
                                <option value="major">Major</option>
                                <option value="additional">Additional</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="status-filter">Status</label>
                            <select id="status-filter" class="form-control select2">
                                <option value="">All Status</option>
                                <option value="yes">Active</option>
                                <option value="no">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="results-table-container">
                    <div class="table-header d-flex justify-content-between align-items-center mb-3">
                        <h3 class="form-title">
                            <i class="fas fa-list"></i> Subject List
                        </h3>
                        <a href="<?= base_url('alevel/subjects') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Subject
                        </a>
                    </div>
                    
                    <table id="subjects-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Combination</th>
                                <th>Subject Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects as $subject): ?>
                                <tr>
                                    <td>
                                        <?php 
                                        $combination = array_filter($combinations, function($c) use ($subject) {
                                            return $c['id'] == $subject['combination_id'];
                                        });
                                        $combination = reset($combination);
                                        echo esc($combination['combination_code']) . ' - ' . esc($combination['combination_name']);
                                        ?>
                                    </td>
                                    <td><?= esc($subject['subject_name']) ?></td>
                                    <td><?= ucfirst(esc($subject['subject_type'])) ?></td>
                                    <td>
                                        <span class="status-badge <?= $subject['is_active'] == 'yes' ? 'status-active' : 'status-inactive' ?>">
                                            <?= $subject['is_active'] == 'yes' ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td><?= date('Y-m-d H:i', strtotime($subject['created_at'])) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= base_url('alevel/subjects/edit/' . $subject['id']) ?>" 
                                               class="btn btn-primary btn-sm" 
                                               title="Edit Subject">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete(<?= $subject['id'] ?>)" 
                                                    class="btn btn-danger btn-sm"
                                                    title="Delete Subject">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%'
            });

            // Initialize DataTable
            const table = $('#subjects-table').DataTable({
                pageLength: 10,
                order: [[4, 'desc']], // Sort by created_at by default
                responsive: true
            });

            // Filter handling
            $('#combination-filter, #type-filter, #status-filter').on('change', function() {
                const combinationVal = $('#combination-filter').val();
                const typeVal = $('#type-filter').val();
                const statusVal = $('#status-filter').val();

                table.columns(0).search(combinationVal);
                table.columns(2).search(typeVal);
                table.columns(3).search(statusVal);
                table.draw();
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
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `<?= base_url('alevel/subjects/delete') ?>/${id}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '<?= csrf_token() ?>';
                    csrfToken.value = '<?= csrf_hash() ?>';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
