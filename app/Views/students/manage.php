<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Student Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        .page-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            color: #333;
            font-size: 24px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 800px;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h3 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4AE54A;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group label .required {
            color: #dc3545;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4AE54A;
        }

        .form-group .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-primary {
            background: #4AE54A;
            color: white;
        }

        .btn-primary:hover {
            background: #3dd33d;
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background: #c82333;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .form-card {
                padding: 20px;
            }

            .form-row {
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
    <div class="container">
        <?= view('shared/sidebar_menu') ?>

        <div class="main-content" id="mainContent">
            <div class="page-header">
                <h1><i class="fas fa-user-edit"></i> <?= esc($title) ?></h1>
                <a href="<?= base_url('students') ?>" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <div class="form-card">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-error">
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-error">
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= isset($student) ? base_url('students/update/' . $student['id']) : base_url('students/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-section">
                        <h3>Personal Information</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstname">First Name <span class="required">*</span></label>
                                <input type="text" id="firstname" name="firstname" 
                                       value="<?= old('firstname', $student['firstname'] ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="middlename">Middle Name</label>
                                <input type="text" id="middlename" name="middlename" 
                                       value="<?= old('middlename', $student['middlename'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label for="lastname">Last Name <span class="required">*</span></label>
                                <input type="text" id="lastname" name="lastname" 
                                       value="<?= old('lastname', $student['lastname'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="dob">Date of Birth <span class="required">*</span></label>
                                <input type="date" id="dob" name="dob" 
                                       value="<?= old('dob', $student['dob'] ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="gender">Gender <span class="required">*</span></label>
                                <select id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" <?= (old('gender', $student['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= (old('gender', $student['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= (old('gender', $student['gender'] ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="guardian_phone">Guardian Phone <span class="required">*</span></label>
                                <input type="tel" id="guardian_phone" name="guardian_phone" 
                                       value="<?= old('guardian_phone', $student['guardian_phone'] ?? '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Academic Information</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="session_id">Session <span class="required">*</span></label>
                                <select id="session_id" name="session_id" required>
                                    <option value="">Select Session</option>
                                    <?php foreach ($sessions as $sess): ?>
                                        <option value="<?= $sess['id'] ?>" 
                                                <?= (old('session_id', $studentSession['session_id'] ?? $currentSession['id'] ?? '') === $sess['id']) ? 'selected' : '' ?>>
                                            <?= esc($sess['session']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="class_id">Class <span class="required">*</span></label>
                                <select id="class_id" name="class_id" required>
                                    <option value="">Select Class</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>" 
                                                <?= (old('class_id', $studentSession['class_id'] ?? '') === $class['id']) ? 'selected' : '' ?>>
                                            <?= esc($class['class']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="section_id">Section <span class="required">*</span></label>
                                <select id="section_id" name="section_id" required>
                                    <option value="">Select Section</option>
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?= $section['id'] ?>" 
                                                <?= (old('section_id', $studentSession['section_id'] ?? '') === $section['id']) ? 'selected' : '' ?>>
                                            <?= esc($section['section']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="is_active">Status <span class="required">*</span></label>
                                <select id="is_active" name="is_active" required>
                                    <option value="yes" <?= (old('is_active', $student['is_active'] ?? 'yes') === 'yes') ? 'selected' : '' ?>>Active</option>
                                    <option value="no" <?= (old('is_active', $student['is_active'] ?? 'yes') === 'no') ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="<?= base_url('students') ?>" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?= isset($student) ? 'Update' : 'Save' ?> Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
