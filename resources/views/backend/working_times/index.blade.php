@extends('layouts.auth_admin_app')

@section('title', 'اوقات العمل')

@section('style')
    <style>
        table.dataTable tbody td.select-checkbox:before,
        table.dataTable tbody th.select-checkbox:before {
            content: " ";
            margin-top: 22px;
            margin-left: 0;
            border: 1px solid darkblue;
            border-radius: 3px;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div class="row ">
            <div class="col-6 d-flex text-left">
                <h1 class=" text-left">اوقات العمل</h1>
            </div>
            <div class="col-6 d-flex justify-content-end">
                @ability('superAdmin', 'manage_Settings,show_working_times')
                <a href="{{ route('admin.working_times.create') }}" class="btn btn-primary font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path
                                    d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                    fill="#000000" opaemail="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                    عنصر جديد
                </a>
                @endability
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card-body align-items-center">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-hover table-striped table-light yajra-datatable">
                    <thead class="table-dark ">
                        <tr class="text-light">
                            <th class="text-light">No</th>
                            <th class="text-light">اليوم</th>
                            <th class="text-light">وقت البداية</th>
                            <th class="text-light">وقت النهاية</th>
                            @ability('superAdmin', 'manage_Settings,show_working_times')
                                <th class="text-light">الحالة</th>
                                <th class="text-light">العمليات</th>
                            @endability
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workingTimes as $k => $workingTime)
                            <tr data-entry-id="{{ $workingTime->id }}">
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $workingTime->day }}</td>
                                <td>{{ $workingTime->start }}</td>
                                <td>{{ $workingTime->end }}</td>
                                @ability('superAdmin', 'manage_Settings,show_working_times')
                                    <td class="text-center">
                                        <span class="switch switch-icon">
                                            <label>
                                                <input data-id="{{ $workingTime->id }}" class="status-class" type="checkbox"
                                                    data-onstyle="success" data-offstyle="danger" data-toggle="toggle"
                                                    data-on="On" data-width="40" data-height="30" data-off="Off"
                                                    {{ $workingTime->status == 1 ? 'checked' : '' }}>
                                                <span></span>
                                            </label>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <div style="display: flex" class="text-center justify-content-between">
                                            <a href="{{ route('admin.working_times.edit', $workingTime->id) }}"
                                                class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i>
                                            </a>

                                            <a href="javascript:void(0)" onclick="
                                                                if (confirm('Are You Sure You Want To Delete This Record ?') )
                                                                    { document.getElementById('record_delete_{{ $workingTime->id }}').submit(); }
                                                                else
                                                                    { return false; }" class="btn btn-danger"><i
                                                    class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                        <form action="{{ route('admin.working_times.destroy', $workingTime->id) }}" method="post"
                                            id="record_delete_{{ $workingTime->id }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                @endability
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
                    {!! $workingTimes->appends(request()->input())->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

    <script type="text/javascript">
        $(function() {
            let languages = {
                'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
            };
            var table = $('.yajra-datatable').DataTable({
                language: {
                    url: languages['{{ app()->getLocale() }}']
                },
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, {
                    orderable: false,
                    searchable: false,
                    targets: -1
                }],
                select: {
                    style: 'multi+shift',
                    selector: 'td:first-child'
                },
                order: [],
                scrollX: false,
                dom: 'lBfrtip<"actions">',
            });
        });
    </script>


    <script>
        $(function () {
            $('.status-class').change(function() {
                console.log("success");
                var status = $(this).prop('checked') == true ? 1 : 0;
                var cat_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('admin.working_times.changeStatus') }}',
                    data: {
                        'status': status,
                        'cat_id': cat_id
                    },
                    success: function(data) {
                        Swal.fire({
                            title: 'Status Change Successfully',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        })
                    }
                });
            })
        });
    </script>

@endsection

