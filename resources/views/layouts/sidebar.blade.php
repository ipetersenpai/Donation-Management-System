<!-- Sidebar -->
<div class="col-md-3 col-lg-2 sidebar" id="sidebar">
    <div class="toggle-sidebar d-flex justify-content-end">

        <svg class="toggle-icon mt-2" style="cursor: pointer; margin-right:10px" onclick="toggleSidebar()"
            xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
        </svg>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="{{ env('BASE_URL') }}/users" class="menu-list">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path
                            d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                    </svg>
                </i>
                <span class="menu-text">Users</span>
            </a>
        </li>
        <li>
            <a href="{{ env('BASE_URL') }}/categories" class="menu-list">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-card-checklist" viewBox="0 0 16 16">
                        <path
                            d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                        <path
                            d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
                    </svg>
                </i>
                <span class="menu-text">Donation Settings</span>
            </a>
        </li>
        <li>
            <a href="{{ env('BASE_URL') }}/history" class="menu-list">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
                        <path
                            d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z" />
                    </svg></i>
                <span class="menu-text">Donated History</span>
            </a>
        </li>
        <li>
            <a href="{{ env('BASE_URL') }}/fund-allocations" class="menu-list">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-currency-exchange" viewBox="0 0 16 16">
                        <path
                            d="M0 5a5 5 0 0 0 4.027 4.905 6.5 6.5 0 0 1 .544-2.073C3.695 7.536 3.132 6.864 3 5.91h-.5v-.426h.466V5.05q-.001-.07.004-.135H2.5v-.427h.511C3.236 3.24 4.213 2.5 5.681 2.5c.316 0 .59.031.819.085v.733a3.5 3.5 0 0 0-.815-.082c-.919 0-1.538.466-1.734 1.252h1.917v.427h-1.98q-.004.07-.003.147v.422h1.983v.427H3.93c.118.602.468 1.03 1.005 1.229a6.5 6.5 0 0 1 4.97-3.113A5.002 5.002 0 0 0 0 5m16 5.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0m-7.75 1.322c.069.835.746 1.485 1.964 1.562V14h.54v-.62c1.259-.086 1.996-.74 1.996-1.69 0-.865-.563-1.31-1.57-1.54l-.426-.1V8.374c.54.06.884.347.966.745h.948c-.07-.804-.779-1.433-1.914-1.502V7h-.54v.629c-1.076.103-1.808.732-1.808 1.622 0 .787.544 1.288 1.45 1.493l.358.085v1.78c-.554-.08-.92-.376-1.003-.787zm1.96-1.895c-.532-.12-.82-.364-.82-.732 0-.41.311-.719.824-.809v1.54h-.005zm.622 1.044c.645.145.943.38.943.796 0 .474-.37.8-1.02.86v-1.674z" />
                    </svg></i>
                <span class="menu-text">Funds Management</span>
            </a>
        </li>
    </ul>
</div>

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
        overflow-y: auto;
        padding: 0;
    }

    .sidebar.sidebar-closed {
        width: 50px;
        overflow: hidden;
    }

    .sidebar-menu {
        padding: 0;
        list-style-type: none;
    }

    .sidebar-menu li {
        margin-bottom: 5px;
        padding: 0;
        box-sizing: border-box;
    }

    .sidebar-menu li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 10px 15px;
        width: 100%;

    }

    .sidebar-menu li a:hover {
        background-color: rgb(91, 21, 21);
    }

    .sidebar-menu li a i {
        margin-right: 10px;
        display: inline;
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
            height: 45px;
        }

        .toggle-sidebar {
            display: block;
            text-align: left;
            padding: 10px;
            background-color: maroon;
        }

        .toggle-icon {
            display: block;
            text-align: left;
            line-height: 55px;
            margin: 0 !important;
        }

        .sidebar.sidebar-closed .toggle-icon {
            text-align: left;
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

    .menu-list:hover {
        background: rebeccapurple;
        width: 100%;
        margin: 0;
    }
</style>
