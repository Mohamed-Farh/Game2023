@extends('layouts.auth_admin_app')

@section('title', 'انشاء اوقات العمل')

@section('content')

    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex">
                <div class="col-6">
                    <h6 class="m-0 font-weight-bold text-primary">عنصر جديد</h6>
                </div>
                <div class="col-6 text-right">
                    <a href="{{ route('admin.working_times.index') }}" class="btn btn-primary">
                        <span class="icon text-white-50">
                            <i class="fa fa-home"></i>
                        </span>
                        <span class="text">اوقات العمل</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.working_times.store') }}" method="post">
                    @csrf
                    <div class="row mb-5">
                        <div class="col-9">
                            <label for="day">الصفحة</label>
                            <select name="day" class="form-control">
                                <option value="السبت" {{ old('day') == "السبت" ? 'selected' : null }}>السبت</option>
                                <option value="الاحد" {{ old('day') == "الاحد" ? 'selected' : null }}>الاحد</option>
                                <option value="الاثنين" {{ old('day') == "الاثنين" ? 'selected' : null }}>الاثنين</option>
                                <option value="الثلاثاء" {{ old('day') == "الثلاثاء" ? 'selected' : null }}>الثلاثاء</option>
                                <option value="الاربعاء" {{ old('day') == "الاربعاء" ? 'selected' : null }}>الاربعاء</option>
                                <option value="الخميس" {{ old('day') == "الخميس" ? 'selected' : null }}>الخميس</option>
                                <option value="الجمعة" {{ old('day') == "الجمعة" ? 'selected' : null }}>الجمعة</option>
                            </select>
                            @error('day')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-3">
                            <label for="status">الحالة</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ old('status') == 1 ? 'selected' : null }}>نشط</option>
                                <option value="0" {{ old('status') == 0 ? 'selected' : null }}>غير نشط</option>
                            </select>
                            @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-6">
                            <label for="start">وقت البداية</label>
                            <input type="time" class="form-control" name="start">
                            @error('start')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-6">
                            <label for="end">وقت النهاية</label>
                            <input type="time" class="form-control" name="end">
                            @error('end')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="form-group pt-4 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


