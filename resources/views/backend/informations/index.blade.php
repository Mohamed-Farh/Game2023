@extends('layouts.auth_admin_app')

@section('title', 'معلومات')


@section('content')


    <div class="container">
        <div class="row ">
            <div class="col-6 d-flex text-left">
                <h1 class=" text-left">معلومات</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card-body align-items-center">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-hover table-striped table-light yajra-datatable">
                    <thead class="table-dark ">
                        <tr class="text-light">
                            <th class="text-light">النوع</th>
                            <th class="text-light">النص</th>
                            <th class="text-light">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($informations as $k => $information)
                            <tr data-entry-id="{{ $information->id }}">
                                <td class="text-center">{{ $information->name }}</td>
                                <td class="text-center">{!! $information->text !!}</td>
                                <td class="text-center">
                                    <div style="display: flex" class="text-center justify-content-between">
                                        <a href="{{ route('admin.informations.edit', $information) }}"
                                            class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

