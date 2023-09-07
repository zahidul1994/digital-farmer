<div class="row">

    {!! Form::hidden('payment_id',1) !!}
    <div class="col-md-6">
        <div class="form-group">
            <label for="amount" class="form-control-label">Amount * </label>
            {!! Form::text('amount', null, ['id' => 'price', 'class' =>'form-control', 'required']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="invoice" class="form-control-label">Invoice  * </label>
            {!! Form::text('invoice', null, ['id' => 'invoice', 'class' =>
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
    <div class="col-md-12">
    <div class="form-group">
        <label for="superadmin_id" class="form-control-label">Select Receiver  * </label>
        {!! Form::select('superadmin_id',Helper::getadminPaymentReceiver(), null, ['id' => 'superadmin_id', 'class' =>
        'form-control select2', 'required']) !!}
        @if ($errors->has('superadmin_id'))
        <span class="text-danger alert">{{ $errors->first('superadmin_id') }}</span>
        @endif
    </div>
    </div>
   
</div>