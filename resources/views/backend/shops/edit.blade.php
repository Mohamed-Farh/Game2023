@extends('layouts.auth_admin_app')

@section('title', 'تعديل عرض')

@section('content')

    <div class="container">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h3 class="card-title card-title-new"> تعديل عرض</h3>
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
            <form action="{{ route('admin.shops.update', $shop->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div>
                    <div class="card-body row">
                        <div class="col-6 form-group">
                            <label>اسم العرض</label>
                            <input name="name" type="text" value="{{ old('name', $shop->name ) }}" class="form-control">
                            @error('name') <span style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-3 form-group">
                            <label>الحالة</label>
                            <select name="active" class="form-control">
                                <option value="1" {{ old('active',  $shop->active) == 1 ? 'selected' : null }}>نشط</option>
                                <option value="0" {{ old('active',  $shop->active) == 0 ? 'selected' : null }}>غير نشط</option>
                            </select>
                            @error('active')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-3 form-group">
                            <label>مجاني</label>
                            <select name="free" class="form-control">
                                <option value="1" {{ old('free',  $shop->free) == 1 ? 'selected' : null }}>مجاني</option>
                                <option value="0" {{ old('free',  $shop->free) == 0 ? 'selected' : null }}>مدفوع</option>
                            </select>
                            @error('free')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-6 form-group">
                            <div class="form-group">
                                <label>عدد التوكن بالعرض</label>
                                <input name="win_tokens" type="number" class="form-control"
                                       min="1.00" step="1.00" value="{{ old('win_tokens', $shop->win_tokens ) }}">
                                @error('win_tokens') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-6 form-group">
                            <div class="form-group">
                                <label>تكلفة العرض</label>
                                <input name="cost" type="number" class="form-control"
                                       min="0.00" step="0.01" value="{{ old('cost', $shop->cost ) }}">
                                @error('cost') <span style="color: red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-6 form-group">
                            <label>وقت وتاريخ البداية</label>
                            <input name="start" type="datetime-local" value="{{ old('start', $shop->start_time ) }}"
                                   class="form-control">
                            @error('start') <span style="color: red">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 form-group">
                            <label>وقت وتاريخ النهاية</label>
                            <input name="end" type="datetime-local" value="{{ old('end', $shop->end_time ) }}" class="form-control">
                            @error('end') <span style="color: red">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="image">صورة العرض</label>
                                <input type="file" name="image" id="category_image" class="file-input-overview">
                                <span class="form-text text-muted">Image Width Should be (500px) X (500px)</span>
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
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

@section('script')
    <script>
        $(function() {
            $('#category_image').fileinput({
                theme: "fas",
                maxFileCount: 1,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
                initialPreview: [
                    @if ($shop->image != '')
                        "{{ asset($shop->image) }}"
                    @endif
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [
                        @if ($shop->image != '')
                    {
                        caption: "{{ $shop->image }}",
                        size: '1000',
                        width: "120px",
                        url: "{{ route('admin.shops.removeImage', ['shop_id' => $shop->id, '_token' => csrf_token()]) }}",
                        key: "{{ $shop->id }}"
                    },
                    @endif
                ],
            });
        });
    </script>
@endsection
