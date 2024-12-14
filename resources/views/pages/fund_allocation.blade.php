@extends('layouts.app')

@section('content')
    @include('layouts.navbar')
    <div class="container-fluid">
        <div class="row" style="height: 100%; overflow-y: hidden !important;">
            @include('layouts.sidebar')
            <!-- Main Content -->
            <div class="relative col-md-9 col-lg-10 content" style="height: 100%; display: flex; flex-direction: column; ">

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
                    <h3>Fund Allocations</h3>
                </div>

                <div class="d-flex w-full flex-md-row flex-column justify-content-between">
                    <!-- Search Input -->
                    <form action="{{ route('fund_allocations.search') }}" method="GET"
                        class="d-flex flex-sm-col flex-md-row gap-2 justify-content-start my-3">
                        <div class="form-group">
                            <input type="text" style="width: 250px" name="search" class="form-control"
                                placeholder="Search...">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                    <div>
                        <a href="{{ route('fund_allocations.export') }}" class="btn btn-secondary my-md-3 my-2">Export to
                            CSV</a>
                        <button type="button" class="btn btn-primary my-md-3 my-2" data-toggle="modal"
                            data-target="#createAllocationModal">
                            Allocate Funds
                        </button>
                        <button type="button" class="btn btn-secondary my-md-3 my-2" data-toggle="modal"
                            data-target="#exportAllocationModal">
                            Export with Filters
                        </button>
                    </div>

                </div>

                <!-- Responsive DataTable -->
                <div class="table-responsive" style="overflow-y: auto; height: 65vh; border: 1px solid #f2f2f2;">
                    <table id="fundAllocationTable" class="table table-striped table-bordered table-hover"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Project Name</th>
                                <th>Allocated Amount</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allocations as $allocation)
                                <tr>
                                    <td>{{ $allocation->category->category_name }}</td>
                                    <td>{{ $allocation->project_name }}</td>
                                    <td>{{ number_format($allocation->allocated_amount, 2) }}</td>
                                    <td>{{ $allocation->created_at->format('m-d-Y') }}</td>
                                    <td>{{ $allocation->updated_at->format('m-d-Y') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#updateAllocationModal-{{ $allocation->id }}">Update</button>
                                        <form action="{{ route('fund_allocations.destroy', $allocation->id) }}"
                                            method="POST" style="display:inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this allocation?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Update Allocation Modal -->
                                <div class="modal fade" id="updateAllocationModal-{{ $allocation->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="updateAllocationModalLabel-{{ $allocation->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form id="updateAllocationForm-{{ $allocation->id }}" method="POST"
                                                action="{{ route('fund_allocations.update', $allocation->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="updateAllocationModalLabel-{{ $allocation->id }}">
                                                        Update Allocation</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="category_id-{{ $allocation->id }}">Category</label>
                                                        <select class="form-control" id="category_id-{{ $allocation->id }}"
                                                            name="category_id" required>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ $category->id == $allocation->category_id ? 'selected' : '' }}>
                                                                    {{ $category->category_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="project_name-{{ $allocation->id }}">Project
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="project_name-{{ $allocation->id }}" name="project_name"
                                                            value="{{ $allocation->project_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="allocated_amount-{{ $allocation->id }}">Allocated
                                                            Amount</label>
                                                        <input type="number" step="0.01" class="form-control"
                                                            id="allocated_amount-{{ $allocation->id }}"
                                                            name="allocated_amount"
                                                            value="{{ $allocation->allocated_amount }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Export Filter Modal -->
                <div class="modal fade" id="exportAllocationModal" tabindex="-1" role="dialog"
                    aria-labelledby="exportAllocationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('fund_allocations.export') }}" method="GET">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportAllocationModalLabel">Export Fund Allocations</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Select Category -->
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <select id="category_id" name="category_id" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Date Range Filters -->
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
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


                <!-- Create Allocation Modal -->
                <div class="modal fade" id="createAllocationModal" tabindex="-1" role="dialog"
                    aria-labelledby="createAllocationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="createAllocationForm" method="POST"
                                action="{{ route('fund_allocations.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createAllocationModalLabel">Allocate Funds</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <select class="form-control" id="category_id" name="category_id" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="project_name">Project Name</label>
                                        <input type="text" class="form-control" id="project_name" name="project_name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="allocated_amount">Allocated Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="allocated_amount"
                                            name="allocated_amount" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $allocations->links('pagination::bootstrap-4') }}
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
            background: linear-gradient(to bottom,
                    rgba(127, 6, 14, 0.944),
                    rgba(160, 8, 36, 0.911));
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#fundAllocationTable').DataTable({
                "paging": true,
                "info": true
            });

            $('#searchInput').on('keyup', function() {
                $('#fundAllocationTable').DataTable().search(this.value).draw();
            });
        });
    </script>
@endsection
