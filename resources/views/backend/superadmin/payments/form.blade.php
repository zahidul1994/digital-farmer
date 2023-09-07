<div class="form-group">
    <label for="payment_name" class="form-control-label">Payment Method Name * </label>
    {!! Form::text('payment_name', null, ['id' => 'payment_name','class' => 'form-control','required',
    ]) !!}
    @if ($errors->has('payment_name')) <span class="text-danger alert">{{ $errors->first('payment_name') }}</span>
    @endif
</div>
    
<div class="input-group">
    <span class="input-group-btn">
      <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
        <i class="fa fa-picture-o"></i>  Image [300X300] *
      </a>
    </span>
    {!! Form::text('image', null, ['id' => 'thumbnail', 'class' => 'form-control', 'required','readonly','style'=>'height:40px']) !!}
    @if ($errors->has('image'))
    <span class="text-danger alert">{{ $errors->first('image') }}</span>
  @endif
<img id="holder" style="margin-top:15px;max-height:100px;">
</div>
<div class="form-group">
    <label for="status" class="form-control-label">Status * </label>
    {!! Form::select('status',[1=>'Active',0=>'In-Active'],null, ['id' => 'status','class' => 'form-control
    select2','required'
    ]) !!}
    @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
</div>
</div>