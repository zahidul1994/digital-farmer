@extends('backend.layouts.master')
@section('title', 'Update Wallet')
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
                            <p class="mb-0">Wallet Update (Cash)</p>
                            <a href="{{ route(Request::segment(1) . '.wallets.index') }}"
                                class="btn btn-primary btn-sm ms-auto"><i class="fa fa-backward"> </i>  Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @include('partial.formerror')
                            {!! Form::model($wallet, [
                                'route' => [Request::segment(1) . '.wallets.update', $wallet->id],
                                'method' => 'PATCH',
                                'files' => true,
                            ]) !!}
                           <div class="row">

                            {!! Form::hidden('payment_id',1) !!}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="credit" class="form-control-label">Amount (Credit) * </label>
                                    {!! Form::text('credit', null, ['id' => 'credit', 'class' =>'form-control', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice" class="form-control-label">Invoice  * </label>
                                    {!! Form::text('invoice', json_decode(@$wallet->details,true), ['id' => 'invoice', 'class' =>
                                    'form-control', 'required']) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note" class="form-control-label">Note * </label>
                                    {!! Form::textarea('note',null, ['id' => 'note','class'
                                    => 'form-control','rows'=>2
                                    ]) !!}
                                    @if ($errors->has('note')) <span class="text-danger alert">{{ $errors->first('note')
                                        }}</span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="admin_id" class="form-control-label">Select Admin * </label>
                                {!! Form::select('admin_id',Helper::adminPluckValue(), null, ['id' => 'admin_id', 'class' =>
                                'form-control select2', 'required']) !!}
                                @if ($errors->has('admin_id'))
                                <span class="text-danger alert">{{ $errors->first('admin_id') }}</span>
                                @endif
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-control-label">Status * </label>
                                {!! Form::select('status',[1=>'Approve',0=>'Pending',2=>'Reject'],null, ['id' => 'status','class' => 'form-control
                                select2','required'
                                ]) !!}
                                @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
                            </div>
                            </div>
                        
                        </div>
                           
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
<!-- CKEditor init -->
<script src="{{ asset('backend/assets/select2/js/select2.min.js') }}"></script>

<script>
    $('.select2').select2();
  </script>

@endpush


