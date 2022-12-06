@extends('layouts.auth_admin_app')

@section('title', 'تعديل صفحة البدايه')

@section('content')

<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h6 class="m-0 font-weight-bold text-primary">تعديل صفحة البدايه</h6>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.appStartPages.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">صفحات البداية للتطبيق</span>
                </a>
            </div>
        </div>
        <div class="card-body">

            <form action="{{ route('admin.appStartPages.update', $appStartPage->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="text">الوصف</label>
                            <textarea  name="text" rows="3" class="form-control" required>{{ old('text', $appStartPage->text) }}</textarea>
                            @error('text')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="status">الحالة</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ old('status', $appStartPage->status) == 1 ? 'selected' : null }}>نشط</option>
                            <option value="0" {{ old('status', $appStartPage->status) == 0 ? 'selected' : null }}>غير نشط</option>
                        </select>
                        @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="number">رقم صفحة البدايه </label>
                            <input type="number" name="number" value="{{ old('number', $appStartPage->number) }}" class="form-control" required>
                            @error('number')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="image">صورة صفحة البدايه</label>
                            <input type="file" name="image" id="appStartPage_image" class="file-input-overview">
                            <span class="form-text text-muted">Image Width Should be (500px) X (500px)</span>
                            @error('image')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-group pt-4 text-center">
                    <button type="submit" name="submit" class="btn btn-primary">تعديل البيانات</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('script')
    <script>
        $(function () {
            $('#appStartPage_image').fileinput({
                theme: "fas",
                maxFileCount: 1,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
                initialPreview:[
                    @if ($appStartPage->image != '')
                        "{{asset($appStartPage->image)}}"
                    @endif
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [
                    @if ($appStartPage->image != '')
                    {
                         caption: "{{ $appStartPage->image }}",
                         size: '1000',
                         width: "120px",
                         url: "{{ route('admin.appStartPages.removeImage', ['appStartPage_id'=>$appStartPage->id, '_token' => csrf_token()]) }}",
                         key: "{{ $appStartPage->id }}"
                    },
                    @endif
                ],
            });
        });
    </script>
@endsection
