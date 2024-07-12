<style>
    .navbar-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
        background: white;
        height: 55px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        padding-left: 5px;
        padding-right: 5px;
    }
</style>

<div class="navbar-container">
    <div class="d-flex gap-2 align-items-center justify-content-center">
        <img src="{{ asset('assets/Logo.png') }}" style="width: 50px;" alt="logo">
        <h4 style="font-weight: 600;">Donation Management System</h4>
    </div>

    <form class="d-flex gap-2 align-items-center justify-content-center" id="logout-form"
        action="{{ url('/auth/logout') }}" method="POST" style="display: none;">
        @csrf
        <button type="button" onclick="document.getElementById('logout-form').submit();">
            Logout
        </button>

    </form>


</div>
