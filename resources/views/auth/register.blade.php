@extends('layouts.app')

@section('content')
    <style>
        .left-side {
            background-color: #800000;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        @media (max-width: 767px) {
            .left-side {
                display: none;
            }
        }

        .right-side {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .register-form {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }

        .progress-bar-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
        }

        .progress-bar-background {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background: #d3d3d3;
            /* Background line color */
            z-index: -2;
            transform: translateY(-50%);
        }

        .progress-bar {
            position: absolute;
            top: 50%;
            left: 0;
            height: 2px;
            background: #28a745;
            /* Progress bar color */
            z-index: -1;
            width: 0;
            transform: translateY(-50%);
        }

        .progress-bar-step {
            text-align: center;
            flex-grow: 1;
            position: relative;
        }

        .progress-bar-step-number {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            background: #d3d3d3;
            /* Default step background */
            color: white;
            font-weight: bold;
            z-index: 1;
        }

        .progress-bar-step.completed .progress-bar-step-number {
            background: #28a745;
            /* Completed step background */
        }
    </style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-7 left-side">
            <div>
                <h1>Register Now!</h1>
                <p>Join our community by creating an account</p>
            </div>
        </div>
        <div class="col-md-5 right-side">
            <div class="register-form">
                <div class="text-center mb-2 pb-1" style="color:#800000; font-weight:bold">
                    <img src="{{ asset('assets/Logo.png') }}" style="width: 105px;" alt="logo">
                    <h4 class="mt-2">CARITAS TARLAC INC.</h4>
                    <p style="color: black; margin-top:-0.5rem">DONATION MANAGEMENT SYSTEM</p>
                </div>
                <div class="progress-bar-container" style="margin-top: 10px">
                    <div class="progress-bar-background"></div>
                    <div class="progress-bar"></div>
                    <div class="progress-bar-step" id="step-indicator-1">
                        <div class="progress-bar-step-number" style="background-color:#28a745">1</div>
                        <div>Personal Info</div>
                    </div>
                    <div class="progress-bar-step" id="step-indicator-2">
                        <div class="progress-bar-step-number">2</div>
                        <div>Contact Info</div>
                    </div>
                    <div class="progress-bar-step" id="step-indicator-3">
                        <div class="progress-bar-step-number">3</div>
                        <div>Account Info</div>
                    </div>
                </div>

                <form id="registrationForm" action="{{ url('/auth/register') }}" method="POST">
                    @csrf

                    <!-- Step 1: Personal Information -->
                    <div id="step1">
                        <h4 class="mb-4">Step 1: Personal Information</h4>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                placeholder="First Name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="middle_name" name="middle_name"
                                placeholder="Middle Name" value="{{ old('middle_name') }}">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                placeholder="Last Name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div style="margin-bottom:25px;">
                            <input type="text" class="form-control" id="suffix" name="suffix"
                                placeholder="Suffix" value="{{ old('suffix') }}">
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger text-center">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary nextBtn" style="width: 150px;">Next</button>
                        </div>
                    </div>

                    <!-- Step 2: Contact Information -->
                    <div id="step2" style="display: none;">
                        <h4 class="mb-4">Step 2: Contact Information</h4>
                        <div class="mb-3">
                            <input type="date" class="form-control" id="birth_date" name="birth_date"
                                value="{{ old('birth_date') }}" required>
                            @error('birth_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="contact_no" name="contact_no"
                                placeholder="Contact No" value="{{ old('contact_no') }}" required>
                            @error('contact_no')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="home_address" name="home_address"
                                placeholder="Home Address" value="{{ old('home_address') }}" required>
                            @error('home_address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="" disabled {{ old('gender') === null ? 'selected' : '' }}>Select Gender</option>
                                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2" style="margin-top:25px;">
                            <button type="button" class="btn btn-secondary prevBtn" style="width: 150px;">Back</button>
                            <button type="button" class="btn btn-primary nextBtn" style="width: 150px;">Next</button>
                        </div>
                    </div>

                    <!-- Step 3: Account Information -->
                    <div id="step3" style="display: none;">
                        <h4 class="mb-4">Step 3: Account Information</h4>
                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Email Address" value="{{ old('email') }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="New Password" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Confirm New Password" required>
                        </div>

                        <div class="d-flex justify-content-end gap-2" style="margin-top:25px;">
                            <button type="button" class="btn btn-secondary prevBtn"
                                style="width: 150px;">Back</button>
                            <button type="submit" class="btn btn-primary" style="width: 150px;">Register</button>
                        </div>
                    </div>
                </form>

                <div class="text-center" style="margin-top:35px">
                    <p>Already have an Account? <a class="link" href="{{ url('/login') }}">Sign in here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var currentStep = 1;

            $(".nextBtn").click(function() {
                if (currentStep === 1) {
                    if ($('#first_name').val() === '' || $('#last_name').val() === '') {
                        alert('Please fill in all required fields');
                        return false;
                    }
                } else if (currentStep === 2) {
                    if ($('#birth_date').val() === '' || $('#contact_no').val() === '') {
                        alert('Please fill in all required fields');
                        return false;
                    }
                }

                currentStep++;
                showStep(currentStep);
            });

            $(".prevBtn").click(function() {
                currentStep--;
                showStep(currentStep);
            });

            function showStep(step) {
                $("#step1, #step2, #step3").hide();
                $("#step" + step).show();

                $(".progress-bar-step").removeClass("completed");
                for (var i = 1; i <= step; i++) {
                    $("#step-indicator-" + i).addClass("completed");
                }

                var progress = ((step - 1) / 2) * 100; // Calculate progress percentage
                $(".progress-bar").css("width", progress + "%");
            }
        });
    </script>
@endsection
