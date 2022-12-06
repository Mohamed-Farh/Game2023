@extends('layouts.auth_admin_app')

@section('title', 'إنشاء جائزة جديدة')

@section('content')

    <div class="container">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h3 class="card-title card-title-new"> اضافة جائزة جديدة</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.nine-games.showPrices', $nineGame->id) }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">القائمة</span>
                </a>
            </div>
        </div>
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <form action="{{ route('admin.nine-games-prices.store', ['nineGame' => $nineGame]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div>
                    <div class="card-body row">
                        <div class="col-9">
                            <div class="form-group">
                                <label>اسم الجائزة</label>
                                <input name="price_name" type="text" class="form-control">
                                @error('price_name') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>سعر الجائزة</label>
                                <input name="price_value" type="number" class="form-control"
                                       min="0.00" step="0.01">
                                @error('price_price') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>وصف الجائزة</label>
                                <textarea name="price_description" class="form-control"></textarea>
                                @error('price_description') <span
                                    style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>وقت وتاريخ البداية</label>
                                <input name="start" type="datetime-local"
                                       class="form-control">
                                @error('start') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>وقت وتاريخ النهاية</label>
                                <input name="end" type="datetime-local" class="form-control">
                                @error('end') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>صورة الجائزة</label>
                                <input name="price_image" type="file"
                                       class="form-control-plaintext text-muted">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="card-footer">
                    <div class="form-group pt-4 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>
                </div>

            </form>
            <!--end::Form-->

        </div>
    </div>

    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

@endsection
