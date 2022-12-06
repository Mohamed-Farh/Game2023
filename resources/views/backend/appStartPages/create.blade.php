@extends('layouts.auth_admin_app')

@section('title', 'انشاء صفحة جديدة')

@section('content')

<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h3 class="m-0 font-weight-bold text-primary">{{__('انشاء صفحة جديدة') }}</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.appStartPages.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">{{__('صفحات البداية للتطبيق') }}</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.appStartPages.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="text">الوصف</label>
                            <textarea  name="text" rows="3" class="form-control" required>{{ old('text') }}</textarea>
                            @error('text')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="status">حالة القسم</label>
                        <select name="status" class="form-control">
                            <option value="0" {{ old('status') == 0 ? 'selected' : null }}>غير نشط</option>
                            <option value="1" {{ old('status') == 1 ? 'selected' : null }}>نشط</option>
                        </select>
                        @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="number">رقم صفحة البدايه </label>
                            <input type="number" name="number" {{ old('number') }} class="form-control" required>
                            @error('number')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="image">صورة </label>
                            <input type="file" name="image" id="appStartPage_image" class="file-input-overview" required>
                            <span class="form-text text-muted">Image Width Should be (500px) X (500px)</span>
                            @error('image')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-group pt-4 text-center">
                    <button type="submit" name="submit" class="btn btn-primary">اضافة البيانات</button>
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
            });
        });
    </script>
@endsection
