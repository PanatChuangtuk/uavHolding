<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ URL::asset('') }}administrator/assets" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login | UAV</title>

    <meta name="description" content="" />
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" /> -->

    <!-- Favicon -->
    {{-- <link rel="icon" type="image/x-icon" href="{{ URL::asset('') }}administrator/assets/img/favicon/favicon.ico" /> --}}
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('administrator') }}/assets/img/gas_logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ URL::asset('') }}administrator/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ URL::asset('') }}administrator/assets/vendor/css/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ URL::asset('') }}administrator/assets/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ URL::asset('') }}administrator/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ URL::asset('') }}administrator/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ URL::asset('') }}administrator/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="{{ URL::asset('') }}administrator/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ URL::asset('') }}administrator/assets/js/config.js"></script>
    <link rel="stylesheet" href="{{ URL::asset('vendor') }}/sweetalert2/sweetalert2.min.css" />
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    {{-- <img src="{{ URL::asset('administrator') }}/assets/img/logo.png" width="40" alt=""> --}}
                                </span>
                                <span class="app-brand-text demo text-body fw-bolder text-capitalize"> UAV </span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2">Administrator! 👋</h4>
                        <p class="mb-4">Please sign-in to your account.</p>
                        @if (Session::has('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <form action="{{ route('administrator.login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email or Username</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email or username" autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <a href="auth-forgot-password-basic.html">
                                    <small>Forgot Password?</small>
                                </a>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me"
                                        name="remember_token" />
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit"> Log in </button>
                            </div>
                        </form>

                        {{-- <p class="text-center">
                <span>New on our platform?</span>
                <a href="auth-register-basic.html">
                <span>Create an account</span>
                </a>
            </p> --}}
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ URL::asset('') }}administrator/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ URL::asset('') }}administrator/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ URL::asset('') }}administrator/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ URL::asset('') }}administrator/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{ URL::asset('') }}administrator/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ URL::asset('') }}administrator/assets/js/main.js"></script>


</body>

</html>
