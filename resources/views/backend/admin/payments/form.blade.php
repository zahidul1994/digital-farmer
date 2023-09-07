<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="branch_name" class="form-control-label">Name * </label>
            {!! Form::text('branch_name', null, ['id' => 'branch_name','placeholder' =>'Branch Name', 'class' => 'form-control', 'required']) !!}
            @if ($errors->has('branch_name'))
            <span class="text-danger alert">{{ $errors->first('branch_name') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="branch_phone" class="form-control-label">Phone * </label>
            {!! Form::tel('branch_phone', null, ['id' => 'branch_phone','placeholder' =>'Phone Number', 'class' => 'form-control', 'required','max'=>11]) !!}
            @if ($errors->has('branch_phone'))
            <span class="text-danger alert">{{ $errors->first('branch_phone') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="branch_email" class="form-control-label">Email  </label>
            {!! Form::email('branch_email', null, ['id' => 'branch_email', 'placeholder' =>'Email Address','class' => 'form-control', 'max'=>11]) !!}
            @if ($errors->has('branch_email'))
            <span class="text-danger alert">{{ $errors->first('branch_email') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="branch_address" class="form-control-label">Address  *</label>
            {!! Form::textarea('branch_address', null, ['id' => 'branch_address','placeholder' =>'Branch Full Address', 'class' => 'form-control','required','rows'=>1]) !!}
            @if ($errors->has('branch_address'))
            <span class="text-danger alert">{{ $errors->first('branch_address') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="status" class="form-control-label">Status * </label>
            {!! Form::select('status',[1=>'Active',0=>'In-Active'],null, ['id' => 'status','class' => 'form-control
            select2','required'
            ]) !!}
            @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
        </div>
    </div>
   
</div>