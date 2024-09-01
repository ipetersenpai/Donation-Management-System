@extends('layouts.app')

@section('content')
    @include('layouts.navbar')
    <div class="container-fluid">
        <div class="row">
            @include('layouts.sidebar')
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content">
                <div class="header" style="margin-top: {{ session('success') || session('error') ? '0' : '20px' }};">
                    <h3>Dashboard
                    </h3>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-layout">
                            <div class="d-flex flex-column justify-items-center justify-content-center"
                                style="width:40%; background-color: #007bff; color:white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
                                    class="bi bi-people-fill mx-auto" viewBox="0 0 16 16">
                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                </svg>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title" style="font-weight: bold">Total Users</h5>
                                <h4 class="card-text" id="countUser" style="font-weight: 600">Loading...</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-layout">
                            <div class="d-flex flex-column justify-items-center justify-content-center"
                                style="width:40%; background-color: #28a745; color:white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
                                    class="bi bi-currency-exchange mx-auto" viewBox="0 0 16 16">
                                    <path d="M0 5a5 5 0 0 0 4.027 4.905 6.5 6.5 0 0 1 .544-2.073C3.695 7.536 3.132 6.864 3 5.91h-.5v-.426h.466V5.05q-.001-.07.004-.135H2.5v-.427h.511C3.236 3.24 4.213 2.5 5.681 2.5c.316 0 .59.031.819.085v.733a3.5 3.5 0 0 0-.815-.082c-.919 0-1.538.466-1.734 1.252h1.917v.427h-1.98q-.004.07-.003.147v.422h1.983v.427H3.93c.118.602.468 1.03 1.005 1.229a6.5 6.5 0 0 1 4.97-3.113A5.002 5.002 0 0 0 0 5m16 5.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0m-7.75 1.322c.069.835.746 1.485 1.964 1.562V14h.54v-.62c1.259-.086 1.996-.74 1.996-1.69 0-.865-.563-1.31-1.57-1.54l-.426-.1V8.374c.54.06.884.347.966.745h.948c-.07-.804-.779-1.433-1.914-1.502V7h-.54v.629c-1.076.103-1.808.732-1.808 1.622 0 .787.544 1.288 1.45 1.493l.358.085v1.78c-.554-.08-.92-.376-1.003-.787zm1.96-1.895c-.532-.12-.82-.364-.82-.732 0-.41.311-.719.824-.809v1.54h-.005zm.622 1.044c.645.145.943.38.943.796 0 .474-.37.8-1.02.86v-1.674z" />
                                </svg>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title" style="font-weight: bold">Total User Donated</h5>
                                <h4 class="card-text" id="totalUsers" style="font-weight: 600">Loading...</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-layout">
                            <div class="d-flex flex-column justify-items-center justify-content-center"
                                style="width:40%; background-color: #dc3545; color:white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
                                    class="bi bi-currency-exchange mx-auto" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z" />
                                    <path fill-rule="evenodd"
                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                </svg>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title" style="font-weight: bold">Money Received</h5>
                                <h4 class="card-text" id="totalAmount" style="font-weight: 600">Loading...</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-layout">
                            <div class="d-flex flex-column justify-items-center justify-content-center"
                                style="width:40%; background-color: #17a2b8; color:white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
                                    class="bi bi-currency-exchange mx-auto" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z" />
                                    <path fill-rule="evenodd"
                                        d="M7.646.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 1.707V10.5a.5.5 0 0 1-1 0V1.707L5.354 3.854a.5.5 0 1 1-.708-.708z" />

                                </svg>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title" style="font-weight: bold">Amount Allocated</h5>
                                <h4 class="card-text" style="font-weight: 600">81999</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

                $.get('{{ route('users.total_users') }}', function(data) {
                $('#countUser').text(data.total_users);
            });

            // Fetch total number of users who donated
            $.get('{{ route('donations.total_users') }}', function(data) {
                $('#totalUsers').text(data.total_users);
            });

            // Fetch total amount donated
            $.get('{{ route('donations.total_amount') }}', function(data) {
                $('#totalAmount').text(data.total_amount.toLocaleString());
            });

        });
    </script>



    <style>
        .header {
            padding: 7px;
            color: white;
            border-radius: 5px;
            padding-left: 10px;
            margin-bottom: 30px;
            background: linear-gradient(to bottom,
                    rgba(127, 6, 14, 0.944),
                    rgba(160, 8, 36, 0.911));

        }

        .card-layout {
            position: relative;
            display: flex;
            flex-direction: row;
            height: 130px;
        }
    </style>
@endsection
