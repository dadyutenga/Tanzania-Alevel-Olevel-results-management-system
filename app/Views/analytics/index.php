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
            --bg-color: #0f172a;
            --card: #1e293b;
            --card-muted: #111827;
            --text: #e2e8f0;
            --text-muted: #94a3b8;
            --accent: #38bdf8;
            --accent-strong: #0ea5e9;
            --success: #22c55e;
            --warning: #facc15;
            --danger: #f97316;
            --border: rgba(148, 163, 184, 0.15);
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.4);
            --radius-lg: 20px;
            --radius-sm: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at top, rgba(14, 165, 233, 0.15), transparent 55%),
                        radial-gradient(circle at 20% 80%, rgba(34, 197, 94, 0.1), transparent 55%),
                        var(--bg-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .page-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .page-title {
            font-size: 2.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .page-description {
            color: var(--text-muted);
            max-width: 720px;
            margin: 0 auto;
        }

        .insight-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .insight-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.9));
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .insight-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(56, 189, 248, 0.1), transparent 55%);
            pointer-events: none;
        }

        .insight-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .insight-card-title {
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--text-muted);
        }

        .insight-card-value {
            font-size: 2.25rem;
            font-weight: 700;
            letter-spacing: -0.035em;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }

        .pill.positive {
            border-color: rgba(34, 197, 94, 0.5);
            color: var(--success);
        }

        .chart-panel {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.9));
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            position: relative;
        }

        .chart-card h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text);
        }

        .table-card {
            background: rgba(15, 23, 42, 0.92);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
        }

        th {
            text-transform: uppercase;
            font-size: 0.72rem;
            color: var(--text-muted);
            letter-spacing: 0.18em;
        }

        tr + tr td {
            border-top: 1px solid rgba(148, 163, 184, 0.1);
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent);
            font-size: 0.75rem;
        }

        .muted {
            color: var(--text-muted);
        }

        footer {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 2rem;
        }

        @media (max-width: 640px) {
            body { padding: 1.5rem 0; }
            .page-title { font-size: 1.8rem; }
            .insight-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <header class="page-header">
            <div>
                <p class="pill">Live Insights</p>
                <h1 class="page-title">Data Analytics Dashboard</h1>
            </div>
            <p class="page-description">
                Stay informed with a real-time overview of student performance, enrollment trends, and exam outcomes. Data refreshes every page load and aggregates information by students, sessions, classes, and subjects.
            </p>
        </header>

        <section class="insight-grid" id="headlineMetrics">
            <!-- Filled dynamically -->
        </section>

        <section class="chart-panel">
            <article class="chart-card">
                <h3>Student Enrollment Trend</h3>
                <canvas id="studentsLine"></canvas>
            </article>
            <article class="chart-card">
                <h3>Exam Division Distribution</h3>
                <canvas id="divisionPie"></canvas>
            </article>
        </section>

        <section class="chart-panel">
            <article class="chart-card">
                <h3>Top Performing Subjects</h3>
                <canvas id="subjectBar"></canvas>
            </article>
            <article class="chart-card">
                <h3>Class Population Snapshot</h3>
                <canvas id="classHorizontal"></canvas>
            </article>
        </section>

        <section class="table-card">
            <header class="insight-card-header" style="margin-bottom: 1rem;">
                <h3 class="insight-card-title" style="letter-spacing: 0.18em;">Top Students</h3>
                <span class="pill"><i class="fas fa-trophy"></i>Latest Updates</span>
            </header>
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

    <script>
        const chartPalette = {
            accent: '#38bdf8',
            accentStrong: '#0ea5e9',
            success: '#22c55e',
            warning: '#facc15',
            danger: '#f97316',
            purple: '#a855f7',
            teal: '#14b8a6'
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
                card.innerHTML = `
                    <div class="insight-card-header">
                        <span class="insight-card-title">${metric.title}</span>
                        <span class="pill ${metric.tone === 'positive' ? 'positive' : ''}">
                            <i class="fas ${metric.icon}"></i>
                        </span>
                    </div>
                    <div class="insight-card-value">${metric.value ?? 0}</div>
                    <p class="muted">${metric.trendText || ''}</p>
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
                        backgroundColor: 'rgba(56, 189, 248, 0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
                        x: { grid: { display: false } }
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
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#e2e8f0' }
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
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.08)' } },
                        x: { grid: { display: false }, ticks: { color: '#e2e8f0' } }
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.08)' }, ticks: { color: '#e2e8f0' } },
                        y: { grid: { display: false }, ticks: { color: '#e2e8f0' } }
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
