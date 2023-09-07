@extends('backend.layouts.superadminmaster')
@section('title', 'Update Payment')
@push('css')
<link href="{{ asset('backend/assets/select2/css/select2.min.css') }}" rel="stylesheet" />
<style>
    .select2-selection__choice {
        background-color: var(--bs-gray-200);
        border: none !important;
        font-size: 12px;
        font-size: 0.85rem !important;
    }
</style>
@endpush
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header pb-4">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Payment Update</p>
                        <a href="{{route(Request::segment(1).'.packages.index')}}"
                            class="btn btn-primary btn-sm ms-auto"><i class="fa fa-backward"> </i>  Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @include('partial.formerror')

                        {!! Form::model($payment, [
                        'route' => [Request::segment(1) . '.payments.update', $payment->id],
                        'method' => 'PATCH',
                        'files' => true,
                        ]) !!}
                        @include('backend.superadmin.payments.form')
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
@push('js')
<script src="{{ asset('backend/assets/select2/js/select2.min.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
  $(".select2").select2();
$('#lfm').filemanager('filemanager');
  </script>

@endpush
