@extends('layouts.auth_admin_app')

@section('title', 'Edit ِAbout')

@section('content')

    <script src="https://cdn.ckeditor.com/ckeditor5/32.0.0/classic/ckeditor.js"></script>

<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <div class="col-6">
                <h6 class="m-0 font-weight-bold text-primary">تعديل</h6>
            </div>

            <div class="col-6 text-right">
                <a href="{{ route('admin.abouts.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">حول التطبيق</span>
                </a>
            </div>
        </div>
        <div class="card-body">

            <form action="{{ route('admin.abouts.update', $about->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="row mt-4">
                    <div class="col-12">
                        <!-- The toolbar will be rendered in this container. -->
                        <div id="toolbar-container"></div>

                        <!-- This container will become the editable. -->
                        <textarea class="description" name="about" id="editor"
                            style="direction: rtl!important">{{ $about->about }}</textarea>
                        @error('about')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group pt-4 text-center">
                    <button type="submit" name="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/32.0.0/decoupled-document/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                language: {
                    // The UI will be English.
                    ui: 'en',

                    // But the content will be edited in Arabic.
                    content: 'ar'
                }
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });
    </script>
@endsection

