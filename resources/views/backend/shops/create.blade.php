@extends('layouts.auth_admin_app')

@section('title', 'إنشاء عرض جديد')

@section('content')

    <div class="container">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h3 class="card-title card-title-new"> اضافة عرض جديد</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.shops.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">القائمة</span>
                </a>
            </div>
        </div>
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <form action="{{ route('admin.shops.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div>
                    <div class="card-body row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>اسم العرض</label>
                                <input name="name" type="text" class="form-control" placeholder="اختياري" value="{{ old('name') }}">
                                @error('name') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <label for="active">حالة العرض</label>
                            <select name="active" class="form-control">
                                <option value="1" {{ old('active') == 1 ? 'selected' : null }}>نشط</option>
                                <option value="0" {{ old('active') == 0 ? 'selected' : null }}>غير نشط</option>
                            </select>
                            @error('active')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-3">
                            <label for="free">مجاني</label>
                            <select name="free" class="form-control">
                                <option value="1" {{ old('free') == 1 ? 'selected' : null }}>مجاني</option>
                                <option value="0" {{ old('free') == 0 ? 'selected' : null }}>مدفوع</option>
                            </select>
                            @error('free')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>عدد التوكن بالعرض</label>
                                <input name="win_tokens" type="number" class="form-control"
                                       min="1.00" step="1.00"  value="{{ old('win_tokens') }}">
                                @error('win_tokens') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>تكلفة العرض</label>
                                <input name="cost" type="number" class="form-control"
                                       min="0.00" step="0.01"  value="{{ old('cost') }}">
                                @error('cost') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>وقت وتاريخ البداية</label>
{{--                                <input name="start" type="datetime-local" class="form-control" value="{{ old('start', \Carbon\Carbon::parse(now())) }}">--}}
                                <input name="start" type="datetime-local" class="form-control" value="{{ old('start') }}">
                                @error('start') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>وقت وتاريخ النهاية</label>
{{--                                <input name="end" type="datetime-local" class="form-control"  value="{{ old('end', \Carbon\Carbon::parse(now())) }}">--}}
                                <input name="end" type="datetime-local" class="form-control" value="{{ old('end') }}">
                                @error('end') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="image">صورة العرض</label>
                                <input type="file" name="image" id="category_image" class="file-input-overview">
                                <span class="form-text text-muted">Image Width Should be (500px) X (500px)</span>
                                @error('image')<span class="text-danger">{{ $message }}</span>@enderror
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

@section('script')
    <script src="{{ asset('backend/vendor/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            $(".select2").select2({
                tags:true,
                closeOnSelect: false,
                minimumResultForsearch: Infinity
            });

            $('#category_image').fileinput({
                theme: "fas",
                maxFileCount: 1,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
            });
        });
    </script>
@endsection
