@extends('layouts.auth_admin_app')

@section('title', 'انشاء دولة')

@section('content')

<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h6 class="m-0 font-weight-bold text-primary">عنصر جديد</h6>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.countries.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">الدول</span>
                </a>
            </div>
        </div>
        <div class="card-body">

            <form action="{{ route('admin.countries.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-9">
                        <div class="form-group">
                            <label for="name">اسم الدولة</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-3">
                        <label for="status">حالة الدولة</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ old('status') == 1 ? 'selected' : null }}>نشط</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : null }}>غير نشط</option>
                        </select>
                        @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group pt-4 text-center">
                    <button type="submit" name="submit" class="btn btn-primary">اضافة الدولة</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

