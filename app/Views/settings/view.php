

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --primary: #4AE54A;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-color); color: var(--text-primary); min-height: 100vh; }
        
        .dashboard { display: flex; min-height: 100vh; }
        
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .logo i { color: var(--primary); font-size: 1.75rem; }
        
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
        
        .logout-section {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }
        
        .logout-link {
            color: #ef4444 !important;
        }
        
        .logout-link:hover {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem 1rem;
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
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1rem 0.5rem; }
            .sidebar-toggle { display: block; }
        }
        
        .container { max-width: 800px; margin: 0 auto; padding: 0 1rem; }
        .card { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); padding: 2rem; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .info-row { display: flex; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border); }
        .info-label { font-weight: 600; width: 200px; color: var(--text-secondary); }
        .info-value { flex: 1; }
        .logo-img { max-width: 200px; border-radius: 8px; box-shadow: var(--shadow); }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 50px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: var(--primary); color: black; }
        .badge { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; }
        .badge-success { background: rgba(34, 197, 94, 0.2); color: #16a34a; }
        .badge-danger { background: rgba(239, 68, 68, 0.2); color: #dc2626; }
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
            <?= $this->include("shared/sidebar_menu") ?>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="card">
                    <div class="header">
                        <h1><?= esc($title) ?></h1>
                        <a href="<?= base_url('settings/edit') ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Settings
                        </a>
                    </div>

            <div class="info-row">
                <div class="info-label">School Name</div>
                <div class="info-value"><?= esc($settings['school_name']) ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Total Classes</div>
                <div class="info-value"><?= esc($settings['total_classes']) ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">School Year</div>
                <div class="info-value"><?= esc($settings['school_year']) ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">School Address</div>
                <div class="info-value"><?= esc($settings['school_address'] ?? 'N/A') ?></div>
            </div>

            <?php if (!empty($settings['school_logo'])): ?>
            <div class="info-row">
                <div class="info-label">School Logo</div>
                <div class="info-value">
                    <img src="data:image/jpeg;base64,<?= esc($settings['school_logo']) ?>" alt="School Logo" class="logo-img">
                </div>
            </div>
            <?php endif; ?>

            <div class="info-row">
                <div class="info-label">Contact Email</div>
                <div class="info-value"><?= esc($settings['contact_email'] ?? 'N/A') ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Contact Phone</div>
                <div class="info-value"><?= esc($settings['contact_phone'] ?? 'N/A') ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <?php if ($settings['is_active'] === 'yes'): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Inactive</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Created At</div>
                <div class="info-value"><?= esc($settings['created_at']) ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Last Updated</div>
                <div class="info-value"><?= esc($settings['updated_at']) ?></div>
            </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
            }
            
            // Submenu Toggle
            const menuItems = document.querySelectorAll('.sidebar-menu > li');
            
            menuItems.forEach(item => {
                const link = item.querySelector('.expandable');
                const submenu = item.querySelector('.submenu');
                const toggleIcon = item.querySelector('.toggle-icon');
                
                if (link && submenu) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        submenu.classList.toggle('show');
                        if (toggleIcon) {
                            toggleIcon.style.transform = submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                        }
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
    </script>
</body>
</html>