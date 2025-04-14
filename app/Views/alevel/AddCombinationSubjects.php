<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Manage A-Level Subjects</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Using the same CSS styles as AddCombinations.php -->
    <style>
        /* Light Theme with Green Accents */
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

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Reusing the same styles from AddCombinations.php */
        .sidebar {
            width: 250px;
            background-color: var(--card-bg);
            border-right: 1px solid var(--border);
            padding: 1rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }

        .form-container {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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
            padding: 0.85rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .btn {
            padding: 0.85rem 2rem;
            border-radius: var(--button-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: black;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .results-table-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
        }

        .results-table th,
        .results-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .results-table th {
            background-color: var(--primary);
            color: black;
            font-weight: 600;
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

        .required {
            color: #ef4444;
            margin-left: 0.25rem;
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
                    <h1>Manage A-Level Subjects</h1>
                    <p>Add, edit, or delete subjects for A-Level combinations</p>
                </div>

                <div class="form-container">
                    <h2 class="form-title">
                        <i class="fas fa-<?php echo isset($edit_subject) ? 'edit' : 'plus-circle'; ?>"></i>
                        <?php echo isset($edit_subject) ? 'Edit Subject' : 'Add New Subject'; ?>
                    </h2>

                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success">
                            <?= session('message') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= isset($edit_subject) ? base_url('alevel/subjects/update/' . $edit_subject['id']) : base_url('alevel/subjects/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="form-group">
                                <label for="combination_id">Combination <span class="required">*</span></label>
                                <select id="combination_id" name="combination_id" class="form-control" required>
                                    <option value="">Select Combination</option>
                                    <?php foreach ($combinations as $combination): ?>
                                        <option value="<?= $combination['id'] ?>" <?= (isset($edit_subject) && $edit_subject['combination_id'] == $combination['id']) ? 'selected' : '' ?>>
                                            <?= esc($combination['combination_code']) ?> - <?= esc($combination['combination_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subject_name">Subject Name <span class="required">*</span></label>
                                <input type="text" id="subject_name" name="subject_name" class="form-control" 
                                       value="<?= isset($edit_subject) ? esc($edit_subject['subject_name']) : old('subject_name') ?>" 
                                       placeholder="Enter subject name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label for="subject_type">Subject Type <span class="required">*</span></label>
                                <select id="subject_type" name="subject_type" class="form-control" required>
                                    <option value="major" <?= (isset($edit_subject) && $edit_subject['subject_type'] == 'major') ? 'selected' : '' ?>>Major</option>
                                    <option value="additional" <?= (isset($edit_subject) && $edit_subject['subject_type'] == 'additional') ? 'selected' : '' ?>>Additional</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active">Status <span class="required">*</span></label>
                                <select id="is_active" name="is_active" class="form-control" required>
                                    <option value="yes" <?= (isset($edit_subject) && $edit_subject['is_active'] == 'yes') ? 'selected' : '' ?>>Active</option>
                                    <option value="no" <?= (isset($edit_subject) && $edit_subject['is_active'] == 'no') ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-<?php echo isset($edit_subject) ? 'save' : 'plus'; ?>"></i>
                                <?php echo isset($edit_subject) ? 'Update Subject' : 'Add Subject'; ?>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="results-table-container">
                    <h2 class="form-title" style="padding: 1rem; margin-bottom: 0;">
                        <i class="fas fa-list"></i> Existing Subjects
                    </h2>
                    <?php if (empty($subjects)): ?>
                        <div class="empty-results">
                            <i class="fas fa-database"></i>
                            <p>No subjects found. Add a new subject to get started.</p>
                        </div>
                    <?php else: ?>
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Combination</th>
                                    <th>Subject Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
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
                                            echo esc($combination['combination_code']);
                                            ?>
                                        </td>
                                        <td><?= esc($subject['subject_name']) ?></td>
                                        <td><?= ucfirst(esc($subject['subject_type'])) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $subject['is_active'] == 'yes' ? 'success' : 'secondary' ?>">
                                                <?= $subject['is_active'] == 'yes' ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('alevel/subjects/edit/' . $subject['id']) ?>" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button onclick="confirmDelete(<?= $subject['id'] ?>)" class="btn btn-primary" style="padding: 0.5rem 1rem; background-color: #ef4444;">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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