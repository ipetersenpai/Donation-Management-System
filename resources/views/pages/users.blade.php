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
            <div class="relative col-md-9 col-lg-10 content" style="height: 100%; display: flex; flex-direction: column; ">

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
                    <h3>User Information
                    </h3>
                </div>

                <div class="d-flex w-full flex-md-row flex-column justify-content-between">
                    <!-- Search Input -->
                    <form action="{{ route('users.search') }}" method="GET"
                        class="d-flex flex-sm-col flex-md-row gap-2 justify-content-start my-3">
                        <div class="form-group">
                            <input type="text" style="width: 250px" name="search" class="form-control"
                                placeholder="Search...">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                    <button type="button" class="btn btn-primary my-md-3 my-2" data-toggle="modal"
                        data-target="#createUserModal">
                        Create User
                    </button>
                </div>

                <!-- Responsive DataTable -->
                <div class="table-responsive" style="overflow-y: auto; height: 65vh; border: 1px solid #f2f2f2;">
                    <table id="userTable" class="table table-striped table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th style="min-width: 300px">Full Name</th>
                                <th style="min-width: 70px">Suffix</th>
                                <th style="min-width: 150px">Birth Date</th>
                                <th style="min-width: 150px">Contact No</th>
                                <th style="min-width: 350px">Home Address</th>
                                <th style="min-width: 100px">Gender</th>
                                <th style="min-width: 250px">Email</th>
                                <th style="min-width: 100px">Role</th>
                                <th style="min-width: 150px">Verified Status</th>
                                <th style="min-width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->suffix }}</td>
                                    <td>{{ Carbon::parse($user->birth_date)->format('m-d-Y') }}</td>
                                    <td>{{ $user->contact_no }}</td>
                                    <td>{{ $user->home_address }}</td>
                                    <td>{{ $user->gender }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->verified_status ? 'Verified' : 'Not Verified' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#updateUserModal-{{ $user->id }}">Update</button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Update User Modal -->
                                <div class="modal fade" id="updateUserModal-{{ $user->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="updateUserModalLabel-{{ $user->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form id="updateUserForm-{{ $user->id }}" method="POST"
                                                action="{{ route('users.update', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateUserModalLabel-{{ $user->id }}">
                                                        Update User: {{ $user->first_name }} {{ $user->last_name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="first_name-{{ $user->id }}">First
                                                                    Name</label>
                                                                <input type="text" class="form-control"
                                                                    id="first_name-{{ $user->id }}" name="first_name"
                                                                    value="{{ $user->first_name }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="middle_name-{{ $user->id }}">Middle
                                                                    Name</label>
                                                                <input type="text" class="form-control"
                                                                    id="middle_name-{{ $user->id }}" name="middle_name"
                                                                    value="{{ $user->middle_name }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="last_name-{{ $user->id }}">Last
                                                                    Name</label>
                                                                <input type="text" class="form-control"
                                                                    id="last_name-{{ $user->id }}" name="last_name"
                                                                    value="{{ $user->last_name }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="suffix-{{ $user->id }}">Suffix</label>
                                                                <input type="text" class="form-control"
                                                                    id="suffix-{{ $user->id }}" name="suffix"
                                                                    value="{{ $user->suffix }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="birth_date-{{ $user->id }}">Birth
                                                                    Date</label>
                                                                <input type="date" class="form-control"
                                                                    id="birth_date-{{ $user->id }}" name="birth_date"
                                                                    value="{{ \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="contact_no-{{ $user->id }}">Contact
                                                                    No</label>
                                                                <input type="text" class="form-control"
                                                                    id="contact_no-{{ $user->id }}" name="contact_no"
                                                                    value="{{ $user->contact_no }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="gender-{{ $user->id }}">Gender</label>
                                                                <input type="text" class="form-control"
                                                                    id="gender-{{ $user->id }}" name="gender"
                                                                    value="{{ $user->gender }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="email-{{ $user->id }}">Email</label>
                                                                <input type="email" class="form-control"
                                                                    id="email-{{ $user->id }}" name="email"
                                                                    value="{{ $user->email }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="role-{{ $user->id }}">Role</label>
                                                                <input type="text" class="form-control"
                                                                    id="role-{{ $user->id }}" name="role"
                                                                    value="{{ $user->role }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="verified_status-{{ $user->id }}">Verified
                                                                    Status</label>
                                                                <select class="form-control"
                                                                    id="verified_status-{{ $user->id }}"
                                                                    name="verified_status" required>
                                                                    <option value="1"
                                                                        {{ $user->verified_status ? 'selected' : '' }}>
                                                                        Verified</option>
                                                                    <option value="0"
                                                                        {{ !$user->verified_status ? 'selected' : '' }}>Not
                                                                        Verified</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="home_address-{{ $user->id }}">Home Address</label>
                                                        <textarea class="form-control" id="home_address-{{ $user->id }}" name="home_address" rows="2" required>{{ $user->home_address }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password-{{ $user->id }}">Password</label>
                                                        <input type="password" class="form-control"
                                                            id="password-{{ $user->id }}" name="password">
                                                        <small class="form-text text-muted">Leave blank to keep the current
                                                            password.</small>
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

                <!-- Create User Modal -->
                <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog"
                    aria-labelledby="createUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="middle_name">Middle Name</label>
                                                <input type="text" class="form-control" id="middle_name"
                                                    name="middle_name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="suffix">Suffix</label>
                                                <input type="text" class="form-control" id="suffix"
                                                    name="suffix">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="birth_date">Birth Date</label>
                                                <input type="date" class="form-control" id="birth_date"
                                                    name="birth_date" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_no">Contact No</label>
                                                <input type="text" class="form-control" id="contact_no"
                                                    name="contact_no" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gender">Gender</label>
                                                <input type="text" class="form-control" id="gender" name="gender"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="role">Role</label>
                                                <input type="text" class="form-control" id="role" name="role"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="verified_status">Verified Status</label>
                                                <select class="form-control" id="verified_status" name="verified_status"
                                                    required>
                                                    <option value="1">Verified</option>
                                                    <option value="0">Not Verified</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="home_address">Home Address</label>
                                        <textarea class="form-control" id="home_address" name="home_address" rows="2" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>
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
                    {{ $users->links('pagination::bootstrap-4') }}
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
            var table = $('#userTable').DataTable({
                "paging": true,
                "info": true
            });

            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endsection
