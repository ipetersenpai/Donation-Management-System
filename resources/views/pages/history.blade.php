@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('content')
    @include('layouts.navbar')
    <div class="container-fluid">
        <div class="row" style="height: 100%; overflow-y: hidden !important;">
            @include('layouts.sidebar')
            <!-- Main Content -->
            <div class="relative col-md-9 col-lg-10 content" style="height: 100%; display: flex; flex-direction: column;">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="header" style="margin-top: {{ session('success') || session('error') ? '0' : '20px' }};">
                    <h3>Donation History</h3>
                </div>

                <!-- Search Input -->
                <form action="{{ route('donations.search') }}" method="GET"
                    class="d-flex flex-sm-col flex-md-row gap-2 justify-content-start my-3">
                    <div class="form-group">
                        <input type="text" style="width: 250px" name="search" class="form-control"
                            placeholder="Search...">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <!-- Responsive DataTable -->
                <div class="table-responsive" style="overflow-y: auto; height: 65vh; border: 1px solid #f2f2f2;">
                    <table id="donationTable" class="table table-striped table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th style="min-width: 200px">Donator Name</th>
                                <th style="min-width: 150px">Category</th>
                                <th style="min-width: 150px">Amount</th>
                                <th style="min-width: 150px">Reference No</th>
                                <th style="min-width: 150px">Payment Option</th>
                                <th style="min-width: 150px">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donations as $donation)
                                <tr>
                                    <td>{{ $donation->user->first_name }} {{ $donation->user->middle_name }} {{ $donation->user->last_name }}</td>
                                    <td>{{ $donation->category->category_name }}</td>
                                    <td>{{ number_format($donation->amount, 2) }}</td>
                                    <td>{{ $donation->reference_no }}</td>
                                    <td>{{ $donation->payment_option }}</td>
                                    <td>{{ Carbon::parse($donation->created_at)->format('m-d-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $donations->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .header {
            padding: 7px;
            color: white;
            border-radius: 5px;
            padding-left: 10px;
            background: linear-gradient(to bottom, rgba(127, 6, 14, 0.944), rgba(160, 8, 36, 0.911));
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#donationTable').DataTable({
                "paging": true,
                "info": true
            });

            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endsection
