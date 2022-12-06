<div class="row">
    <div class="col-12">
        <div class="card-body align-items-center">
            <form action="{{ route('admin.states.index') }}" method="get">
                <div class="row  align-items-center">
                    <div class="col-3">
                        <div class="form-group input-icon">
                            <input type="text" name="keyword" value="{{ old('keyword', request()->input('keyword')) }}" class="form-control form-control-solid" placeholder="ابحث هنا...">
                            <span>
                                <i class="flaticon2-search-1 text-muted"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <select name="status" class="form-control form-control-solid">
                                <option value="">الحالة</option>
                                <option value="1" {{ old('status', request()->input('status')) == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('status', request()->input('status')) == '0' ? 'selected' : ''  }}>غير نشط</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <select name="sort_by" class="form-control form-control-solid">
                                <option value="">الترتيب</option>
                                <option value="id" {{ old('sort_by', request()->input('sort_by')) == 'id' ? 'selected' : '' }}>الرقم</option>
                                <option value="name" {{ old('sort_by', request()->input('sort_by')) == 'name' ? 'selected' : ''  }}>الاسم</option>
                                <option value="created_at" {{ old('sort_by', request()->input('sort_by')) == 'created_at' ? 'selected' : ''  }}>التاريخ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <select name="order_by" class="form-control form-control-solid">
                                <option value="">العرض</option>
                                <option value="asc" {{ old('order_by', request()->input('order_by')) == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                                <option value="desc" {{ old('order_by', request()->input('order_by')) == 'desc' ? 'selected' : ''  }}>تنازلي</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <select name="limit_by" class="form-control form-control-solid">
                                <option value="">عدد الصفوف</option>
                                <option value="10" {{ old('limit_by', request()->input('limit_by')) == '10' ? 'selected' : '' }}>10</option>
                                <option value="20" {{ old('limit_by', request()->input('limit_by')) == '20' ? 'selected' : ''  }}>20</option>
                                <option value="50" {{ old('limit_by', request()->input('limit_by')) == '50' ? 'selected' : ''  }}>50</option>
                                <option value="100" {{ old('limit_by', request()->input('limit_by')) == '100' ? 'selected' : ''  }}>100</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-light-primary px-6 font-weight-bold">بحث</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

