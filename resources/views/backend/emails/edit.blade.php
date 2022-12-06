@extends('layouts.auth_admin_app')

@section('title', 'تعديل البريد الالكتروني')

@section('content')

    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex">
                <div class="col-6">
                    <h6 class="m-0 font-weight-bold text-primary">تعديل البريد الالكتروني</h6>
                </div>
                <div class="col-6 text-right">
                    <a href="{{ route('admin.emails.index') }}" class="btn btn-primary">
                        <span class="icon text-white-50">
                            <i class="fa fa-home"></i>
                        </span>
                        <span class="text">البريد الالكتروني</span>
                    </a>
                </div>
            </div>
            <div class="card-body">

                <form action="{{ route('admin.emails.update', $email->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="email">البريد الالكتروني</label>
                                <input type="text" name="email" value="{{ old('email', $email->email) }}"
                                    class="form-control">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="type">النوع</label>
                            <select name="type" class="form-control">
                                <option value="E-Mail" {{ old('type', $email->type) == 'E-Mail' ? 'selected' : null }}>
                                    E-Mail</option>
                                <option value="HR" {{ old('type', $email->type) == 'HR' ? 'selected' : null }}>HR</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label for="status">الحالة</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ old('status', $email->status) == 1 ? 'selected' : null }}>نشط
                                </option>
                                <option value="0" {{ old('status', $email->status) == 0 ? 'selected' : null }}>غير نشط
                                </option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
