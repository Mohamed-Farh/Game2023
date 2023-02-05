@extends('layouts.auth_admin_app')

@section('title', 'إنشاء لعبة جديدة')

@section('content')

    <div class="container">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h3 class="card-title card-title-new"> اضافة لعبة جديدة ( الرقم الخاسر )</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.lose-number-games.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">القائمة</span>
                </a>
            </div>
        </div>
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <form action="{{ route('admin.lose-number-games.store') }}" method="post" enctype="multipart/form-data" id="forma">
                @csrf
                <div>
                    <div class="card-body row">
                        <div class="col-6">
                            <h1 class="text-center" style="text-decoration: underline;padding-bottom: 20px;">بيانات
                                اللعبة</h1>
                            <div class="form-group">
                                <label>الرقم الخاسر</label>
                                <input name="lose_number"  value="{{ old('lose_number') }}"
                                       type="number" class="form-control" min="1" max="9">
                                @error('lose_number') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label> الوقت المحدد للمرحلة</label>
                                <label class="mr-2" style="color: #3699ff">( إذا تخطي اللاعب هذا الوقت سيخسر اللعبة )</label>

                                <input name="timer" type="text"  value="{{ old('timer', '00:01:00') }}" class="form-control">
                                @error('timer') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وقت وتاريخ البداية</label>
                                <label class="mr-2" style="color: #3699ff">( يجب ان يكون وقت وتاريخ البداية بعد الوقت الحالي )</label>
                                <input name="start" type="datetime-local" value="{{ old('start') }}"
                                       class="form-control">
                                @error('start') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وقت وتاريخ النهاية</label>
                                <label class="mr-2" style="color: #3699ff">( يجب ان يكون وقت وتاريخ النهاية بعد وقت وتاريخ البداية )</label>
                                <input name="end" type="datetime-local" value="{{ old('end') }}" class="form-control">
                                @error('end') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>الصورة</label>
                                <input name="image" type="file"
                                       class="form-control-plaintext text-muted">
                            </div>
                        </div>
                        <div class="col-1 block"></div>
                        <div class="col-5">
                            <h1 class="text-center" style="text-decoration: underline;padding-bottom: 20px;">بيانات
                                الجائزة</h1>


                            <div class="form-group row price_token">
                                <div class="col-3">
                                    <label style="font-weight: bolder;">جائزة التوكن</label>
                                </div>
                                <div class="col-9">
                                    <input name="win_tokens" type="number" min="1" value="{{ old('win_tokens') }}" class="form-control">
                                </div>
                                @error('win_tokens') <span style="color: red">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>اسم الجائزة</label>
                                <input name="price_name" type="text" value="{{ old('price_name') }}" class="form-control">
                                @error('price_name') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>سعر الجائزة</label>
                                <input name="price_value" type="number" value="{{ old('price_value') }}" class="form-control"
                                       min="0.00" step="0.01">
                                @error('price_value') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وصف الجائزة</label>
                                <textarea name="price_description" class="form-control" rows="5">{{ old('price_description') }}</textarea>
                                @error('price_description') <span
                                    style="color: red">{{ $message }}</span> @enderror
                            </div>
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
                        <button type="submit" name="submit" class="btn btn-primary btn-submit">حفظ البيانات</button>
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

