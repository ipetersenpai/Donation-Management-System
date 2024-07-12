<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-closed');
    }
</script>

<style>
    .sidebar {
        background-color: maroon;
        color: white;
        height: 100vh;
        transition: width 0.3s ease;
        overflow-y: auto; /* Add scroll if content exceeds sidebar height */
    }

    .sidebar.sidebar-closed {
        width: 60px; /* Adjust sidebar width when closed */
        overflow: hidden;
    }

    .sidebar-menu {
        padding-left: 0;
        list-style-type: none;
    }

    .sidebar-menu li {
        margin-bottom: 10px;
    }

    .sidebar-menu li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 10px 15px;
    }

    .sidebar-menu li a i {
        margin-right: 10px;
        display: inline; /* Ensure icons are always displayed */
    }

    .sidebar-menu .menu-text {
        display: block;
        transition: opacity 0.3s ease;
    }

    .sidebar.sidebar-closed .sidebar-menu .menu-text {
        opacity: 0;
        pointer-events: none;
        position: absolute;
        left: -9999px;
    }

    .content {
        padding-top: 20px;
    }

    .card {
        margin-bottom: 20px;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .sidebar {
            height: auto;
            width: 100%;
            position: relative;
        }

        .sidebar.sidebar-closed {
            width: 100%;
            height: 55px; /* Adjust height when closed */
        }

        .toggle-sidebar {
            display: block;
            text-align: left; /* Ensure the toggle icon stays on the left */
            padding: 10px;
            background-color: maroon;
        }

        .toggle-icon {
            display: block;
            text-align: left; /* Keep the icon left-aligned */
            padding-left: 10px; /* Add padding to align with the menu items */
            line-height: 55px; /* Vertically center the icon */
        }

        .sidebar.sidebar-closed .toggle-icon {
            text-align: left; /* Ensure the icon stays left-aligned */
        }

        .sidebar.sidebar-closed .sidebar-menu {
            display: none;
        }

        .sidebar.sidebar-closed .menu-hidden {
            display: none;
        }

        .content {
            margin-top: 20px;
            margin-left: 0;
        }

        .content-expanded {
            margin-left: 0;
        }
    }
</style>

<!-- Sidebar -->
<div class="col-md-3 col-lg-2 sidebar" id="sidebar">
<div class="toggle-sidebar">
    <span class="toggle-icon" onclick="toggleSidebar()">☰</span> <!-- Toggle icon -->
</div>
<ul class="sidebar-menu">
    <li>
        <a href="#">
            <i class="fas fa-tachometer-alt">☰</i> <!-- Font Awesome icon -->
            <span class="menu-text">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="#">
            <i class="fas fa-user">☰</i> <!-- Font Awesome icon -->
            <span class="menu-text">Profile</span>
        </a>
    </li>
    <li>
        <a href="#">
            <i class="fas fa-cog">☰</i> <!-- Font Awesome icon -->
            <span class="menu-text">Settings</span>
        </a>
    </li>
</ul>
</div>



