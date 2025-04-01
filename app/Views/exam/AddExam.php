<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Add Exam</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Update root variables to match the screenshot */
        :root {
            --primary: #f8f9fa;
            --primary-dark: #f1f3f5;
            --secondary: #e9ecef;
            --accent: #1e2837;        /* Dark blue from screenshot */
            --accent-light: #2a374b;  /* Lighter blue for hover */
            --accent-hover: #2f3f57;  /* Hover state blue */
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

        /* Sidebar styles from index.php */
        .sidebar {
            background-color: var(--accent);
            color: #fff;
            padding: 0;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 2rem 1.5rem;
            margin: 0;
            background-color: var(--accent);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Update menu items */
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .sidebar-menu a:hover, 
        .sidebar-menu a.active {
            background-color: var(--accent-hover);
            color: #fff;
        }

        /* Update form styles */
        .form-container {
            background: #fff;
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        /* Update button styles */
        .btn {
            background-color: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: var(--accent-hover);
        }

        .btn-secondary {
            background-color: #e9ecef;
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background-color: #dde1e4;
        }

        /* Update form controls */
        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: var(--radius);
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(30, 40, 55, 0.1);
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
                <h1>Add New Exam</h1>
            </div>
            
            <div class="form-container">
                <form id="addExamForm" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_name">Exam Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="exam_name" name="exam_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_date">Exam Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="exam_date" name="exam_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="session_id">Academic Session <span class="text-danger">*</span></label>
                                <select class="form-control" id="session_id" name="session_id" required>
                                    <option value="">Select Academic Session</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_active">Status</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="yes">Active</option>
                                    <option value="no">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions" style="margin-top: 2rem;">
                        <button type="submit" class="btn">
                            <i class="fas fa-save"></i> Save Exam
                        </button>
                        <a href="<?= base_url('exam') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addExamForm');
        const sessionSelect = document.getElementById('session_id');

        // Load all sessions
        async function loadSessions() {
            try {
                const response = await fetch('<?= base_url('exam/getSessions') ?>');
                const result = await response.json();

                if (result.status === 'success') {
                    // Clear existing options except the first one
                    sessionSelect.innerHTML = '<option value="">Select Academic Session</option>';

                    // Add sessions to dropdown
                    result.data.forEach(session => {
                        const option = document.createElement('option');
                        option.value = session.id;
                        option.textContent = session.session; // Just show the session name without (Current)
                        sessionSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load academic sessions'
                });
            }
        }

        // Call loadSessions immediately
        loadSessions();

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // Validate session selection
            if (!sessionSelect.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select an academic session'
                });
                return;
            }

            try {
                const formData = new FormData(form);
                const response = await fetch('<?= base_url('exam/store') ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '<?= base_url('exam') ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Failed to create exam'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while saving the exam'
                });
            }
        });

        // Add form validation styles
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('invalid', function() {
                input.classList.add('is-invalid');
            });
            input.addEventListener('input', function() {
                if (input.validity.valid) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            });
        });
    });
    </script>
</body>
</html>
