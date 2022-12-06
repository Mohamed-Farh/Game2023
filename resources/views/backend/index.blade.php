@extends('layouts.auth_admin_app')

@section('title', 'Dashboard')

@section('content')


    @php

        $playerCount = \App\Models\User::whereHas('roles', function($query){
            $query->where('name', 'player');
        })->count();

        $hundredCount = \App\Models\HundredGame::count();
        $nineCount = \App\Models\NineGame::count();
        $oneCount = \App\Models\LoseNumberGame::count();

    @endphp

    <style>
        .text-muted {
            color: #b5b5c3!important;
            font-size: x-large;
        }
    </style>
        <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">

        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Dashboard-->
                <div class="row">

                    <div class="col-lg col-xxl">
                        <!--begin::Stats Widget 12-->
                        <div class="card card-custom card-stretch card-stretch-half gutter-b">
                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <div class="row d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                    <div class="col-6 text-left">
                                        <span class="symbol symbol-50 symbol-light-primary mr-2">
                                            <span class="symbol-label">
                                                <span class="svg-icon svg-icon-xl svg-icon-primary">
                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                                    <i class="fas fa-gamepad"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $hundredCount }}</span>
                                    </div>
                                    <div class="col-12 d-flex flex-column text-center pt-2">
                                        <span class="text-muted font-weight-bold mt-2">لعبة (100) رقم</span>
                                    </div>
                                </div>
                                <div class="card-rounded-bottom" data-color="primary" style="height: 150px"></div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 12-->

                        <!--begin::Stats Widget 11-->
                        <div class="card card-custom card-stretch card-stretch-half gutter-b">
                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <div class="row d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                    <div class="col-6 text-left">
                                        <span class="symbol symbol-50 symbol-light-success mr-2">
                                            <span class="symbol-label">
                                                <span class="svg-icon svg-icon-xl svg-icon-success">
                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                                                    <i class="fas fa-user-tie"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $playerCount }}</span>
                                    </div>
                                    <div class="col-12 d-flex flex-column text-center pt-2">
                                        <span class="text-muted font-weight-bold mt-2">اللاعبين</span>
                                    </div>
                                </div>
                                <div class="card-rounded-bottom" data-color="success" style="height: 150px">
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 11-->
                    </div>

                    <div class="col-lg col-xxl">
                        <!--begin::Stats Widget 12-->
                        <div class="card card-custom card-stretch card-stretch-half gutter-b">
                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <div class="row d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                    <div class="col-6 text-left">
                                        <span class="symbol symbol-50 symbol-light-primary mr-2">
                                            <span class="symbol-label">
                                                <span class="svg-icon svg-icon-xl svg-icon-primary">
                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                                    <i class="fas fa-gamepad"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $nineCount }}</span>
                                    </div>
                                    <div class="col-12 d-flex flex-column text-center pt-2">
                                        <span class="text-muted font-weight-bold mt-2">لعبة (9) رقم</span>
                                    </div>
                                </div>
                                <div class="card-rounded-bottom" data-color="primary" style="height: 150px"></div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 12-->

                        <!--begin::Stats Widget 11-->
                        <div class="card card-custom card-stretch card-stretch-half gutter-b">
                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <div class="row d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                    <div class="col-6 text-left">
                                        <span class="symbol symbol-50 symbol-light-success mr-2">
                                            <span class="symbol-label">
                                                <span class="svg-icon svg-icon-xl svg-icon-success">
                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                                                    <i class="fas fa-user-tie"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $hundredCount }}</span>
                                    </div>
                                    <div class="col-12 d-flex flex-column text-center pt-2">
                                        <span class="text-muted font-weight-bold mt-2">اللاعبين</span>
                                    </div>
                                </div>
                                <div class="card-rounded-bottom" data-color="success" style="height: 150px">
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 11-->
                    </div>

                    <div class="col-lg col-xxl">
                        <!--begin::Stats Widget 12-->
                        <div class="card card-custom card-stretch card-stretch-half gutter-b">
                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <div class="row d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                    <div class="col-6 text-left">
                                        <span class="symbol symbol-50 symbol-light-primary mr-2">
                                            <span class="symbol-label">
                                                <span class="svg-icon svg-icon-xl svg-icon-primary">
                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                                    <i class="fas fa-gamepad"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $oneCount }}</span>
                                    </div>
                                    <div class="col-12 d-flex flex-column text-center pt-2">
                                        <span class="text-muted font-weight-bold mt-2">لعبة (1) رقم</span>
                                    </div>
                                </div>
                                <div class="card-rounded-bottom" data-color="primary" style="height: 150px"></div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 12-->

                        <!--begin::Stats Widget 11-->
                        <div class="card card-custom card-stretch card-stretch-half gutter-b">
                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <div class="row d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                    <div class="col-6 text-left">
                                        <span class="symbol symbol-50 symbol-light-success mr-2">
                                            <span class="symbol-label">
                                                <span class="svg-icon svg-icon-xl svg-icon-success">
                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                                                    <i class="fas fa-user-tie"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="text-dark-75 font-weight-bolder font-size-h3">{{ $hundredCount }}</span>
                                    </div>
                                    <div class="col-12 d-flex flex-column text-center pt-2">
                                        <span class="text-muted font-weight-bold mt-2">اللاعبين</span>
                                    </div>
                                </div>
                                <div class="card-rounded-bottom" data-color="success" style="height: 150px">
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 11-->
                    </div>

                </div>
                <!--end::Dashboard-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->


@endsection
