@extends('layouts.auth_admin_app')

@section('title', 'تعديل لعبة')

@section('content')

    <div class="container">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h3 class="card-title card-title-new"> تعديل لعبة ( 8 رقم )</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.nine-games.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">القائمة</span>
                </a>
            </div>
        </div>
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <form action="{{ route('admin.nine-games.update', $nineGame->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div>
                    <div class="card-body row">
                        <div class="col-6">
                            <h1 class="text-center" style="text-decoration: underline;padding-bottom: 20px;">بيانات
                                اللعبة</h1>
                            <div class="form-group">
                                <label>عدد الأرقام الفائزة</label>
                                <input name="no_of_win_numbers" type="text" value="{{ old('no_of_win_numbers', $nineGame->no_of_win_numbers ) }}"
                                       class="form-control">
                                @error('no_of_win_numbers') <span
                                    style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>الأرقام الفائزة</label>
                                <label class="mr-2" style="color:#3699ff">( يرجى ادخال الارقام والفصل بينهم بفاصلة مثل
                                    1,2,3 )</label>
                                <input name="win_numbers" value="{{ old('win_numbers', implode(', ', $nineGame->win_numbers) ) }}"
                                       type="text" class="form-control">
                                @error('win_numbers') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label> الوقت المحدد للمرحلة</label>
                                <label class="mr-2" style="color: #3699ff">( إذا تخطي اللاعب هذا الوقت سيخسر اللعبة )</label>

                                <input name="timer" type="text" value="{{ old('timer', $nineGame->timer ) }}" class="form-control">
                                @error('timer') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وقت وتاريخ البداية</label>
{{--                                <label class="mr-2" style="color: #3699ff">( يجب ان يكون وقت وتاريخ البداية بعد الوقت الحالي )</label>--}}
                                <input name="start" type="datetime-local" value="{{ old('start', $nineGame->start ) }}"
                                       class="form-control">
                                @error('start') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وقت وتاريخ النهاية</label>
                                <label class="mr-2" style="color: #3699ff">( يجب ان يكون وقت وتاريخ النهاية بعد وقت وتاريخ البداية )</label>
                                <input name="end" type="datetime-local" value="{{ old('end', $nineGame->end ) }}" class="form-control">
                                @error('end') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>الحالة</label>
                                <select name="active" class="form-control">
                                    <option value="1" {{ old('active',  $nineGame->active) == 1 ? 'selected' : null }}>نشط</option>
                                    <option value="0" {{ old('active',  $nineGame->active) == 0 ? 'selected' : null }}>غير نشط</option>
                                </select>
                                @error('active')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>الصورة</label>
                                <div class="text-center">
                                    @if( $nineGame->image != '')
                                        <img height="120" width="150" src="{{ asset($nineGame->image) }}">
                                    @else
                                        <img height="120" width="150" src="{{ asset('images/no-image.png') }}">
                                    @endif
                                </div>
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
                                    <input name="win_tokens" type="number" min="1" value="{{ old('win_tokens', $nineGame->basicPrice()->win_tokens) }}" class="form-control">
                                </div>
                                @error('win_tokens') <span style="color: red">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>اسم الجائزة</label>
                                <input name="price_name" type="text" value="{{ old('price_name', $nineGame->basicPrice()->name ) }}" class="form-control">
                                @error('price_name') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>سعر الجائزة</label>
                                <input name="price_value" type="number" value="{{ old('price_value', $nineGame->basicPrice()->value ) }}" class="form-control"
                                       min="0.00" step="0.01">
                                @error('price_value') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وصف الجائزة</label>
                                <textarea name="price_description" class="form-control" rows="5">{!! old('price_description', $nineGame->basicPrice()->description ) !!}</textarea>
                                @error('price_description') <span
                                    style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>صورة الجائزة</label>
                                <div class="text-center">
                                    @if( $nineGame->basicPrice()->image != '')
                                        <img height="120" width="150" src="{{ asset($nineGame->basicPrice()->image) }}">
                                    @else
                                        <img height="120" width="150" src="{{ asset('images/no-image.png') }}">
                                    @endif
                                </div>
                                <input name="price_image" type="file"
                                       class="form-control-plaintext text-muted">
                                @error('price_image') <span
                                    style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="form-group pt-4 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">تعديل البيانات</button>
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
