<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results Management System</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.925rem;
            background: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
        }

        .dashboard {
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
            transition: all 0.3s ease;
        }

        .logo {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .logo i {
            color: var(--primary);
            font-size: 1.75rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            position: relative;
            margin-bottom: 0.25rem;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background-color: var(--primary);
            color: black;
        }

        .sidebar-menu li a i {
            margin-right: 0.75rem;
            width: 16px;
            text-align: center;
        }

        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: var(--secondary);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu li a {
            padding: 0.5rem 1.5rem 0.5rem 2.5rem;
            font-size: 0.85rem;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            margin-left: auto;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 1.5rem 1rem;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards;
        }

        .header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: -0.025em;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: var(--button-radius);
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: black;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            background-color: var(--secondary);
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.25rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary);
            border-top-left-radius: var(--radius);
            border-top-right-radius: var(--radius);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .stat-card-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 0.75rem;
            font-size: 1.25rem;
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .stat-card-title {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 500;
        }

        .stat-card-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .stat-card-trend {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .trend-up {
            color: var(--success);
        }

        .trend-down {
            color: var(--danger);
        }

        .trend-neutral {
            color: var(--text-secondary);
        }

        .dashboard-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .dashboard-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards 0.2s;
        }

        .dashboard-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .dashboard-card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .dashboard-card-body {
            padding: 1.5rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .recent-activity {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 0.75rem;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .activity-icon.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .activity-icon.warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .activity-icon.danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .activity-icon.info {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .recent-results {
            width: 100%;
            border-collapse: collapse;
        }

        .recent-results th,
        .recent-results td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .recent-results th {
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .recent-results tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background: var(--primary);
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: var(--shadow);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards 0.3s;
        }

        .quick-action-card {
            background: var(--card-bg);
            padding: 1.25rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .quick-action-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .quick-action-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .quick-action-description {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {
            .dashboard-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem 0.5rem;
            }
            
            .container {
                padding: 0 0.5rem;
            }

            .sidebar-toggle {
                display: block;
            }

            .dashboard-stats {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="dashboard">
        <div class="sidebar" id="sidebar">
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
                    <h1>Dashboard Overview Analytics</h1>
                    <div class="header-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-calendar"></i>
                            <span>Academic Year: 2023-2024</span>
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-download"></i>
                            <span>Export Report</span>
                        </button>
                    </div>
                </div>
                
                <div class="dashboard-stats">
                    <div class="stat-card" style="animation-delay: 0.1s;">
                        <div class="stat-card-header">
                            <div class="stat-card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="stat-card-title">Total Students</h3>
                        </div>
                        <div class="stat-card-value">28</div>
                        <div class="stat-card-trend trend-up">
                            <i class="fas fa-arrow-up"></i>
                            <span>12% from last term</span>
                        </div>
                    </div>
                    
                    <div class="stat-card" style="animation-delay: 0.2s;">
                        <div class="stat-card-header">
                            <div class="stat-card-icon" style="background-color: rgba(59, 130, 246, 0.1); color: var(--info);">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3 class="stat-card-title">Active Exams</h3>
                        </div>
                        <div class="stat-card-value">5</div>
                        <div class="stat-card-trend trend-neutral">
                            <i class="fas fa-minus"></i>
                            <span>Same as last term</span>
                        </div>
                    </div>
                    
                    <div class="stat-card" style="animation-delay: 0.3s;">
                        <div class="stat-card-header">
                            <div class="stat-card-icon" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3 class="stat-card-title">Pass Rate</h3>
                        </div>
                        <div class="stat-card-value">85%</div>
                        <div class="stat-card-trend trend-up">
                            <i class="fas fa-arrow-up"></i>
                            <span>5% from last term</span>
                        </div>
                    </div>
                    
                    <div class="stat-card" style="animation-delay: 0.4s;">
                        <div class="stat-card-header">
                            <div class="stat-card-icon" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning);">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3 class="stat-card-title">Average Grade</h3>
                        </div>
                        <div class="stat-card-value">B+</div>
                        <div class="stat-card-trend trend-up">
                            <i class="fas fa-arrow-up"></i>
                            <span>From B last term</span>
                        </div>
                    </div>
                </div>
                
                <div class="quick-actions">
                    <div class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="quick-action-title">Add Student</h3>
                        <p class="quick-action-description">Register a new student</p>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="quick-action-icon" style="background-color: rgba(59, 130, 246, 0.1); color: var(--info);">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="quick-action-title">Create Exam</h3>
                        <p class="quick-action-description">Set up a new examination</p>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="quick-action-icon" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success);">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="quick-action-title">Enter Results</h3>
                        <p class="quick-action-description">Record exam scores</p>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="quick-action-icon" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning);">
                            <i class="fas fa-print"></i>
                        </div>
                        <h3 class="quick-action-title">Generate Reports</h3>
                        <p class="quick-action-description">Create result reports</p>
                    </div>
                </div>
                
                <div class="dashboard-row">
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h3 class="dashboard-card-title">Performance by Subject</h3>
                            <div class="dashboard-card-actions">
                                <button class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                    <i class="fas fa-filter"></i>
                                    <span>Filter</span>
                                </button>
                            </div>
                        </div>
                        <div class="dashboard-card-body">
                            <div class="chart-container">
                                <canvas id="subjectPerformanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h3 class="dashboard-card-title">Recent Activity</h3>
                            <div class="dashboard-card-actions">
                                <button class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Refresh</span>
                                </button>
                            </div>
                        </div>
                        <div class="dashboard-card-body">
                            <ul class="recent-activity">
                                <li class="activity-item">
                                    <div class="activity-icon success">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Final Exam Results Published</div>
                                        <div class="activity-time">Today, 10:30 AM</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-icon info">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">New Student Registered</div>
                                        <div class="activity-time">Yesterday, 2:15 PM</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-icon warning">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Marks Updated for Math Exam</div>
                                        <div class="activity-time">Yesterday, 11:45 AM</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-icon danger">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Low Performance Alert: Physics</div>
                                        <div class="activity-time">2 days ago</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card" style="animation-delay: 0.4s;">
                    <div class="dashboard-card-header">
                        <h3 class="dashboard-card-title">Recent Results</h3>
                        <div class="dashboard-card-actions">
                            <button class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                <i class="fas fa-eye"></i>
                                <span>View All</span>
                            </button>
                        </div>
                    </div>
                    <div class="dashboard-card-body">
                        <table class="recent-results">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Exam</th>
                                    <th>Score</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Charles Victor Simtanda</td>
                                    <td>Form 1</td>
                                    <td>Mid-Term</td>
                                    <td>85%</td>
                                    <td>A</td>
                                    <td><span class="badge badge-success">Passed</span></td>
                                </tr>
                                <tr>
                                    <td>Sarah Johnson</td>
                                    <td>Form 2</td>
                                    <td>Final Exam</td>
                                    <td>92%</td>
                                    <td>A+</td>
                                    <td><span class="badge badge-success">Passed</span></td>
                                </tr>
                                <tr>
                                    <td>Michael Brown</td>
                                    <td>Form 3</td>
                                    <td>Mid-Term</td>
                                    <td>68%</td>
                                    <td>C+</td>
                                    <td><span class="badge badge-warning">Average</span></td>
                                </tr>
                                <tr>
                                    <td>Emily Davis</td>
                                    <td>Form 2</td>
                                    <td>Final Exam</td>
                                    <td>45%</td>
                                    <td>F</td>
                                    <td><span class="badge badge-danger">Failed</span></td>
                                </tr>
                                <tr>
                                    <td>James Wilson</td>
                                    <td>Form 1</td>
                                    <td>Mid-Term</td>
                                    <td>78%</td>
                                    <td>B+</td>
                                    <td><span class="badge badge-success">Passed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Submenu Toggle
            const menuItems = document.querySelectorAll('.sidebar-menu > li');
            
            menuItems.forEach(item => {
                const link = item.querySelector('.expandable');
                const submenu = item.querySelector('.submenu');
                
                if (link && submenu) {
                    if (!link.querySelector('.toggle-icon')) {
                        const icon = document.createElement('i');
                        icon.className = 'fas fa-chevron-up toggle-icon';
                        link.appendChild(icon);
                    }
                    
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const toggleIcon = this.querySelector('.toggle-icon');
                        
                        document.querySelectorAll('.submenu').forEach(menu => {
                            if (menu !== submenu) {
                                menu.classList.remove('show');
                                const otherIcon = menu.previousElementSibling.querySelector('.toggle-icon');
                                if (otherIcon) {
                                    otherIcon.style.transform = 'rotate(0deg)';
                                }
                            }
                        });
                        
                        submenu.classList.toggle('show');
                        toggleIcon.style.transform = submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                    });
                }
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            });

            // Initialize Charts
            const ctx = document.getElementById('subjectPerformanceChart').getContext('2d');
            const subjectPerformanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Mathematics', 'English', 'Science', 'History', 'Geography', 'Physics'],
                    datasets: [{
                        label: 'Average Score (%)',
                        data: [78, 82, 65, 75, 68, 62],
                        backgroundColor: [
                            'rgba(74, 229, 74, 0.6)',
                            'rgba(74, 229, 74, 0.6)',
                            'rgba(74, 229, 74, 0.6)',
                            'rgba(74, 229, 74, 0.6)',
                            'rgba(74, 229, 74, 0.6)',
                            'rgba(74, 229, 74, 0.6)'
                        ],
                        borderColor: [
                            'rgba(58, 208, 58, 1)',
                            'rgba(58, 208, 58, 1)',
                            'rgba(58, 208, 58, 1)',
                            'rgba(58, 208, 58, 1)',
                            'rgba(58, 208, 58, 1)',
                            'rgba(58, 208, 58, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>