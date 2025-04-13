<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Dark Theme Color Scheme */
        :root {
            --bg-gradient-from: #1a1a1a;
            --bg-gradient-to: #2d2d2d;
            --primary: #222222;
            --primary-dark: #1a1a1a;
            --secondary: #333333;
            --accent: #4AE54A;
            --accent-hover: #3AD03A;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --border: #444444;
            --card-bg: rgba(40, 40, 40, 0.8);
            --success: #31c48d;
            --warning: #f59e0b;
            --danger: #e53e3e;
            --shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
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
            background: linear-gradient(135deg, var(--bg-gradient-from), var(--bg-gradient-to));
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(74, 229, 74, 0.03) 0%, rgba(0, 0, 0, 0) 70%);
            z-index: -1;
        }

        .container {
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .header {
            margin-bottom: 3.5rem;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #ffffff, #a0a0a0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .card {
            background: var(--card-bg);
            padding: 3rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        .btn {
            padding: 0.85rem 2rem;
            border-radius: var(--button-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            text-decoration: none;
            margin: 0.75rem;
            min-width: 200px;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .btn i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: translateX(3px);
        }

        .btn-primary {
            background-color: var(--accent);
            color: #000000;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .logo {
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .logo i {
            color: var(--accent);
            font-size: 1.75rem;
        }

        .decorative-element {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(74, 229, 74, 0.05) 0%, rgba(0, 0, 0, 0) 70%);
            z-index: -1;
        }

        .decorative-element-1 {
            top: -150px;
            right: -150px;
        }

        .decorative-element-2 {
            bottom: -150px;
            left: -150px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }

            .header h1 {
                font-size: 2.25rem;
            }

            .header p {
                font-size: 1rem;
            }

            .card {
                padding: 2rem 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="decorative-element decorative-element-1"></div>
    <div class="decorative-element decorative-element-2"></div>
    
    <div class="container">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>ExamResults</span>
        </div>
        
        <div class="header">
            <h1>The Smarter Way to Manage Exam Results</h1>
            <p>Access, analyze, and manage student exam results with ease. Our platform streamlines the entire process for administrators, teachers, and students.</p>
        </div>
        
        <div class="card">
            <div class="action-buttons">
                <a href="<?= base_url('login') ?>" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="<?= base_url('register') ?>" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Register
                </a>
                <a href="<?= base_url('public/results') ?>" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search Results
                </a>
            </div>
        </div>
    </div>
</body>
</html>