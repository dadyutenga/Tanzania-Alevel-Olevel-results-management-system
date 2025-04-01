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
            --primary: #ffffff;
            --primary-dark: #f8fafc;
            --secondary: #f1f5f9;
            --accent: #1e293b;
            --accent-light: #334155;
            --accent-hover: #475569;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --success: #31c48d;
            --warning: #f59e0b;
            --danger: #e53e3e;
            --radius: 6px;
        }

        /* Sidebar styles */
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
            gap: 0.75rem;
            padding: 1.5rem;
            border-bottom: 1px solid var(--accent-light);
        }

        .sidebar-header i {
            font-size: 1.5rem;
            color: #fff;
        }

        .sidebar-header h2 {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
        }

        /* Form Container */
        .form-container {
            background: #fff;
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
        }

        .form-group label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            color: var(--text-primary);
            background-color: #fff;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--accent-light);
            box-shadow: 0 0 0 2px rgba(30, 41, 59, 0.1);
        }

        /* Button styles */
        .btn {
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: var(--radius);
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: var(--accent-light);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        /* Header styles */
        .header {
            margin-bottom: 1.5rem;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Main content padding */
        .main-content {
            margin-left: 250px;
            padding: 1.5rem;
            background-color: var(--primary-dark);
        }

        .header {
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Form Container */
        .form-container {
            background: #fff;
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
        }

        /* Grid Layout */
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
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
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(30, 40, 55, 0.1);
            outline: none;
        }

        .text-danger {
            color: var(--danger);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn i {
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--accent);
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: #dde1e4;
        }

        /* Validation Styles */
        .is-invalid {
            border-color: var(--danger);
        }

        .is-valid {
            border-color: var(--success);
        }

        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
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
