@extends('layouts.app')

@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="my-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="240" height="240" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16" style="color:#3bce62">
                                <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0"/>
                                <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                              </svg>
                        </div>
                        <h2 class="card-title mb-4">Payment Successful!</h2>
                        <p class="card-text mb-4">
                            Thank you so much for your generous donation! Your kindness and support mean the world to us and will make a significant impact in the lives of those we help.
                        </p>
                        <p class="card-text text-muted">
                            A Copy of receipt will be sent to your registered email address.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
