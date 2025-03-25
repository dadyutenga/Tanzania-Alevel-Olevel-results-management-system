<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5f6fa;
            --text-color: #2c3e50;
            --border-color: #dcdde1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-left: 10px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>School Portal</h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('student') ?>" class="nav-link">
                        <i class="fas fa-users"></i>
                        Students
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Welcome to School Portal</h1>
            </div>
        </div>
    </div>
</body>
</html>