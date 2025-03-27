@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Sign Up</h3>
                        <form id="signupForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    placeholder="Enter Your Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" id="email"
                                    placeholder="Enter Your Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input class="form-control" type="password" name="password" id="password"
                                    placeholder="Enter Your Password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input class="form-control" type="password" name="password_confirmation"
                                    id="password_confirmation" placeholder="Confirm Password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="signUpButton">Sign Up</button>
                            </div>
                        </form>
                        <div id="responseMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#signupForm').on('submit', function(event) {
                event.preventDefault();
                $('#responseMessage').html(''); // old message delete

                let formData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val(),
                };

                $.ajax({
                    url: "{{ url('/api/register') }}", // API Endpoint
                    type: "POST",
                    data: JSON.stringify(formData),
                    contentType: "application/json",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    success: function(response) {
                        window.location.href = "/";
                        // $('#responseMessage').html('<div class="alert alert-success">Registration successful!</div>');
                    },
                    error: function(xhr) {
                        let response = xhr.responseJSON;
                        let errorMessages = '<div class="alert alert-danger">';

                        if (xhr.status === 422) { // Validation error
                            $.each(response.errors, function(key, value) {
                                errorMessages += '<p>' + value[0] + '</p>';
                            });
                        } else if (xhr.status === 400) { // Custom error message
                            errorMessages += '<p>' + response.message + '</p>';
                        } else {
                            errorMessages += '<p>Something went wrong. Please try again.</p>';
                        }

                        errorMessages += '</div>';
                        $('#responseMessage').html(errorMessages);
                    }
                });
            });
        });
    </script>

@endsection
