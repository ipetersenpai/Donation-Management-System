@extends('layouts.app')

@section('content')
    @include('layouts.navbar')

    <div class="container-fluid">
        <div class="row" style="height: 100%; overflow-y: hidden;">
            @include('layouts.sidebar')

            <div class="col-md-9 col-lg-10 content" style="height: 100%; display: flex; flex-direction: column;">
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
                    <h3>User Profile</h3>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="p-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control"
                                    value="{{ $user->first_name }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control"
                                    value="{{ $user->middle_name }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control"
                                    value="{{ $user->last_name }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="suffix">Suffix</label>
                                <input type="text" name="suffix" id="suffix" class="form-control"
                                    value="{{ $user->suffix }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="birth_date">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="contact_no">Contact Number</label>
                                <input type="text" name="contact_no" id="contact_no" class="form-control"
                                    value="{{ $user->contact_no }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $user->email }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="home_address">Home Address</label>
                                <textarea name="home_address" id="home_address" class="form-control" rows="3" required>{{ $user->home_address }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-end" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
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
            margin-bottom: 15px;
            background: linear-gradient(to bottom,
                    rgba(127, 6, 14, 0.944),
                    rgba(160, 8, 36, 0.911));

        }

        .form-group label {
            font-weight: bold;
        }
    </style>
@endsection
