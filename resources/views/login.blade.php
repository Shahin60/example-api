@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container mt-5">
        <div class="form-control w-50 mx-auto">
            <div class="mb-2">
                <label for=" email">Email</label>
                <input class="form-control" type="text" name="email" id="email" placeholder="Enter Your Email">
            </div>
            <div class="mb-2">
                <label for="password">Password</label>
                <input class="form-control" type="password" name="password" id="password"
                    placeholder="Enter Your Password">
            </div>
            <div class="mb-2">
                <button id="loginButton" class="btn btn-primary">Login</button>
                <span> <a href="{{ route('register')  }}">Don't have on account?</a></span>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#loginButton').on('click', function() {
                const email = $('#email').val();
                const password = $('#password').val();

                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        email: email,
                        password: password
                    }),
                    success: function(response) {
                        // console.log(response);

                        localStorage.setItem('api_token', response.token);
                        window.location.href = "/index";

                    },
                    error: function(xhr, status, error) {
                        alert('Error:' + xhr.responseText);
                    }

                })
            })
        });
    </script>
@endsection
