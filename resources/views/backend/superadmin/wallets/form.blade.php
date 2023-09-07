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