<div class="row">
  <div class="col-md-9">
    <div class="form-group">
      <label for="supplier_id" class="form-control-label">Select Supplier * </label>
      {!! Form::select('supplier_id',$suppliers,null, ['id' => 'supplier_id','class'
      => 'form-control
      select2','required','placeholder'=>'Select One'
      ]) !!}
      @if ($errors->has('supplier_id')) <span class="text-danger alert">{{ $errors->first('supplier_id')
        }}</span> @endif
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label for="due" class="form-control-label">Due Amount</label>
      {!! Form::text('due',null, ['id' => 'due','class'
      => 'form-control','readonly']) !!}

    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group">
      <label for="payment_method" class="form-control-label">Payment Method * </label>
      {!! Form::select('payment_method',Helper::paymentMethodPluckValue(), null, ['id' => 'payment_method', 'class' =>
      'form-control select2', 'tabindex' => 5]) !!}

    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="paid" class="form-control-label">Pay Amount * </label>
      {!! Form::number('paid',null, ['id' => 'paid', 'class' =>'form-control',
      'required','step'=>'any','min'=>1,'max'=>9999999999999,]) !!}
    </div>
  </div>
</div>
<div class=" row d-none" id="MobileBankPayment">
  <div class="col-md-6">
    <div class="form-group">
      <label for="phone_number" class="form-control-label">Sending Mobile Number * </label>
      {!! Form::tel('phone_number',null, ['id' => 'phone_number', 'class' =>'form-control']) !!}
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="transaction_number" class="form-control-label">Transaction Number/ID * </label>
      {!! Form::text('transaction_number',null, ['id' => 'paid', 'class' =>'form-control'
      ]) !!}
    </div>
  </div>
</div>
<div class=" row d-none" id="BankPayment">
  <div class="col-md-6">
    <div class="form-group">
      <label for="bank_name" class="form-control-label">Bank Name * </label>
      {!! Form::text('bank_name',null, ['id' => 'bank_name', 'class' =>'form-control']) !!}
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="bank_account_number" class="form-control-label">Bank Acouunt NO * </label>
      {!! Form::number('bank_account_number',null, ['id' => 'bank_account_number', 'class' =>'form-control'
      ]) !!}
    </div>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group">
    <label for="note" class="form-control-label">Note </label>
    {!! Form::textarea('note',null, ['id' => 'note','class'
    => 'form-control','rows'=>2
    ]) !!}
    @if ($errors->has('note')) <span class="text-danger alert">{{ $errors->first('note')
      }}</span> @endif
  </div>
</div>