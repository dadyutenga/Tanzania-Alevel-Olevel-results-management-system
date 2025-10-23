<ul class="sidebar-menu">
    <li><a href="<?= base_url('dashboard') ?>" class="<?= current_url() == base_url('dashboard') ? 'active' : '' ?>">
        <i class="fas fa-home"></i> Dashboard
    </a></li>
    <li><a href="<?= base_url('analytics') ?>" class="<?= strpos(current_url(), 'analytics') !== false ? 'active' : '' ?>">
        <i class="fas fa-chart-line"></i> Analytics
    </a></li>
    <li>
        <a href="#" class="expandable <?= strpos(current_url(), 'students') !== false ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Students
            <i class="fas fa-chevron-down toggle-icon" style="margin-left:auto;"></i>
        </a>
        <ul class="submenu <?= strpos(current_url(), 'students') !== false ? 'show' : '' ?>">
            <li><a href="<?= base_url('students') ?>"><i class="fas fa-list"></i> View Students</a></li>
            <li><a href="<?= base_url('students/create') ?>"><i class="fas fa-plus-circle"></i> Add Student</a></li>
            <li><a href="<?= base_url('students/bulk-register') ?>"><i class="fas fa-users-cog"></i> Bulk Register</a></li>
        </ul>
    </li>
    <li>
        <a href="#" class="expandable <?= strpos(current_url(), 'classes') !== false ? 'active' : '' ?>">
            <i class="fas fa-chalkboard-teacher"></i> Classes
            <i class="fas fa-chevron-down toggle-icon" style="margin-left:auto;"></i>
        </a>
        <ul class="submenu <?= strpos(current_url(), 'classes') !== false ? 'show' : '' ?>">
            <li><a href="<?= base_url('classes') ?>"><i class="fas fa-list"></i> View Classes</a></li>
            <li><a href="<?= base_url('classes/create') ?>"><i class="fas fa-plus-circle"></i> Add Class</a></li>
            <li><a href="<?= base_url('classes/sections') ?>"><i class="fas fa-th-large"></i> View Sections</a></li>
            <li><a href="<?= base_url('classes/sections/create') ?>"><i class="fas fa-plus-circle"></i> Add Section</a></li>
            <li><a href="<?= base_url('classes/allocations') ?>"><i class="fas fa-link"></i> View Allocations</a></li>
            <li><a href="<?= base_url('classes/allocations/create') ?>"><i class="fas fa-plus-circle"></i> Add Allocation</a></li>
        </ul>
    </li>
    <li>
        <a href="#" class="expandable <?= strpos(current_url(), 'exam') !== false ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i> Exams
            <i class="fas fa-chevron-down toggle-icon" style="margin-left:auto;"></i>
        </a>
        <ul class="submenu <?= strpos(current_url(), 'exam') !== false ? 'show' : '' ?>">
            <li><a href="<?= base_url('exam') ?>"><i class="fas fa-plus-circle"></i> Add Exams</a></li>
            <li><a href="<?= base_url('exam/subjects') ?>"><i class="fas fa-book"></i> Add Exam Subjects</a></li>
            <li><a href="<?= base_url('exam/allocation') ?>"><i class="fas fa-chalkboard"></i> Allocate Classes</a></li>
            <li><a href="<?= base_url('exam/marks') ?>"><i class="fas fa-pen"></i> Add Exam Marks</a></li>
            <li><a href="<?= base_url ('exam/marks/bulk')?>"><i class="fas fa-upload"></i> Bulk Add Exam Marks</a></li>
            <li><a href="<?= base_url('exam/view') ?>"><i class="fas fa-file-alt"></i> View Exam</a></li>
            <li><a href="<?= base_url('exam/marks/view') ?>"><i class="fas fa-eye"></i> View Exam Marks</a></li>
        </ul>
    </li>
    <li>
        <a href="#" class="expandable <?= strpos(current_url(), 'results') !== false ? 'active' : '' ?>">
            <i class="fas fa-chart-bar"></i> Results
            <i class="fas fa-chevron-down toggle-icon" style="margin-left:auto;"></i>
        </a>
        <ul class="submenu <?= strpos(current_url(), 'results') !== false ? 'show' : '' ?>">
            <li><a href="<?= base_url('results/publish') ?>"><i class="fas fa-chart-bar"></i> Publish Results</a></li>
            <li><a href="<?= base_url('results/view')?>"><i class="fas fa-eye"></i> View Results</a></li>
        </ul>
    </li>
    <li>
        <a href="#" class="expandable <?= strpos(current_url(), 'alevel') !== false ? 'active' : '' ?>">
            <i class="fas fa-graduation-cap"></i> A-Level
            <i class="fas fa-chevron-down toggle-icon" style="margin-left:auto;"></i>
        </a>
        <ul class="submenu <?= strpos(current_url(), 'alevel') !== false ? 'show' : '' ?>">
            <li><a href="<?= base_url('alevel/combinations') ?>"><i class="fas fa-layer-group"></i> Manage Combinations</a></li>
            <li><a href="<?= base_url('alevel/subjects') ?>"><i class="fas fa-book"></i> Manage Subjects</a></li>
            <li><a href="<?= base_url('alevel/subjects/view') ?>"><i class="fas fa-eye"></i> View Subjects</a></li>
            <li><a href="<?= base_url('alevel/allocations') ?>"><i class="fas fa-chalkboard"></i> Allocate Classes</a></li>
            <li><a href="<?= base_url('alevel/allocations/view') ?>"><i class="fas fa-eye"></i> View Allocations</a></li>
            <li><a href="<?= base_url('alevel/allocate-exams') ?>"><i class="fas fa-chalkboard"></i> Allocate Exams</a></li>
            <li><a href="<?= base_url('alevel/view-exams') ?>"><i class="fas fa-eye"></i> View Exams</a></li>
            <li><a href="<?= base_url('alevel/marks') ?>"><i class="fas fa-plus-circle"></i> Add Exam Marks</a></li>
            <li><a href="<?= base_url('alevel/marks/bulk') ?>"><i class="fas fa-upload"></i> Bulk Marks Upload</a></li>
            <li><a href="<?= base_url('alevel/marks/view') ?>"><i class="fas fa-eye"></i> View Exam Marks</a></li>
           <li><a href="<?= base_url('alevel/results/publish') ?>"><i class="fas fa-chart-bar"></i> Publish Results</a></li>
           <li><a href="<?= base_url('alevel/results/view') ?>"><i class="fas fa-eye"></i> View Results</a></li>
        </ul>
    </li>
    <li><a href="<?= base_url('settings') ?>" class="<?= strpos(current_url(), 'settings') !== false ? 'active' : '' ?>">
        <i class="fas fa-cog"></i> Settings
    </a></li>
    <li class="logout-section">
        <a href="<?= base_url('logout') ?>" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </li>
</ul>
