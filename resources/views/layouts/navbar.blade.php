<div class="navbar-container">
    <div class="d-flex gap-2 align-items-center justify-content-center">
        <img src="{{ asset('assets/Logo.png') }}" style="width: 40px;" alt="logo">
        <h5 style="font-weight: 600;">Donation Management System</h5>
    </div>

    <form id="profile-form" class="open-menu d-flex align-items-center justify-content-center gap-1" <p>Hello,
        {{ auth()->user()->first_name }}!</p>
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
            class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
            <path fill-rule="evenodd"
                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
        </svg>
    </form>
</div>

<div class="menu-item" id="menu-item">
    <p class="text-style d-flex gap-2 align-items-center" style="padding: 7px; margin: 0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-person-fill" viewBox="0 0 16 16">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
        </svg>
        Profile
    </p>
    <p class="text-style d-flex gap-2 align-items-center" style="padding: 7px; margin: 0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-gear-fill" viewBox="0 0 16 16">
            <path
                d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
        </svg>
        Change Password
    </p>
    <form id="logout-form" action="{{ url('/auth/logout') }}" method="POST">
        @csrf
        <p class="text-style d-flex gap-2 align-items-center" style="padding: 7px; margin: 0;" type="button"
            onclick="document.getElementById('logout-form').submit();">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                <path fill-rule="evenodd"
                    d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
            </svg>
            Logout
        </p>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileForm = document.getElementById('profile-form');
        const menuItem = document.getElementById('menu-item');

        profileForm.addEventListener('click', function(event) {
            event.stopPropagation();
            menuItem.style.display = menuItem.style.display === 'none' || menuItem.style.display ===
                '' ? 'block' : 'none';
        });

        document.addEventListener('click', function(event) {
            if (!menuItem.contains(event.target) && !profileForm.contains(event.target)) {
                menuItem.style.display = 'none';
            }
        });
    });
</script>

<style>
    .navbar-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
        background: white;
        height: 50px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        padding-left: 5px;
        padding-right: 5px;
    }

    .menu-item {
        position: absolute;
        right: 5px;
        top: 62px;
        background-color: white;
        z-index: 10;
        font-size: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 150px;
        display: none;
    }

    .text-style:hover {
        background-color: #f1f3f4;
        cursor: pointer;
    }

    .open-menu:hover {
        color: #0d6efd;
        cursor: pointer;
    }
</style>
