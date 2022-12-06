<!DOCTYPE html>

<html lang="en">

<head>

    @include('layouts.backend.head')

    @livewireStyles
    @yield('style')

</head>

<!--begin::Body-->

<body id="kt_body"
    class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed
    aside-minimize-hoverable page-loading">
    <!-- Sidebar -->
    @include('layouts.backend.fixed_body')
    <!-- End of Sidebar -->

    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">

            <!-- Sidebar -->
            @include('layouts.backend.sidebar')
            <!-- End of Sidebar -->

            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

                <!-- Topbar -->
                @include('layouts.backend.topbar')
                <!-- End of Topbar -->

                @include('layouts.backend.flash')


                @yield('content')

                <!-- Footer -->
                @include('layouts.backend.footer')
                <!-- End of Footer -->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->

    @include('layouts.backend.footer_script')
    @yield('script')
    
    @livewireScripts
    @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])



</body>

</html>
