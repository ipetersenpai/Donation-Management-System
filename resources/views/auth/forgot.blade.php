
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

        .login-form {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7 left-side">
                <div>
                    <h1>Forgot your Password?</h1>
                    <p>Don't worry We will help you recover your password.</p>
                </div>
            </div>
            <div class="col-md-5 right-side">
                <div class="login-form">
                    <div class="text-center mb-5 pb-1" style="color:#800000; font-weight:bold">
                        <img src="{{ asset('assets/Logo.png') }}" style="width: 105px;" alt="logo">
                        <h4 class="mt-2">CARITAS TARLAC INC.</h4>
                        <p style="color: black; margin-top:-0.5rem">DONATION MANAGEMENT SYSTEM</p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <p style="font-weight: bold; font-size: 18px">Please Enter your Email Address</p>

                        <div data-mdb-input-init class="form-outline mb-2">
                            <input type="email" class="form-control" id="email" name="email" required
                                placeholder="Email Address" />
                            <label for="email" class="form-label mb-4" style="font-size: 14px">Email Address</label>
                        </div>

                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger text-center">
                            {{ $error }}
                        </div>
                    @endforeach
                        @if (session('status'))
                            <div class="alert alert-success text-center">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="text-center pt-1 mb-5 pb-1">
                            <button class="btn btn-primary mb-3 w-100" type="submit">Send Link</button>
                            <p>Remember your password? <a class="text-muted" href="{{ env('BASE_URL') }}/auth/login/">Login Here</a></p>
                        </div>

                        <div class="d-flex align-items-center justify-content-center pb-4">
                            <p class="mb-0 me-2">Don't have an account?</p>
                            <a type="button" href="{{ env('BASE_URL') }}/auth/register" data-mdb-ripple-init
                                class="btn btn-outline-danger">Create new</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
