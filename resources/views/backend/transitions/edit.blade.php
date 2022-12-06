@extends('layouts.admin_auth_app')

@section('title', 'تعديل جائزة')

@section('content')

    <div class="container">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h3 class="card-title card-title-new"> تعديل جائزة</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('dashboard.hundred-games.showPrices', $price->game_id) }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">القائمة</span>
                </a>
            </div>
        </div>
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <form action="{{ route('dashboard.prices.update', $price->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div>
                    <div class="card-body row">
                        <div class="col-12 form-group">
                            <label>اسم الجائزة</label>
                            <input name="price_name" type="text" value="{{ old('price_name', $price->name ) }}" class="form-control">
                            @error('price_name') <span style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 form-group">
                            <label>سعر الجائزة</label>
                            <input name="price_value" type="number" value="{{ old('price_name', $price->value ) }}" class="form-control"
                                   min="0.00" step="0.01">
                            @error('price_price') <span style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 form-group">
                            <label>الحالة</label>
                            <select name="active" class="form-control">
                                <option value="1" {{ old('active',  $price->active) == 1 ? 'selected' : null }}>نشط</option>
                                <option value="0" {{ old('active',  $price->active) == 0 ? 'selected' : null }}>غير نشط</option>
                            </select>
                            @error('active')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 form-group">
                            <label>وصف الجائزة</label>
                            <textarea name="price_description" rows="5" class="form-control">{!! old('price_description', $price->description ) !!}</textarea>
                            @error('price_description') <span
                                style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 form-group">
                            <label>وقت وتاريخ البداية</label>
                            <input name="start" type="datetime-local" value="{{ old('start', $hundredGame->start ) }}"
                                   class="form-control">
                            @error('start') <span style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 form-group">
                            <label>وقت وتاريخ النهاية</label>
                            <input name="end" type="datetime-local" value="{{ old('end', $hundredGame->end ) }}" class="form-control">
                            @error('end') <span style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>صورة الجائزة</label>
                                <div class="text-center">
                                    <img height="120" width="150" src="{{ $price->getFirstMediaUrl() ?? ''}}">
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
