{{-- @extends('admin.pages.auth.layout')
@section('content')
    <div class="d-flex flex-column flex-root">
        <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
            <div class="login-aside d-flex flex-column flex-row-auto cw-40" style="background-color: #F2C98A;  ">
                <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
                    <h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg">Using data to your Advantage
                    </h3>
                    <h3 class="text-center font-size-h4" style="color: #986923;">
                        Organization which use advance analytics <br> see a 60% higher increase in year-on-year <br>
                        revenue than those that don't
                    </h3>
                </div>
                <div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center"
                    style="background-image: url({{ asset('/media/custom/login-visual-1.svg') }});"></div>
</div>
<div
    class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
    <div class="d-flex flex-column-fluid flex-center">
        <div class="login-form login-signin">
            <form class="form" novalidate="novalidate" id="kt_login_signin_form"
                action="{{ url('admin/auth/login') }}" method="post">
                {{ csrf_field() }}
                <div class="pb-13 pt-lg-0 pt-5">
                    <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">
                        {{ config('app.name') }}
                    </h3>
                    <span class="text-muted font-size-h4">The best in class give their entire team
                        <br>visibility our business intelligence
                    </span>
                </div>
                <div class="form-group">
                    @error('email')
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    @error('password')
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    @error('error')
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                    @if (request('error'))
                    <div class="alert alert-danger" role="alert">
                        <strong>{{ request('error') }}</strong>
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="font-size-h6 font-weight-bolder text-dark">Email</label>
                    <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="text"
                        name="email" autocomplete="off" isrequired="isrequired" />
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between mt-n5">
                        <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
                    </div>
                    <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="password"
                        name="password" autocomplete="off" isrequired="isrequired" />
                </div>
                <div class="pb-lg-0 pb-5">
                    <button type="submit" id="kt_login_signin_submit"
                        class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign
                        In</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@section('styles')
<style>
    .cw-40 {
        width: 40%;
    }

    @media only screen and (max-width: 900px) {
        .cw-40 {
            width: 100%;
            padding-bottom: 40px;
        }
    }
</style>
@endsection
@section('scripts')
<script src="{{ url('/') }}/public/js/custom.js" type="text/javascript"></script>
@endsection
--}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #55917f;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            width: 800px;
            height: 500px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .left {
            flex: 1;
            background-color: #143b69;
            color: #fff;
            padding: 170px 40px;
            position: relative;
        }

        .left h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .left p {
            font-size: 16px;
            opacity: 0.9;
        }

        .right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form {
            width: 100%;
            max-width: 300px;
        }

        .login-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .login-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .options a {
            color: #6f42c1;
            text-decoration: none;
        }

        .login-form button {
            width: 100%;
            padding: 12px;
            background: #143b69;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-form button:hover {
            background: #55917f;
        }

        .signup {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .signup a {
            color: #6f42c1;
            text-decoration: none;
        }

        @media (max-width: 991.98px) {
            .container {
                flex-direction: column;
                width: 90%;
            }

            .left,
            .right {
                width: 100%;
                padding: 2rem 1rem;
            }

            .left {
                order: 1;
                text-align: center;
                border-bottom: 1px solid #ccc;
            }

            .right {
                order: 2;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <h2>Welcome back!</h2>
            <p>You can sign in to access with your existing account.</p>
        </div>
        <div class="right">
            <form class="login-form" action="{{ url('admin/auth/login') }}" method="POST">
                <h2 style="color:#f7cd46;">Krishna Trading Co.</h2>
                @csrf
                <h2>Sign In</h2>
                <input type="text" placeholder="Email" name="email" required />
                <input type="password" placeholder="Password" name="password" required />
                <div class="options">
                    <label><input type="checkbox" style="width:unset;" /> Remember me</label>
                </div>
                <button type="submit">Sign In</button>
            </form>
            @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
            @endif
        </div>
    </div>
</body>

</html>