<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Management - Students</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Color Scheme */
        :root {
            --primary: #f8f9fa;
            --primary-dark: #f1f3f5;
            --secondary: #e9ecef;
            --accent: #1a1f36;
            --accent-light: #2d3748;
            --text-primary: #1a1f36;
            --text-secondary: #4a5568;
            --border: #e2e8f0;
            --success: #31c48d;
            --warning: #f59e0b;
            --danger: #e53e3e;
            --shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            --radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.925rem;
            background-color: var(--primary-dark);
            color: var(--text-primary);
            line-height: 1.5;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background-color: var(--accent);
            color: var(--primary);
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
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header i {
            font-size: 2rem;
            margin-right: 0.75rem;
            opacity: 0.9;
        }

        .sidebar-header h2 {
            font-size: 1.25rem;
            letter-spacing: -0.025em;
            font-weight: 600;
            opacity: 0.9;
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 2rem;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.675rem 1rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.9);
        }

        .sidebar-menu i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        /* Main Content */
        .main-content {
            grid-column: 2;
            padding: 2rem;
            background-color: var(--primary-dark);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Filters */
        .filters {
            background: var(--primary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.375rem;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .filter-group select, .filter-group input {
            width: 100%;
            padding: 0.625rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background-color: var(--primary);
            color: var(--text-primary);
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .filter-group select:focus, .filter-group input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(26, 31, 54, 0.1);
        }

        .btn {
            background-color: var(--accent);
            color: var(--primary);
            border: none;
            border-radius: var(--radius);
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            background-color: var(--accent-light);
        }

        /* Students Container */
        .students-container {
            margin-top: 2rem;
            background: var(--primary);
            border-radius: var(--radius);
            padding: 1.25rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .students-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .students-header h2 {
            font-size: 1.125rem;
            letter-spacing: -0.025em;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Student Table View */
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .students-table th {
            background: var(--accent);
            color: var(--primary);
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .students-table th:first-child {
            border-top-left-radius: var(--radius);
        }

        .students-table th:last-child {
            border-top-right-radius: var(--radius);
        }

        .students-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .students-table tr:last-child td {
            border-bottom: none;
        }

        .students-table tr:hover {
            background-color: var(--secondary);
        }

        .table-status {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .table-status.active {
            background-color: rgba(49, 196, 141, 0.1);
            color: var(--success);
            border: 1px solid rgba(49, 196, 141, 0.2);
        }

        .table-status.inactive {
            background-color: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            border: 1px solid rgba(229, 62, 62, 0.2);
        }

        .action-btn {
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius);
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            margin-right: 0.5rem;
        }

        .action-btn.view {
            background: var(--accent);
            color: var(--primary);
        }

        .action-btn.edit {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .action-btn.edit:hover {
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            gap: 0.5rem;
        }

        .page-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
            background-color: var(--primary);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .page-btn:hover {
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .page-btn.active {
            background-color: var(--accent);
            color: var(--primary);
            border-color: var(--accent);
        }

        .page-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* No Results */
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
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .no-results p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        /* Responsive */
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

        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .btn {
                width: 100%;
            }
            
            .students-table {
                display: block;
                overflow-x: auto;
            }
            
            .students-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Result Management</h2>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="<?= base_url('student') ?>" class="active"><i class="fas fa-users"></i> Students</a></li>
                <li><a href="#"><i class="fas fa-file-alt"></i> Exams</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Results</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Student Management</h1>
            </div>
            
            <!-- Filters -->
            <div class="filters">
                <div class="filter-group">
                    <label for="classFilter">Class</label>
                    <select id="classFilter">
                        <option value="">All Classes</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sectionFilter">Section</label>
                    <select id="sectionFilter">
                        <option value="">All Sections</option>
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
            
            <!-- Students Container -->
            <div class="students-container">
                <div class="students-header">
                    <h2>Student List</h2>
                    <button id="importBtn" class="btn">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                </div>
                
                <!-- Table View -->
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
                
                <!-- No Results -->
                <div class="no-results" id="noResults" style="display: none;">
                    <i class="fas fa-search"></i>
                    <h3>No Students Found</h3>
                    <p>Try adjusting your search or filter criteria</p>
                </div>
                
                <!-- Pagination -->
                <div class="pagination" id="pagination">
                    <!-- Pagination will be dynamically generated -->
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = window.location.origin;
        
        // DOM elements
        const studentsTable = document.getElementById('studentsTable');
        const studentTableBody = document.getElementById('studentTableBody');
        const searchInput = document.getElementById('searchInput');
        const classFilter = document.getElementById('classFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const searchBtn = document.getElementById('searchBtn');
        const noResultsElement = document.getElementById('noResults');
        const paginationContainer = document.getElementById('pagination');

        // Core functions
        async function fetchStudents(page = 1) {
            const search = searchInput.value;
            const classValue = classFilter.value;
            const sectionValue = sectionFilter.value;
            const limit = 10;

            try {
                const response = await fetch(`${baseUrl}/student/fetchStudents?page=${page}&limit=${limit}&search=${search}&class=${classValue}&section=${sectionValue}`);
                const data = await response.json();
                
                if (data.status === 'success') {
                    if (data.data.students.length === 0) {
                        showNoResults();
                    } else {
                        renderStudents(data.data.students);
                        setupPagination(data.data.pagination);
                    }
                }
            } catch (error) {
                console.error('Error fetching students:', error);
                showNoResults();
            }
        }

        function renderStudents(students) {
            studentTableBody.innerHTML = '';
            students.forEach(student => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.admission_no || '-'}</td>
                    <td>${student.firstname} ${student.lastname}</td>
                    <td>${student.class_name || '-'}</td>
                    <td>${student.section_name || '-'}</td>
                    <td><span class="table-status ${student.is_active === 'yes' ? 'active' : 'inactive'}">${student.is_active === 'yes' ? 'Active' : 'Inactive'}</span></td>
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
        }

        function setupPagination(pagination) {
            paginationContainer.innerHTML = '';
            
            if (pagination.total_pages <= 1) {
                paginationContainer.style.display = 'none';
                return;
            }

            paginationContainer.style.display = 'flex';
            
            // Previous button
            const prevBtn = createPageButton('‹', pagination.current_page > 1, () => fetchStudents(pagination.current_page - 1));
            paginationContainer.appendChild(prevBtn);

            // Page numbers
            for (let i = 1; i <= pagination.total_pages; i++) {
                if (shouldShowPageNumber(i, pagination.current_page, pagination.total_pages)) {
                    const pageBtn = createPageButton(
                        i,
                        true,
                        () => fetchStudents(i),
                        i === pagination.current_page
                    );
                    paginationContainer.appendChild(pageBtn);
                } else if (shouldShowEllipsis(i, pagination.current_page, pagination.total_pages)) {
                    const ellipsis = createEllipsis();
                    paginationContainer.appendChild(ellipsis);
                }
            }

            // Next button
            const nextBtn = createPageButton('›', pagination.current_page < pagination.total_pages, () => fetchStudents(pagination.current_page + 1));
            paginationContainer.appendChild(nextBtn);
        }

        function createPageButton(text, enabled, onClick, isActive = false) {
            const button = document.createElement('button');
            button.className = `page-btn${isActive ? ' active' : ''}${!enabled ? ' disabled' : ''}`;
            button.textContent = text;
            if (enabled) button.onclick = onClick;
            return button;
        }

        function createEllipsis() {
            const span = document.createElement('span');
            span.className = 'page-btn disabled';
            span.textContent = '...';
            return span;
        }

        function shouldShowPageNumber(pageNum, currentPage, totalPages) {
            return pageNum === 1 ||
                   pageNum === totalPages ||
                   (pageNum >= currentPage - 1 && pageNum <= currentPage + 1);
        }

        function shouldShowEllipsis(pageNum, currentPage, totalPages) {
            return (pageNum === currentPage - 2 && pageNum > 2) ||
                   (pageNum === currentPage + 2 && pageNum < totalPages - 1);
        }

        function showNoResults() {
            studentTableBody.innerHTML = '';
            noResultsElement.style.display = 'block';
            studentsTable.style.display = 'none';
            paginationContainer.style.display = 'none';
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            fetchStudents(1);
        });

        searchBtn.addEventListener('click', () => {
            fetchStudents(1);
        });

        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                fetchStudents(1);
            }
        });

        async function viewStudent(id) {
            try {
                const response = await fetch(`${baseUrl}/student/getStudent/${id}`);
                const data = await response.json();
                if (data.status === 'success') {
                    // Implement your view logic here
                    console.log('Student data:', data.data);
                }
            } catch (error) {
                console.error('Error fetching student details:', error);
            }
        }

        async function editStudent(id) {
            try {
                const response = await fetch(`${baseUrl}/student/getStudent/${id}`);
                const data = await response.json();
                if (data.status === 'success') {
                    // Implement your edit logic here
                    console.log('Student data to edit:', data.data);
                }
            } catch (error) {
                console.error('Error fetching student details:', error);
            }
        }
    </script>
</body>
</html>