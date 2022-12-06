@extends('layouts.auth_admin_app')

@section('title', 'حول التطبيق')


@section('content')

    <div class="container">
        <div class="row ">
            @foreach ($abouts as $about)
                <div class="col-6 d-flex text-left">
                    <h1 class=" text-left">حول التطبيق</h1>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    @ability('superAdmin', 'manage_abouts,create_abouts')
                    <a href="{{ route('admin.abouts.edit', $about->id) }}" class="btn btn-primary font-weight-bolder">
                        <span class="svg-icon svg-icon-md">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <circle fill="#000000" cx="9" cy="15" r="6" />
                                    <path
                                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                        fill="#000000" opaabout="0.3" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        تعديل
                    </a>
                    @endability
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            {!! $about->about !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection

