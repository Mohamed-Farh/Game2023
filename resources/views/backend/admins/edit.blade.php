@extends('layouts.auth_admin_app')

@section('title', 'تعديل البيانات')

@section('style')
    <link rel="stylesheet" href="{{ asset('backend/vendor/select2/css/select2.min.css') }}">
@endsection

@section('content')

<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h6 class="m-0 font-weight-bold text-primary">تعديل البيانات</h6>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.admins.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">الادمن</span>
                </a>
            </div>
        </div>
        <div class="card-body">

            <form action="{{ route('admin.admins.update', $admin->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="first_name">الاسم الاول</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $admin->first_name ) }}" class="form-control">
                            @error('first_name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label for="last_name">الاسم الاخير</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $admin->last_name ) }}" class="form-control">
                            @error('last_name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label for="username">اسم المستخدم</label>
                            <input type="text" name="username" value="{{ old('username', $admin->username ) }}" class="form-control">
                            @error('username')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="email">البريد الالكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email ) }}" class="form-control">
                            @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label for="mobile">رقم الهاتف</label>
                            <input type="text" name="mobile" value="{{ old('mobile', $admin->mobile ) }}" class="form-control">
                            @error('mobile')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label for="password">كلمة المرور</label>
                            <input type="password" name="password" class="form-control">
                            @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-3">
                        <label for="active">الحالة</label>
                        <select name="active" class="form-control">
                            <option value="1" {{ old('active', $admin->active ) == 1 ? 'selected' : null }}>نشط</option>
                            <option value="0" {{ old('active', $admin->active ) == 0 ? 'selected' : null }}>غير نشط</option>
                        </select>
                        @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <label for="permissions">الصلاحيات</label>
                        <select name="permissions[]" class="form-control select2" multiple="multiple">
                            @forelse ($permissions as $permission )
                                <option value="{{ $permission->id }}" {{ in_array($permission->id, old('permission', $adminPermissions) ) ? 'selected' : null }}>{{ $permission->display_name }}</option>
                            @empty
                            @endforelse
                        </select>
                        @error('permissions')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="user_image">صورة البروفيل</label>
                            <input type="file" name="user_image" id="category_image" class="file-input-overview">
                            <span class="form-text text-muted">Image Width Should be (500px) X (500px)</span>
                            @error('user_image')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-group pt-4 text-center">
                    <button type="submit" name="submit" class="btn btn-primary">تحديث البيانات</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('script')
    <script src="{{ asset('backend/vendor/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {

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
                initialPreview:[
                    @if ($admin->user_image != '')
                        "{{asset( $admin->user_image)}}"
                    @endif
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [
                    @if ($admin->user_image != '')
                    {
                         caption: "{{ $admin->user_image }}",
                         size: '1000',
                         width: "120px",
                         url: "{{ route('admin.admins.removeImage', ['admin_id'=>$admin->id, '_token' => csrf_token()]) }}",
                         key: "{{ $admin->id }}"
                    },
                    @endif
                ],
            });
        });
    </script>
@endsection
