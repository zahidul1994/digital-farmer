<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="admin_id" class="form-control-label">Select Admin * </label>
            {!! Form::select('admin_id',Helper::adminPluckValue(),null, ['id' => 'admin_id','class' => 'form-control
            select2','required'
            ]) !!}
            @if ($errors->has('admin_id')) <span class="text-danger alert">{{ $errors->first('admin_id') }}</span> @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="shop_name" class="form-control-label">Shop Name * </label>
            {!! Form::text('shop_name', null, ['id' => 'shop_name', 'class' => 'form-control', 'required']) !!}
            @if ($errors->has('shop_name'))
            <span class="text-danger alert">{{ $errors->first('shop_name') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="shop_phone" class="form-control-label">Shop Phone Number * </label>
            {!! Form::tel('shop_phone', null, ['id' => 'shop_phone', 'class' => 'form-control', 'required','max'=>11]) !!}
            @if ($errors->has('shop_phone'))
            <span class="text-danger alert">{{ $errors->first('shop_phone') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="shop_email" class="form-control-label">Shop  Email Address  </label>
            {!! Form::email('shop_email', null, ['id' => 'shop_email', 'class' => 'form-control', 'max'=>11]) !!}
            @if ($errors->has('shop_email'))
            <span class="text-danger alert">{{ $errors->first('shop_email') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="shop_address" class="form-control-label">Shop  Address  </label>
            {!! Form::textarea('shop_address', null, ['id' => 'shop_address', 'class' => 'form-control','required','rows'=>1]) !!}
            @if ($errors->has('shop_address'))
            <span class="text-danger alert">{{ $errors->first('shop_address') }}</span>
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