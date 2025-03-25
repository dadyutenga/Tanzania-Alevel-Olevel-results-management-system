<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Muted Color Scheme */
        :root {
            --primary: #f8f8f8;
            --primary-dark: #f0f0f0;
            --secondary: #e8e8e8;
            --accent: #2c2c2c;
            --accent-light: #3d3d3d;
            --text-primary: #333333;
            --text-secondary: #666666;
            --border: #e0e0e0;
            --success: #5a9a8a;
            --danger: #a15858;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            --radius: 6px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--primary-dark);
            color: var(--text-primary);
            line-height: 1.6;
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
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header i {
            font-size: 2rem;
            margin-right: 0.75rem;
            opacity: 0.9;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
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
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.3s ease;
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
            background-color: var(--primary);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            border: 1px solid var(--border);
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .filter-group select, .filter-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background-color: var(--primary);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .filter-group select:focus, .filter-group input:focus {
            outline: none;
            border-color: var(--accent-light);
            box-shadow: 0 0 0 1px rgba(45, 45, 45, 0.1);
        }

        .btn {
            background-color: var(--accent);
            color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: var(--radius);
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
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
            background-color: var(--primary);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .students-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .students-header h2 {
            font-size: 1.3rem;
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
            background-color: var(--accent);
            color: rgba(255, 255, 255, 0.9);
            text-align: left;
            padding: 0.9rem 1rem;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .students-table th:first-child {
            border-top-left-radius: var(--radius);
        }

        .students-table th:last-child {
            border-top-right-radius: var(--radius);
        }

        .students-table td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
            font-size: 0.95rem;
        }

        .students-table tr:last-child td {
            border-bottom: none;
        }

        .students-table tr:hover {
            background-color: var(--secondary);
        }

        .table-status {
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }

        .table-status.active {
            background-color: rgba(90, 154, 138, 0.1);
            color: var(--success);
            border: 1px solid rgba(90, 154, 138, 0.2);
        }

        .table-status.inactive {
            background-color: rgba(161, 88, 88, 0.1);
            color: var(--danger);
            border: 1px solid rgba(161, 88, 88, 0.2);
        }

        .action-btn {
            padding: 0.4rem 0.9rem;
            border-radius: var(--radius);
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            margin-right: 0.5rem;
        }

        .action-btn.view {
            background-color: var(--accent);
            color: rgba(255, 255, 255, 0.9);
        }

        .action-btn.edit {
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
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
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
            background-color: var(--primary);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .page-btn:hover {
            background-color: var(--secondary);
            color: var(--text-primary);
        }

        .page-btn.active {
            background-color: var(--accent);
            color: rgba(255, 255, 255, 0.9);
            border-color: var(--accent);
        }

        .page-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* No Results */
        .no-results {
            padding: 3rem;
            text-align: center;
        }

        .no-results i {
            font-size: 2.5rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .no-results h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .no-results p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
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
                <h2>Student Portal</h2>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Students</a></li>
                <li><a href="#"><i class="fas fa-chalkboard-teacher"></i> Teachers</a></li>
                <li><a href="#"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="#"><i class="fas fa-calendar-alt"></i> Schedule</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
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
                        <option value="9th">9th Grade</option>
                        <option value="10th">10th Grade</option>
                        <option value="11th">11th Grade</option>
                        <option value="12th">12th Grade</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sectionFilter">Section</label>
                    <select id="sectionFilter">
                        <option value="">All Sections</option>
                        <option value="A">Section A</option>
                        <option value="B">Section B</option>
                        <option value="C">Section C</option>
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
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <!-- Table rows will be populated dynamically -->
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
                    <!-- Pagination buttons will be added here -->
                </div>
            </div>
        </div>
    </div>

    // Replace the existing script section with:
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