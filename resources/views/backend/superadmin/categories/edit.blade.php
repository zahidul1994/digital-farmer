@extends('backend.layouts.master')
@section('title', 'Update Category')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-4">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Category Update</p>
                            <a href="{{ route(Request::segment(1) . '.categories.index') }}"
                                class="btn btn-primary btn-sm ms-auto"><i class="fa fa-backward"> </i>  Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @include('partial.formerror')
                            {!! Form::model($category, [
                                'route' => [Request::segment(1) . '.categories.update', $category->id],
                                'method' => 'PATCH',
                                'files' => true,
                            ]) !!}
                            @include('backend.superadmin.categories.form')
                           
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Update</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
