<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Students</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.925rem;
            background-color: var(--bg-color);
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
            padding: 2rem 1rem;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .filters {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            position: relative;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filters::before {
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

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.925rem;
            transition: all 0.3s ease;
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 229, 74, 0.2);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--button-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            transition: all 0.3s ease;
            background-color: var(--primary);
            color: black;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .students-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            padding: 1.5rem;
            margin-bottom: 2rem;
            overflow-x: auto;
        }

        .students-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .students-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .students-header h2 i {
            color: var(--primary);
        }

        .students-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            min-width: 800px;
        }

        .students-table thead th {
            background-color: var(--primary);
            color: #000000;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .students-table tbody tr {
            background-color: var(--card-bg);
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .students-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: rgba(74, 229, 74, 0.05);
        }

        .students-table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .students-table tr:last-child td {
            border-bottom: none;
        }

        .students-table th:nth-child(1),
        .students-table td:nth-child(1) { width: 15%; } /* Admission No */
        .students-table th:nth-child(2),
        .students-table td:nth-child(2) { width: 25%; } /* Name */
        .students-table th:nth-child(3),
        .students-table td:nth-child(3) { width: 15%; } /* Class */
        .students-table th:nth-child(4),
        .students-table td:nth-child(4) { width: 15%; } /* Section */
        .students-table th:nth-child(5),
        .students-table td:nth-child(5) { width: 15%; } /* Status */
        .students-table th:nth-child(6),
        .students-table td:nth-child(6) { width: 15%; } /* Actions */

        .table-status {
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .table-status.active {
            background-color: rgba(74, 229, 74, 0.1);
            color: var(--primary-dark);
        }

        .table-status.inactive {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .action-btn {
            padding: 0.5rem;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            margin-right: 0.5rem;
        }

        .action-btn.view {
            background-color: var(--primary);
            color: black;
            box-shadow: 0 0 20px rgba(74, 229, 74, 0.3);
        }

        .action-btn.view:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .action-btn.edit {
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .action-btn.edit:hover {
            background-color: var(--primary);
            color: black;
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(74, 229, 74, 0.4);
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            justify-content: center;
        }

        .page-btn {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            color: var(--text-primary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: var(--card-bg);
            border: 1px solid var(--border);
        }

        .page-btn:hover:not(.disabled):not(.dots) {
            background-color: var(--primary);
            color: black;
        }

        .page-btn.active {
            background-color: var(--primary);
            color: black;
            font-weight: 600;
        }

        .page-btn.disabled,
        .page-btn.dots {
            color: var(--text-secondary);
            cursor: not-allowed;
            background-color: transparent;
            border: none;
        }

        .no-results {
            padding: 2.5rem;
            text-align: center;
        }

        .no-results i {
            font-size: 2rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-results h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .no-results p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
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

            .filters {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .students-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .pagination {
                justify-content: center;
            }

            .sidebar-toggle {
                display: block;
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
            <?= $this->include('shared/sidebar_menu') ?>
        </div>
        
        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>Student Management</h1>
                    <p>Search, filter, and manage student records</p>
                </div>
                
                <div class="filters">
                    <div class="filter-group">
                        <label for="sessionFilter">Academic Year</label>
                        <select id="sessionFilter">
                            <option value="">Select Year</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="classFilter">Class</label>
                        <select id="classFilter">
                            <option value="">Select Class</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sectionFilter">Section</label>
                        <select id="sectionFilter">
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="searchInput">Search</label>
                        <input type="text" id="searchInput" placeholder="Search by name...">
                    </div>
                    
                    <button id="searchBtn" class="btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                
                <div class="students-container">
                    <div class="students-header">
                        <h2>
                            <i class="fas fa-list"></i> Student List
                        </h2>
                        <button id="importBtn" class="btn">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                    </div>
                    
                    <table class="students-table" id="studentsTable">
                        <thead>
                            <tr>
                                <th>Admission No</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            <!-- Dynamic content will be loaded here -->
                        </tbody>
                    </table>
                    
                    <div class="no-results" id="noResults" style="display: none;">
                        <i class="fas fa-search"></i>
                        <h3>No Students Found</h3>
                        <p>Try adjusting your search or filter criteria</p>
                    </div>
                    
                    <div class="pagination" id="pagination">
                        <!-- Pagination will be dynamically generated -->
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
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
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
            
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                    sidebar.classList.remove('show');
                }
            });
        });

        const baseUrl = window.location.origin;
        
        const studentsTable = document.getElementById('studentsTable');
        const studentTableBody = document.getElementById('studentTableBody');
        const searchInput = document.getElementById('searchInput');
        const classFilter = document.getElementById('classFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const searchBtn = document.getElementById('searchBtn');
        const noResultsElement = document.getElementById('noResults');
        const paginationContainer = document.getElementById('pagination');
        const sessionFilter = document.getElementById('sessionFilter');

        document.addEventListener('DOMContentLoaded', async () => {
            await loadSessions();
            classFilter.disabled = true;
            sectionFilter.disabled = true;
            showNoResults('Please select academic year to view students');
        });

        sessionFilter.addEventListener('change', async () => {
            const sessionId = sessionFilter.value;
            
            classFilter.innerHTML = '<option value="">Select Class</option>';
            sectionFilter.innerHTML = '<option value="">Select Section</option>';
            classFilter.disabled = true;
            sectionFilter.disabled = true;
            studentTableBody.innerHTML = '';
            paginationContainer.innerHTML = '';
            
            if (!sessionId) {
                showNoResults('Please select an academic year');
                return;
            }

            const selectedYear = sessionFilter.options[sessionFilter.selectedIndex].text;
            showNoResults(`Select class and section to view students for ${selectedYear}`);
            
            try {
                await loadClasses();
                classFilter.disabled = false;
            } catch (error) {
                showError('Failed to load classes for the selected academic year');
            }
        });

        classFilter.addEventListener('change', async (e) => {
            const classId = e.target.value;
            sectionFilter.innerHTML = '<option value="">Select Section</option>';
            sectionFilter.disabled = true;
            studentTableBody.innerHTML = '';
            
            if (classId) {
                await loadSections(classId);
                sectionFilter.disabled = false;
            } else {
                showNoResults('Please select a class');
            }
        });

        sectionFilter.addEventListener('change', async () => {
            if (sectionFilter.value) {
                await fetchStudents(1);
            } else {
                studentTableBody.innerHTML = '';
                showNoResults('Please select a section');
            }
        });

        searchBtn.addEventListener('click', async () => {
            await fetchStudents(1);
        });

        searchInput.addEventListener('keypress', async (e) => {
            if (e.key === 'Enter') {
                await fetchStudents(1);
            }
        });

        async function loadSessions() {
            try {
                console.log('Fetching sessions...');
                const response = await fetch(`${baseUrl}/student/getSessions`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                console.log('Response status:', response.status);
                const responseText = await response.text();
                console.log('Raw response:', responseText);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    throw new Error('Invalid JSON response');
                }

                console.log('Parsed sessions data:', data);

                if (data.status === 'success') {
                    sessionFilter.innerHTML = '<option value="">Select Year</option>';
                    if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                        data.data.forEach(session => {
                            sessionFilter.innerHTML += `
                                <option value="${session.id}">${session.session}</option>
                            `;
                        });
                        console.log('Sessions loaded successfully');
                    } else {
                        showError('No academic years available');
                    }
                } else {
                    throw new Error(data.message || 'Failed to load academic years');
                }
            } catch (error) {
                console.error('Error loading academic years:', error);
                showError('Failed to load academic years. Please try again.');
            }
        }

        async function loadClasses() {
            try {
                const response = await fetch(`${baseUrl}/student/getClasses`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                console.log('Classes response:', data);
                
                if (data.status === 'success') {
                    classFilter.innerHTML = '<option value="">Select Class</option>';
                    if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                        data.data.forEach(classItem => {
                            classFilter.innerHTML += `
                                <option value="${classItem.id}">${classItem.class}</option>
                            `;
                        });
                    } else {
                        showError('No classes available');
                    }
                } else {
                    throw new Error(data.message || 'Failed to load classes');
                }
            } catch (error) {
                console.error('Error loading classes:', error);
                showError('Failed to load classes. Please try again.');
            }
        }

        async function loadSections(classId) {
            try {
                if (!classId) {
                    sectionFilter.innerHTML = '<option value="">Select Section</option>';
                    return;
                }

                const response = await fetch(`${baseUrl}/student/getSections/${classId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                console.log('Sections response:', data);
                
                sectionFilter.innerHTML = '<option value="">Select Section</option>';
                
                if (data.status === 'success' && data.data && Array.isArray(data.data)) {
                    data.data.forEach(section => {
                        sectionFilter.innerHTML += `
                            <option value="${section.id}">${section.section}</option>
                        `;
                    });
                } else {
                    showError('No sections available for this class');
                }
            } catch (error) {
                console.error('Error loading sections:', error);
                showError('Failed to load sections. Please try again.');
            }
        }

        async function fetchStudents(page = 1) {
            try {
                const search = searchInput.value.trim();
                const sessionValue = sessionFilter.value;
                const classValue = classFilter.value;
                const sectionValue = sectionFilter.value;
                const limit = 10;

                if (!sessionValue || !classValue || !sectionValue) {
                    showNoResults('Please select academic year, class and section');
                    return;
                }

                studentTableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';

                const queryParams = new URLSearchParams({
                    page,
                    limit,
                    search,
                    session: sessionValue,
                    class: classValue,
                    section: sectionValue
                });

                const response = await fetch(`${baseUrl}/student/fetchStudents?${queryParams}`);
                const data = await response.json();
                
                console.log('Students response:', data);

                if (data.status === 'error') {
                    showError(data.message);
                    return;
                }

                if (!data.data.students || data.data.students.length === 0) {
                    showNoResults(`No students found in ${sessionFilter.options[sessionFilter.selectedIndex].text} for selected class and section`);
                    return;
                }

                renderStudents(data.data.students);
                setupPagination(data.data.pagination);

            } catch (error) {
                console.error('Error fetching students:', error);
                showError('Failed to fetch students. Please try again.');
            }
        }

        function renderStudents(students) {
            studentTableBody.innerHTML = '';
            
            if (!Array.isArray(students) || students.length === 0) {
                showNoResults('No students found for the selected criteria');
                return;
            }

            const selectedYear = sessionFilter.options[sessionFilter.selectedIndex].text;
            const selectedClass = classFilter.options[classFilter.selectedIndex].text;
            const selectedSection = sectionFilter.options[sectionFilter.selectedIndex].text;

            students.forEach(student => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.middlename || '-'}</td>
                    <td>${student.full_name || `${student.firstname} ${student.lastname}`}</td>
                    <td>${selectedClass}</td>
                    <td>${selectedSection}</td>
                    <td><span class="table-status ${student.is_active === 'yes' ? 'active' : 'inactive'}">
                        ${student.is_active === 'yes' ? 'Active' : 'Inactive'}
                    </span></td>
                    <td>
                        <button class="action-btn view" onclick="viewStudent(${student.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn edit" onclick="editStudent(${student.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                `;
                studentTableBody.appendChild(row);
            });

            noResultsElement.style.display = 'none';
            studentsTable.style.display = 'table';
            paginationContainer.style.display = 'flex';
        }

        function setupPagination(pagination) {
            const { current_page, total_pages } = pagination;
            
            let paginationHTML = '';
            
            paginationHTML += `
                <button class="page-btn ${current_page === 1 ? 'disabled' : ''}" 
                        onclick="fetchStudents(${current_page - 1})" 
                        ${current_page === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            for (let i = 1; i <= total_pages; i++) {
                if (
                    i === 1 || 
                    i === total_pages || 
                    (i >= current_page - 2 && i <= current_page + 2)
                ) {
                    paginationHTML += `
                        <button class="page-btn ${i === current_page ? 'active' : ''}" 
                                onclick="fetchStudents(${i})">
                            ${i}
                        </button>
                    `;
                } else if (
                    i === current_page - 3 || 
                    i === current_page + 3
                ) {
                    paginationHTML += `<span class="page-btn disabled dots">...</span>`;
                }
            }

            paginationHTML += `
                <button class="page-btn ${current_page === total_pages ? 'disabled' : ''}" 
                        onclick="fetchStudents(${current_page + 1})"
                        ${current_page === total_pages ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            paginationContainer.innerHTML = paginationHTML;
        }

        function showNoResults(message) {
            studentTableBody.innerHTML = '';
            noResultsElement.innerHTML = `
                <i class="fas fa-search"></i>
                <h3>${message}</h3>
                <p>Make sure you have selected the correct academic year, class, and section</p>
            `;
            noResultsElement.style.display = 'block';
            studentsTable.style.display = 'none';
            paginationContainer.style.display = 'none';
        }

        function showError(message) {
            studentTableBody.innerHTML = '';
            noResultsElement.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Error</h3>
                <p>${message}</p>
            `;
            noResultsElement.style.display = 'block';
            studentsTable.style.display = 'none';
            paginationContainer.style.display = 'none';
        }

        async function viewStudent(id) {
            try {
                const response = await fetch(`${baseUrl}/student/getStudent/${id}`);
                const data = await response.json();
                if (data.status === 'success') {
                    alert(`Viewing student: ${data.data.firstname} ${data.data.lastname}`);
                }
            } catch (error) {
                console.error('Error fetching student details:', error);
                alert('Failed to load student details');
            }
        }

        async function editStudent(id) {
            try {
                const response = await fetch(`${baseUrl}/student/getStudent/${id}`);
                const data = await response.json();
                if (data.status === 'success') {
                    alert(`Editing student: ${data.data.firstname} ${data.data.lastname}`);
                }
            } catch (error) {
                console.error('Error fetching student details:', error);
                alert('Failed to load student details');
            }
        }
    </script>
</body>
</html>