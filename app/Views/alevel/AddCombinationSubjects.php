<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Add A-Level Subjects</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
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
            width: 100%;
            justify-content: center;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .alert-success {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
            border: 1px solid var(--primary);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid #ef4444;
        }

        .view-subjects-link {
            text-align: center;
            margin-top: 1rem;
        }

        .view-subjects-link a {
            color: var(--text-secondary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .view-subjects-link a:hover {
            color: var(--primary-dark);
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
                    <h1>Add A-Level Subject</h1>
                    <p>Create a new subject for A-Level combinations</p>
                </div>

                <div class="form-container">
                    <h2 class="form-title">
                        <i class="fas fa-plus-circle"></i>
                        Add New Subject
                    </h2>

                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?= session('message') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('alevel/subjects/store') ?>" method="post" id="addSubjectForm">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="form-group">
                                <label for="combination_id">Combination <span class="required">*</span></label>
                                <select id="combination_id" name="combination_id" class="form-control" required>
                                    <option value="">Select Combination</option>
                                    <?php foreach ($combinations as $combination): ?>
                                        <option value="<?= $combination['id'] ?>">
                                            <?= esc($combination['combination_code']) ?> - <?= esc($combination['combination_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subject_name">Subject Name <span class="required">*</span></label>
                                <input type="text" id="subject_name" name="subject_name" class="form-control" 
                                       value="<?= old('subject_name') ?>" 
                                       placeholder="Enter subject name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label for="subject_type">Subject Type <span class="required">*</span></label>
                                <select id="subject_type" name="subject_type" class="form-control" required>
                                    <option value="major">Major</option>
                                    <option value="additional">Additional</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active">Status <span class="required">*</span></label>
                                <select id="is_active" name="is_active" class="form-control" required>
                                    <option value="yes">Active</option>
                                    <option value="no">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Add Subject
                            </button>
                        </div>
                    </form>

                    <div class="view-subjects-link">
                        <a href="<?= base_url('alevel/subjects/view') ?>">
                            <i class="fas fa-list"></i>
                            View All Subjects
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('addSubjectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Submit the form
            this.submit();

            // Show loading state
            Swal.fire({
                title: 'Adding Subject...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        <?php if (session()->has('message')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?= session('message') ?>',
            confirmButtonColor: '#4AE54A'
        });
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?= session('error') ?>',
            confirmButtonColor: '#ef4444'
        });
        <?php endif; ?>
    </script>
    </body>
</html>