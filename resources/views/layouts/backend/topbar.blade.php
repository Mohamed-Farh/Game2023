<!--begin::Header-->
<div id="kt_header" class="header header-fixed">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Header Menu Wrapper-->
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
            <!--begin::Header Menu-->
            <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                <!--begin::Header Nav-->

                <ul class="menu-nav">
                    <li class="menu-item menu-item-submenu menu-item-rel menu-item-active">
                        <a class="menu-link menu-toggle">
                            <span class="menu-text">{{ config('app.name') }}</span>
                            <i class="menu-arrow"></i>
                        </a>
                    </li>
                </ul>
                <!--end::Header Nav-->
            </div>
            <!--end::Header Menu-->
        </div>
        <!--end::Header Menu Wrapper-->
        <!--begin::Topbar-->
        <div class="topbar">
            @if(auth()->user()->ability('superAdmin', 'manage_users,show_users'))
                <div class="topbar-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                        المستخدمين
                    </a>
                </div>
            @endif
            @if(auth()->user()->ability('superAdmin', 'manage_admins,show_admins'))
                <div class="topbar-item">
                    <a class="nav-link" href="{{ route('admin.admins.index') }}">
                        الأدمن
                    </a>
                </div>
            @endif

            <!--begin::User-->
            <div class="topbar-item">
                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">مرحباً,</span>
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ Auth::user()->first_name }}</span>
                    <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
                        <span class="symbol-label font-size-h5 font-weight-bold">{{ substr( Auth::user()->first_name, 0, 1) }}</span>
                    </span>
                </div>
            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>
<!--end::Header-->
