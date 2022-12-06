<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    {{-- <base href="../../../../"> --}}
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>4FARH | Forget Password</title>
    <meta name="description" content="Login page example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />
    <link rel="shortcut icon" href="{{ asset('backend/favicon.ico') }}" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

    <link href="{{ asset('backend/assets/css/pages/login/classic/login-5.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('backend/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

</head>

<body id="kt_body"
    class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-5 login-signin-on d-flex flex-row-fluid" id="kt_login">
            <div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid"
                style="background-image: url({{ asset('backend/assets/media/bg/bg-2.jpg') }});">
                <div class="login-form text-center text-white p-7 position-relative overflow-hidden">

                    <!--begin::Login Header-->
                    <div class="d-flex flex-center mb-15">
                        <a href="#">
                            <img src="{{ asset('backend/4FARH_Logo_H.png') }}"
                            class="max-h-75px" alt="" />
                        </a>
                    </div>
                    <!--end::Login Header-->

                    <!--begin::Login Sign in form-->
                    <div class="login-signin">
                        <div class="mb-20">
                            <h3 class="opacity-40 font-weight-normal">Forgot Your Password ?</h3>
                            <p class="opacity-40">We get it, stuff happens. Just enter your email
                                address below
                                and we'll send you a link to reset your password!</p>
                        </div>

                        <form method="POST" action="{{ route('password.email') }}" class="user">
                        @csrf
                            <div class="form-group">
                                <input type="email" value="{{ old('email') }}"
                                    class="form-control h-auto text-white bg-white-o-5
                                    rounded-pill border-0 py-4 px-8 @error('email') is-invalid @enderror"
                                    placeholder="Enter Email Address..." required>
                                @error('email') <span
                                    class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <button type="submit" name="submit"
                                class="btn btn-pill btn-primary opacity-90 px-15 py-3">
                                Reset Password
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="{{ route('admin.login') }}">Already have
                                an account? Login!</a>
                        </div>
                    </div>
                    <!--end::Login Sign in form-->
                </div>
            </div>
        </div>
        <!--end::Login-->

    </div>
    <!--end::Main-->

</body>
<!--end::Body-->

</html>
