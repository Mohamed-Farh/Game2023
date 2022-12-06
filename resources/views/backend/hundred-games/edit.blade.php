@extends('layouts.auth_admin_app')

@section('title', 'تعديل لعبة')

@section('content')

    <div class="container">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h3 class="card-title card-title-new"> تعديل لعبة ( 100 رقم )</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.hundred-games.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">القائمة</span>
                </a>
            </div>
        </div>
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <form action="{{ route('admin.hundred-games.update', $hundredGame->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div>
                    <div class="card-body row">
                        <div class="col-6">
                            <h1 class="text-center" style="text-decoration: underline;padding-bottom: 20px;">بيانات
                                اللعبة</h1>
                            <div class="form-group">
                                <label>عدد الأرقام الفائزة</label>
                                <input name="no_of_win_numbers" type="text" value="{{ old('no_of_win_numbers', $hundredGame->no_of_win_numbers ) }}"
                                       class="form-control">
                                @error('no_of_win_numbers') <span
                                    style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>الأرقام الفائزة</label>
                                <label class="mr-2" style="color:green">يرجى ادخال الارقام والفصل بينهم بفاصلة مثل
                                    1,2,3</label>
                                <input name="win_numbers" value="{{ old('win_numbers', implode(', ', $hundredGame->win_numbers)) }}"
                                       type="text" class="form-control">
                                @error('win_numbers') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label> الوقت المحدد للمرحلة</label>
                                <label class="mr-2" style="color: green"> يتم ادخال الوقت بالساعات</label>

                                <input name="timer" type="text" value="{{ old('timer', $hundredGame->timer ) }}" class="form-control">
                                @error('timer') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وقت وتاريخ البداية</label>
                                <input name="start" type="datetime-local" value="{{ old('start', $hundredGame->start ) }}"
                                       class="form-control">
                                @error('start') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وقت وتاريخ النهاية</label>
                                <input name="end" type="datetime-local" value="{{ old('end', $hundredGame->end ) }}" class="form-control">
                                @error('end') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>الحالة</label>
                                <select name="active" class="form-control">
                                    <option value="1" {{ old('active',  $hundredGame->active) == 1 ? 'selected' : null }}>نشط</option>
                                    <option value="0" {{ old('active',  $hundredGame->active) == 0 ? 'selected' : null }}>غير نشط</option>
                                </select>
                                @error('active')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>الصورة</label>
                                <div class="text-center">
                                    @if( $hundredGame->image != '')
                                        <img height="120" width="150" src="{{ asset($hundredGame->image) }}">
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
                            <div class="form-group">
                                <label>اسم الجائزة</label>
                                <input name="price_name" type="text" value="{{ old('price_name', $hundredGame->basicPrice()->name ) }}" class="form-control">
                                @error('price_name') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>سعر الجائزة</label>
                                <input name="price_value" type="number" value="{{ old('price_name', $hundredGame->basicPrice()->value ) }}" class="form-control"
                                       min="0.00" step="0.01">
                                @error('price_price') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>وصف الجائزة</label>
                                <textarea name="price_description" class="form-control" rows="5">{!! old('price_description', $hundredGame->basicPrice()->description ) !!}</textarea>
                                @error('price_description') <span
                                    style="color: red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>صورة الجائزة</label>
                                <div class="text-center">
                                    @if( $hundredGame->basicPrice()->image != '')
                                        <img height="120" width="150" src="{{ asset($hundredGame->basicPrice()->image) }}">
                                    @else
                                        <img height="120" width="150" src="{{ asset('images/no-image.png') }}">
                                    @endif
                                </div>
                                <input name="price_image" type="file"
                                       class="form-control-plaintext text-muted">
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
