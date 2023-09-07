@extends('backend.layouts.master')
@section('title', 'Cash Payment')
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
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header pb-4">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Cash Payment</p>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @include('partial.formerror')
                        {!! Form::open(['route' => Request::segment(1) . '.paymentsStore', 'method' => 'POST', 'files'
                        => true]) !!}
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount" class="form-control-label">Amount * </label>
                                {!! Form::text('amount', Session::get('paymentAmount'), ['id' => 'price', 'class' =>'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice" class="form-control-label">Invoice  * </label>
                                {!! Form::text('invoice', null, ['id' => 'invoice', 'class' =>
                                'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="comment" class="form-control-label">Comment * </label>
                                {!! Form::textarea('comment',null, ['id' => 'comment','class'
                                => 'form-control','rows'=>2
                                ]) !!}
                                @if ($errors->has('comment')) <span class="text-danger alert">{{ $errors->first('comment')
                                    }}</span> @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="receiver_id" class="form-control-label">Select Reveicer * </label>
                                {!! Form::select('receiver_id',Helper::getadminPaymentReceiver(),null, ['id' => 'receiver_id','class'
                                => 'form-control
                                select2','required'
                                ]) !!}
                                @if ($errors->has('receiver_id')) <span class="text-danger alert">{{ $errors->first('receiver_id')
                                    }}</span> @endif
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ url()->previous()}}" class="btn btn-success">
                                <i class="fa fa-backward"> </i> Back

                            </a>
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Submit</button>
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
<!-- select2 init -->
<script src="{{ asset('backend/assets/select2/js/select2.min.js') }}"></script>

<script>
    $(".select2").select2({
  tags: true
});
</script>

@endpush