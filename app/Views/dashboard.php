<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Color Scheme - KEEPING YOUR ORIGINAL COLORS */
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --sidebar-bg: #1e1b4b;
            --sidebar-hover: #312e81;
            --text-light: #f8fafc;
            --text-dark: #1e293b;
            --card-bg: #ffffff;
            --body-bg: #f1f5f9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --border-radius: 8px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.2s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            color: var(--text-dark);
            line-height: 1.5;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        /* Sidebar - VISUAL UPDATE ONLY (no structural changes) */
        .sidebar {
            background-color: var(--sidebar-bg);
            color: var(--text-light);
            padding: 2rem 1rem;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header i {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            color: var(--primary);
        }

        .sidebar-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-light);
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .sidebar-menu a:hover, 
        .sidebar-menu a.active {
            background-color: var(--sidebar-hover);
            color: white;
        }

        .sidebar-menu i {
            font-size: 1.1rem;
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Main Content - VISUAL UPDATE ONLY */
        .main-content {
            grid-column: 2;
            padding: 2rem;
            background-color: var(--body-bg);
        }

        .header {
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Dashboard Stats - ENHANCED CARDS */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.75rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .stat-card-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        /* Icon-specific colors */
        .stat-card-icon.blue {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .stat-card-icon.green {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-card-title {
            font-size: 0.95rem;
            color: var(--text-dark);
            font-weight: 500;
            opacity: 0.9;
        }

        .stat-card-value {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0.5rem 0;
        }

        .stat-card-trend {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .trend-up {
            color: var(--success);
        }

        .trend-down {
            color: var(--danger);
        }

        /* Responsive - NO CHANGES TO BREAKPOINTS */
        @media (max-width: 1024px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .main-content {
                grid-column: 1;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar - NO STRUCTURAL CHANGES -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Results Management</h2>
            </div>
            <?= view('shared/sidebar_menu') ?>
        </div>
        
        <!-- Main Content - NO CHANGES TO MARKUP -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
            </div>
            
            <div class="dashboard-stats">
                <!-- Total Students Card -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="stat-card-title">Total Students</h3>
                    </div>
                    <div class="stat-card-value"><?= number_format($totalStudents) ?></div>
                    <div class="stat-card-trend <?= $studentGrowth > 0 ? 'trend-up' : 'trend-down' ?>">
                        <i class="fas fa-arrow-<?= $studentGrowth > 0 ? 'up' : 'down' ?>"></i>
                        <span><?= abs($studentGrowth) ?>% this month</span>
                    </div>
                </div>

                <!-- Active Exams Card -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon blue">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="stat-card-title">Active Exams</h3>
                    </div>
                    <div class="stat-card-value"><?= $activeExams ?></div>
                    <div class="stat-card-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span><?= $newExamsThisWeek ?> new this week</span>
                    </div>
                </div>

                <!-- Completed Exams Card -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="stat-card-title">Completed Exams</h3>
                    </div>
                    <div class="stat-card-value"><?= $completedExams ?></div>
                    <div class="stat-card-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span><?= $completedExams ?> this month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- NO CHANGES TO YOUR ORIGINAL SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const expandableLinks = document.querySelectorAll('.expandable');
            expandableLinks.forEach(link => {
                link.addEventListener('click', function (e) {
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