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
<body>
<div class="dashboard">
        <!-- Replace the old sidebar with the shared one -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Exam Result Management</h2>
            </div>
            <?= $this->include('shared/sidebar_menu') ?>
        </div>
        
        <!-- Rest of the content remains the same -->
        <div class="main-content">
            <div class="header">
                <h1>Student Management</h1>
            </div>
            
            <!-- Filters -->
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
        const sessionFilter = document.getElementById('sessionFilter');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', async () => {
            await loadSessions();
            classFilter.disabled = true;
            sectionFilter.disabled = true;
            showNoResults('Please select academic year to view students');
        });

        // Event Listeners
        sessionFilter.addEventListener('change', async () => {
            const sessionId = sessionFilter.value;
            
            // Reset everything
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
                console.log('Fetching sessions...'); // Debug log
                const response = await fetch(`${baseUrl}/student/getSessions`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                console.log('Response status:', response.status); // Debug log
                const responseText = await response.text();
                console.log('Raw response:', responseText); // Debug log

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

                console.log('Parsed sessions data:', data); // Debug log

                if (data.status === 'success') {
                    sessionFilter.innerHTML = '<option value="">Select Year</option>';
                    if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                        data.data.forEach(session => {
                            sessionFilter.innerHTML += `
                                <option value="${session.id}">${session.session}</option>
                            `;
                        });
                        console.log('Sessions loaded successfully'); // Debug log
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
                console.log('Classes response:', data); // Debug log
                
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
                console.log('Sections response:', data); // Debug log
                
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

                // Validate required parameters
                if (!sessionValue || !classValue || !sectionValue) {
                    showNoResults('Please select academic year, class and section');
                    return;
                }

                // Show loading state
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
                
                console.log('Students response:', data); // Debug log

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
                    <td>${student.admission_no || '-'}</td>
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

            // Update table display
            noResultsElement.style.display = 'none';
            studentsTable.style.display = 'table';
            paginationContainer.style.display = 'flex';
        }

        function setupPagination(pagination) {
            const { current_page, total_pages } = pagination;
            
            let paginationHTML = '';
            
            // Previous button
            paginationHTML += `
                <button class="page-btn ${current_page === 1 ? 'disabled' : ''}" 
                        onclick="fetchStudents(${current_page - 1})" 
                        ${current_page === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            // Page numbers
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
                    paginationHTML += `<span class="page-btn disabled">...</span>`;
                }
            }

            // Next button
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

        // Keep the existing viewStudent and editStudent functions
        
        async function viewStudent(id) {
            try {
                const response = await fetch(`${baseUrl}/student/getStudent/${id}`);
                const data = await response.json();
                if (data.status === 'success') {
                    // Here you can implement a modal or page to view student details
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
                    // Here you can implement a form to edit student details
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