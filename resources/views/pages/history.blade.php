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

                <!-- Success and Error Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="header" style="margin-top: {{ session('success') || session('error') ? '0' : '20px' }};">
                    <h3>Donation History</h3>
                </div>

                <!-- Search Form -->
                <div class="d-flex w-full flex-md-row flex-column justify-content-between">
                    <form action="{{ route('donations.search') }}" method="GET"
                        class="d-flex flex-sm-col flex-md-row gap-2 justify-content-start my-3">
                        <div class="form-group">
                            <input type="text" style="width: 250px" name="search" class="form-control"
                                placeholder="Search...">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>

                    <!-- Buttons -->
                    <div>
                        <button type="button" class="btn btn-secondary my-md-3 my-2" data-toggle="modal"
                            data-target="#dateRangeModal">
                            Export to PDF
                        </button>

                        <button type="button" class="btn btn-primary my-md-3 my-2" data-toggle="modal"
                            data-target="#AddDonationModal">
                            Manually Add
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
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
                                    <td>
                                        @if ($donation->user)
                                            {{ $donation->user->first_name }} {{ $donation->user->middle_name ?? '' }}
                                            {{ $donation->user->last_name }}
                                        @else
                                            {{ $donation->non_member_full_name }}
                                        @endif
                                    </td>
                                    <td>{{ $donation->category->category_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($donation->amount, 2) }}</td>
                                    <td>{{ $donation->reference_no }}</td>
                                    <td>{{ $donation->payment_option }}</td>
                                    <td>{{ \Carbon\Carbon::parse($donation->created_at)->format('m-d-Y') }}</td>
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

    <!-- Add Donation Modal -->
    <div class="modal fade" id="AddDonationModal" tabindex="-1" role="dialog" aria-labelledby="AddDonationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('donation.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddDonationModalLabel">Manually Add Donation</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="is_member">Is Member?</label>
                            <select id="is_member" name="is_member" class="form-control" required
                                onchange="toggleMemberInput(this)">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="form-group" id="memberSelect">
                            <label for="user_id">Select Member</label>
                            <select id="user_id" name="user_id" class="form-control" required>
                                <option value="">Select a member</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->first_name }}
                                        {{ $member->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="manualNameInput" style="display:none;">
                            <label for="name">Enter Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Full Name"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Donation Category</label>
                            <select id="category_id" name="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" id="amount" name="amount" class="form-control"
                                placeholder="Enter amount" required>
                        </div>

                        <div class="form-group">
                            <label for="reference_no">Reference No</label>
                            <input type="text" id="reference_no" name="reference_no" class="form-control"
                                placeholder="Enter reference number" required>
                        </div>

                        <div class="form-group">
                            <label for="payment_option">Payment Option</label>
                            <select id="payment_option" name="payment_option" class="form-control" required>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Credit Card">Credit Card</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Donation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="dateRangeModal" tabindex="-1" aria-labelledby="dateRangeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dateRangeModalLabel">Select Donator and Date Range</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('donations.export') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="donator_id">Donator:</label>
                            <select id="donator_id" name="donator_id" class="form-control">
                                <option value="">All</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">Member: {{ $member->first_name }}
                                        {{ $member->last_name }}</option>
                                @endforeach
                                @foreach ($nonMembers as $nonMember)
                                    <option value="non_member_{{ $nonMember->id }}">Non-Member:
                                        {{ $nonMember->non_member_full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </form>
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

        function toggleMemberInput(select) {
            const isMember = select.value;
            const memberSelect = document.getElementById('memberSelect');
            const manualNameInput = document.getElementById('manualNameInput');

            if (isMember == '1') {
                memberSelect.style.display = 'block';
                manualNameInput.style.display = 'none';
            } else {
                memberSelect.style.display = 'none';
                manualNameInput.style.display = 'block';
            }
        }
    </script>
@endsection
