<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Analytics Overview</title>
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

        .layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: -0.025em;
        }

        .page-description {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards 0.1s;
        }

        .insight-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .insight-card {
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

        .insight-card::before {
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

        .insight-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .insight-card-icon {
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

        .insight-card-title {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 500;
        }

        .insight-card-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .pill.positive {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .chart-panel {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .chart-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards 0.2s;
        }

        .chart-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .chart-card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .chart-card-body {
            padding: 1.5rem;
        }

        .table-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards 0.3s;
        }

        .table-card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .table-wrapper {
            padding: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .tag {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .muted {
            color: var(--text-secondary);
        }

        footer {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-top: 2rem;
            padding: 1rem 0;
        }

        .chart-card-body canvas {
            max-height: 300px;
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
            .chart-panel {
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

            .sidebar-toggle {
                display: block;
            }

            .insight-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
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
            <div class="layout">
        <header class="page-header">
            <h1 class="page-title">Data Analytics Dashboard</h1>
            <span class="pill positive"><i class="fas fa-sync-alt"></i> Live Insights</span>
        </header>
        
        <p class="page-description">
            Stay informed with a real-time overview of student performance, enrollment trends, and exam outcomes. Data refreshes every page load and aggregates information by students, sessions, classes, and subjects.
        </p>

        <section class="insight-grid" id="headlineMetrics">
            <!-- Filled dynamically -->
        </section>

        <section class="chart-panel">
            <article class="chart-card">
                <div class="chart-card-header">
                    <h3>Student Enrollment Trend</h3>
                </div>
                <div class="chart-card-body">
                    <canvas id="studentsLine"></canvas>
                </div>
            </article>
            <article class="chart-card">
                <div class="chart-card-header">
                    <h3>Exam Division Distribution</h3>
                </div>
                <div class="chart-card-body">
                    <canvas id="divisionPie"></canvas>
                </div>
            </article>
        </section>

        <section class="chart-panel">
            <article class="chart-card">
                <div class="chart-card-header">
                    <h3>Top Performing Subjects</h3>
                </div>
                <div class="chart-card-body">
                    <canvas id="subjectBar"></canvas>
                </div>
            </article>
            <article class="chart-card">
                <div class="chart-card-header">
                    <h3>Class Population Snapshot</h3>
                </div>
                <div class="chart-card-body">
                    <canvas id="classHorizontal"></canvas>
                </div>
            </article>
        </section>

        <section class="table-card">
            <div class="table-card-header">
                <h3 class="table-card-title">Top Students</h3>
                <span class="pill positive"><i class="fas fa-trophy"></i> Latest Updates</span>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>Division</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody id="topStudentsTable">
                        <!-- Filled dynamically -->
                    </tbody>
                </table>
            </div>
        </section>

        <footer>
            &copy; <?= date('Y'); ?> Tanzania A-Level &amp; O-Level Results Management · Analytics Snapshot
        </footer>
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
        });

        const chartPalette = {
            accent: '#4AE54A',
            accentStrong: '#3AD03A',
            success: '#10b981',
            warning: '#f59e0b',
            danger: '#ef4444',
            purple: '#a855f7',
            teal: '#14b8a6',
            info: '#3b82f6'
        };

        async function fetchAnalytics() {
            try {
                const response = await fetch('<?= site_url('analytics/overview'); ?>');
                if (!response.ok) {
                    throw new Error('Failed to load analytics data');
                }

                return await response.json();
            } catch (error) {
                console.error('Analytics error:', error);
                return null;
            }
        }

        function renderHeadlineMetrics(analytics) {
            const container = document.getElementById('headlineMetrics');
            container.innerHTML = '';

            const metrics = [
                {
                    title: 'Total Students',
                    value: analytics.students.total,
                    trendText: `${analytics.students.active} active`,
                    icon: 'fa-users',
                    tone: 'positive'
                },
                {
                    title: 'Active Classes',
                    value: analytics.classes.active,
                    trendText: `${analytics.classes.student_distribution.reduce((acc, row) => acc + row.students, 0)} students`,
                    icon: 'fa-school'
                },
                {
                    title: 'Active Exams',
                    value: analytics.exams.active,
                    trendText: `${analytics.exams.results.count} results published`,
                    icon: 'fa-file-lines'
                },
                {
                    title: 'Subjects Tracked',
                    value: analytics.subjects.total_subjects,
                    trendText: `${analytics.subjects.top_subjects.length} highlighted`,
                    icon: 'fa-book-open'
                }
            ];

            metrics.forEach((metric) => {
                const card = document.createElement('article');
                card.className = 'insight-card';
                card.style.animationDelay = `${metrics.indexOf(metric) * 0.1}s`;
                card.innerHTML = `
                    <div class="insight-card-header">
                        <div class="insight-card-icon">
                            <i class="fas ${metric.icon}"></i>
                        </div>
                        <h3 class="insight-card-title">${metric.title}</h3>
                    </div>
                    <div class="insight-card-value">${metric.value ?? 0}</div>
                    <div class="pill ${metric.tone === 'positive' ? 'positive' : ''}">
                        ${metric.trendText || ''}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function renderTopStudents(students) {
            const tbody = document.getElementById('topStudentsTable');
            tbody.innerHTML = '';

            if (!students.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="muted">No student results available yet.</td></tr>';
                return;
            }

            students.forEach((student) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <strong>${student.student_name || 'Unknown'}</strong><br>
                        <span class="muted">ID: ${student.student_id || '—'}</span>
                    </td>
                    <td class="muted">${student.exam_name || '—'}</td>
                    <td><span class="tag">${student.division || '—'}</span></td>
                    <td>${student.total_points ?? '—'}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function renderCharts(analytics) {
            const enrollmentTrend = Array.isArray(analytics.students.recent_registrations)
                ? analytics.students.recent_registrations.slice().reverse()
                : [];
            const labels = enrollmentTrend.map((item) => {
                if (!item.created_at) {
                    return 'N/A';
                }
                const timestamp = new Date(item.created_at);
                return Number.isNaN(timestamp.valueOf()) ? 'N/A' : timestamp.toLocaleDateString();
            });
            const data = enrollmentTrend.map((_, index) => index + 1);

            new Chart(document.getElementById('studentsLine'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'New Admissions',
                        data,
                        borderColor: chartPalette.accent,
                        backgroundColor: 'rgba(74, 229, 74, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: chartPalette.accentStrong
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(226, 232, 240, 0.5)' }, ticks: { color: '#64748b' } },
                        x: { grid: { display: false }, ticks: { color: '#64748b' } }
                    }
                }
            });

            const divisionData = analytics.exams.results.division_breakdown || {};
            const divisionLabels = Object.keys(divisionData);
            const divisionValues = Object.values(divisionData);

            new Chart(document.getElementById('divisionPie'), {
                type: 'doughnut',
                data: {
                    labels: divisionLabels,
                    datasets: [{
                        data: divisionValues,
                        backgroundColor: [chartPalette.success, chartPalette.accent, chartPalette.warning, chartPalette.danger, chartPalette.purple]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#1e293b', padding: 15 }
                        }
                    }
                }
            });

            const topSubjects = Array.isArray(analytics.subjects.top_subjects) ? analytics.subjects.top_subjects : [];
            new Chart(document.getElementById('subjectBar'), {
                type: 'bar',
                data: {
                    labels: topSubjects.map((item) => item.subject),
                    datasets: [{
                        label: 'Average Mark',
                        data: topSubjects.map((item) => item.average_mark ?? 0),
                        backgroundColor: 'rgba(168, 85, 247, 0.5)',
                        borderColor: '#a855f7',
                        borderWidth: 1,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(226, 232, 240, 0.5)' }, ticks: { color: '#64748b' } },
                        x: { grid: { display: false }, ticks: { color: '#64748b' } }
                    }
                }
            });

            const classDistribution = Array.isArray(analytics.classes.student_distribution) ? analytics.classes.student_distribution : [];
            new Chart(document.getElementById('classHorizontal'), {
                type: 'bar',
                data: {
                    labels: classDistribution.map((item) => item.class_name || 'Unassigned'),
                    datasets: [{
                        label: 'Students',
                        data: classDistribution.map((item) => item.students ?? 0),
                        backgroundColor: 'rgba(20, 184, 166, 0.6)',
                        borderColor: '#14b8a6',
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: 'rgba(226, 232, 240, 0.5)' }, ticks: { color: '#64748b' } },
                        y: { grid: { display: false }, ticks: { color: '#64748b' } }
                    }
                }
            });
        }

        (async function init() {
            const analytics = await fetchAnalytics();
            if (!analytics) {
                document.getElementById('headlineMetrics').innerHTML = '<div class="insight-card">Unable to load analytics data.</div>';
                return;
            }

            renderHeadlineMetrics(analytics);
            renderTopStudents(analytics.top_students || []);
            renderCharts(analytics);
        })();
    </script>
</body>
</html>
