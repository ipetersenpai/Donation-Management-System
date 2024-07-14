@extends('layouts.app')

@section('content')
    @include('layouts.navbar')
    <div class="container-fluid">
        <div class="row">
            @include('layouts.sidebar')
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content" style="background: #f4f7fc">
                <h2>Welcome to Dashboard</h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text">1000</p> <!-- Replace with dynamic data -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Donations</h5>
                                <p class="card-text">$50,000</p> <!-- Replace with dynamic data -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Receipts</h5>
                                <p class="card-text">500</p> <!-- Replace with dynamic data -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Money Received</h5>
                                <p class="card-text">$30,000</p> <!-- Replace with dynamic data -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
